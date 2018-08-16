<?php
/**
 * PayFastSDK Library
 *
 * @category Library
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @author   Fergus Strangways-Dixon <fergusdixon@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/blob/master/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */

namespace FergusDixon\PayFastSDK\Request;

use DateTime;
use DateTimeZone;
use FergusDixon\PayFastSDK\Exception\ApiException;

use FergusDixon\PayFastSDK\Exception\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * RequestHandler Class
 *
 * @category Library
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/blob/master/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */
class RequestHandler
{
    /**
     * Current API version
     */
    const VERSION = 'v1';

    /**
     * GuzzleHttp Client
     *
     * @var Client $client
     */
    private $client;

    /**
     * The merchant ID from the config
     *
     * @var string $merchantId
     */
    private $merchantId;

    /**
     * The passphrase from the config
     *
     * @var string $passPhrase
     */
    private $passPhrase;

    /**
     * The endpoint that gets passed in from config
     *
     * @var string $endpoint
     */
    private $endpoint;

    /**
     * Port to use
     *
     * @var integer $port
     */
    private $port;

    /**
     * Use SSL
     *
     * @var integer $ssl
     */
    private $ssl;

    /**
     * Testing mode?
     *
     * @var string $testing
     */
    private $testing = false;

    /**
     * Request handler constructor
     *
     * @param array $config The connection config
     */
    public function __construct(array $config)
    {
        $this->merchantId = $config['merchantId'];
        $this->passPhrase = $config['passPhrase'];
        $this->endpoint = $config['endpoint'];
        $this->port = $config['port'];
        $this->ssl = $config['ssl'];
        $this->testing = $config['testing'];

        $this->client = new Client();
    }

    /**
     * @param String $verb
     * @param $method
     * @param array $parameters
     * @return array|string
     * @throws ApiException
     * @throws ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function custom(String $verb, $method, $parameters = [])
    {
        // Only GET and POST are supported by PayFast
        if ($verb !== 'GET' && $verb !== 'POST') {
            throw new ValidationException(
                ValidationException::INVALID_VERB_EXCEPTION,
                $verb
            );
        }

        if (is_null($method)) {
            throw new ValidationException(
                ValidationException::INVALID_METHOD_EXCEPTION,
                sprintf('Method cannot be null.')
            );
        }

        return $this->handleRequest($verb, $method, $parameters);
    }

    /**
     * Makes a request using Guzzle
     *
     * @param $verb
     * @param $method
     * @param array $parameters
     * @return array|string
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see RequestHandler::request()
     */
    private function handleRequest($verb, $method, array $parameters)
    {
        // Get the timestamp in the required format
        $date = new DateTime();
        $date->setTimeZone(new DateTimeZone('UTC'));
        $date->setTimestamp(time());
        $timestamp = $date->format('Y-m-d\TH:i:s');

        $signParameters = $this->getSigningObject($timestamp);

        // Add the provided params to $signParameters
        foreach ($parameters as $key => $value) {
            $signParameters[$key] = $value;
        }

        // MD5 hash the fields
        $signature = $this->generateSignature($signParameters);

        // Create the headers
        $request = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Content-Length' => 0,
                'signature' => $signature,
                'timestamp' => $timestamp,
                'version' => self::VERSION,
                'merchant-id' => $this->merchantId,
            ],
        ];

        // Create the URI
        $protocol = $this->ssl ? 'https' : 'http';

        $uri = sprintf(
            '%s:%s:%s%s',
            $protocol,
            $this->endpoint,
            $this->port,
            $method
        );

        // Are we using the sandbox?
        if ($this->testing === true) {
            $uri .= '?testing=true';
        }

        if ($verb === 'POST') {
            $data = json_encode($parameters);
            $request['headers']['Content-Length'] = strlen($data);
            $request['body'] = $data;
        }

        // We need to add params to URI
        if ($verb === 'GET') {
            if (!$this->testing) {
                $uri .= '?';
            } elseif (sizeof($parameters) > 0) {
                $uri .= '&';
            }

            foreach ($parameters as $key => $value) {
                $uri .= sprintf('%s=%s&', $key, $value);
            }

            // Trim trailing ampersand
            $uri = rtrim($uri, '&');
        }

        try {
            $response = $this->client->request($verb, $uri, $request);
            $body = (string)$response->getBody();
            return $body;
        } catch (RequestException $exception) {
            $this->handleException($exception);
        }

        return json_decode($response->getBody());
    }

    /**
     * Handles all API exceptions, and adds the official exception terms
     * to the message.
     *
     * @param RequestException the original exception
     *
     * @throws ApiException
     */
    private function handleException($exception)
    {
        $body = $exception->getResponse()->getBody()->getContents();

        throw new ApiException($body, $exception->getCode(), $exception);
    }

    /**
     * Get signing object for PayFast API requests
     *
     * @param $timestamp
     * @return array The bits for the signature
     */
    private function getSigningObject($timestamp)
    {
        $parts = [
            'merchant-id' => $this->merchantId,
            'version' => self::VERSION,
            'timestamp' => $timestamp,
            'passphrase' => $this->passPhrase,
        ];

        return $parts;
    }

    /**
     * Generates signature
     *
     * @param array $parameters
     * @return string
     */
    public function generateSignature(array $parameters)
    {
        $sortedSigningString = $this->sortParameters($parameters);

        return md5($sortedSigningString);
    }

    /**
     * Sorts query parameters
     *
     * The request parameters are collected, sorted and concatenated into a normalized string:
     * - Parameters in the signature object
     * - Parameters in the HTTP POST request body (with a content-type of application/x-www-form-urlencoded).
     *
     * Parameters are sorted by name, using lexicographical byte value ordering
     * If two or more parameters share the same name, they are sorted by their value.
     * For each parameter, the name is separated from the corresponding value by an '=' character
     * even if the value is empty.
     * Each name-value pair is separated by an '&' character
     *
     * @param array $parameters
     * @return string
     */
    protected function sortParameters(array $parameters)
    {
        $elements = [];
        ksort($parameters);
        foreach ($parameters as $name => $value) {
            if (is_array($value)) {
                sort($value);
                foreach ($value as $element) {
                    array_push(
                        $elements,
                        sprintf('%s=%s', ($name), urlencode($element))
                    );
                }
                continue;
            }
            array_push(
                $elements,
                sprintf('%s=%s', ($name), urlencode($value))
            );
        }
        return join('&', $elements);
    }
}
