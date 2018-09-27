<?php
/**
 * PayFastSDK Config Exception
 *
 * @category Exception
 * @package  PayFastSDK
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/fergusdixon/payfast-sdk-php/blob/master/LICENSE>
 * @link     https://github.com/fergusdixon/payfast-sdk-php
 */

namespace FergusDixon\PayFastSDK\Exception;

use Exception;

/**
 * Config exception for PayFastSDK
 *
 * @package PayFastSDK
 */
class ConfigException extends Exception
{
    const UNDEFINED_CONFIG_EXCEPTION = 12000;
    const MISSING_MERCHANT_ID = 12001;
    const MISSING_PASSPHRASE = 12002;
    // number constants up from this number - 12003 is next

    /**
     * Custom validation exception handler
     *
     * @var integer $code The error code (as per above) [11000 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 12000, $extra = '')
    {
        $message = sprintf(
            'Config error %s %s',
            $extra,
            ExceptionMessages::$configErrorMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
