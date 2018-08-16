<?php

namespace FergusDixon\PayFastSDK\Config;

use FergusDixon\PayFastSDK\Exception\ConfigException;

/**
 * PayFastSDK Config
 *
 * @category Configuration
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */
class Config
{
    /**
     * PayFast Merchant ID
     *
     * @var string $merchantId
     */
    private $merchantId = null;

    /**
     * PayFast Merchant Passphrase
     *
     * @var string $passPhrase
     */
    private $passPhrase = null;

    /**
     * The endpoint
     *
     * @var string $endpoint
     */
    public $endpoint = '//api.payfast.co.za';

    /**
     * The port
     *
     * @var integer $port
     */
    public $port = 443;

    /**
     * Use SSL?
     *
     * @var boolean $ssl
     */
    public $ssl = true;

    /**
     * Whether or not we're in test mode
     *
     * @var boolean $cache
     */
    public $testing = false;

    /**
     * Construct the config object
     *
     * @param array $config An array of configuration options
     * @throws ConfigException
     */
    public function __construct($config)
    {
        // Throw exceptions on essentials
        $this->checkAndSetEssentials($config);

        // optionals
        $this->checkAndSetOverrides($config);
    }

    /**
     * Check and set essential configuration elements.
     *
     * Required:
     *
     *   - Merchant ID
     *   - Passphrase
     *
     * @param array $config An array of configuration options
     * @throws ConfigException
     */
    private function checkAndSetEssentials($config)
    {
        // Validate and throw
        if (!isset($config['merchantId']) || empty($config['merchantId'])) {
            throw new ConfigException(ConfigException::MISSING_MERCHANT_ID);
        }

        if (!isset($config['passPhrase']) || empty($config['passPhrase'])) {
            throw new ConfigException(ConfigException::MISSING_PASSPHRASE);
        }

        // Set
        $this->merchantId = (string)$config['merchantId'];
        $this->passPhrase = (string)$config['passPhrase'];
    }

    /**
     * Check and set any overriding elements.
     *
     * Optionals:
     *
     *   - Endpoint
     *   - Port
     *   - Method
     *   - Testing
     *
     * @param array $config An array of configuration options
     */
    private function checkAndSetOverrides($config)
    {
        if (isset($config['endpoint']) && !empty($config['endpoint'])) {
            $this->endpoint = (string)$config['endpoint'];
        }

        if (isset($config['port']) && !empty($config['port'])) {
            $this->port = (integer)$config['port'];
        }

        if (isset($config['ssl']) && !empty($config['ssl'])) {
            $this->ssl = (boolean)$config['ssl'];
        }

        if (isset($config['testing']) && !empty($config['testing'])) {
            $this->testing = (boolean)$config['testing'];
        }
    }

    /**
     * Retrieves the expected config for the API
     *
     * @return array
     */
    public function getRequestHandlerConfig()
    {
        $config = [
            'merchantId' => $this->merchantId,
            'passPhrase' => $this->passPhrase,
            'endpoint' => $this->endpoint,
            'port' => $this->port,
            'ssl' => $this->ssl,
            'testing' => $this->testing,
        ];

        return $config;
    }
}
