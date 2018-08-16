<?php
/**
 * PayFastSDK Library
 *
 * @category Library
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/blob/master/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */

namespace FergusDixon\PayFastSDK\Request;

use FergusDixon\PayFastSDK\Exception\ApiException;

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
     * Method to call
     *
     * @var string $method
     */
    private $method;

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
        $this->method= $config['method'];
        $this->port = $config['port'];
        $this->ssl = $config['ssl'];
        $this->testing = $config['testing'];

        $this->client = new Client();
    }

    /**
     * Makes a request using Guzzle
     *
     * @param array $options Request options
     * @return array|string
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see RequestHandler::request()
     */
    public function handleRequest(array $options)
    {
        $protocol = $this->ssl ? 'https' : 'http';

        $uri = sprintf(
            '%s:%s:%s%s',
            $protocol,
            $this->endpoint,
            $this->port,
            $this->method
        );

        if ($this->testing === true) {
            $uri .= '?testing=true';
        }

        try {
            $response = $this->client->request('POST', $uri, $options);
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
