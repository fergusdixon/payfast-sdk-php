<?php
/**
 * PayFastSDK
 *
 * @category Base
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/blob/master/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */

namespace FergusDixon\PayFastSDK;

use FergusDixon\PayFastSDK\Config\Config;
use FergusDixon\PayFastSDK\Request\RequestHandler;

/**
 * Base class for PayFastSDK manipulation
 *
 * @package PayFastSDK
 */
class PayFastSDK
{
    /**
     * Configuration
     *
     * @var Config $config
     */
    public $config;

    /**
     * Oauth Request Handler
     *
     * @var RequestHandler $request
     */
    public $request;

    /**
     * PayFastSDK constructor
     *
     * @param array $config The API client config details
     * @throws Exception\ConfigException
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->request = new RequestHandler($this->config->getRequestHandlerConfig());
    }
}
