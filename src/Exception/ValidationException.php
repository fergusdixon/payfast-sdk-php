<?php
/**
 * PayFastSDK API Exception
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
 * Validation exception for PayFastSDK
 *
 * @package PayFastSDK
 */
class ValidationException extends Exception
{
    const UNDEFINED_VALIDATION_EXCEPTION = 12100;
    const INVALID_VERB_EXCEPTION = 12101;
    const INVALID_METHOD_EXCEPTION = 12102;

    /**
     * Custom validation exception handler
     *
     * @var integer $code The error code (as per above) [12100 is Generic code]
     * @var string $extra Any additional information to be included
     */
    public function __construct($code = 12100, $extra = '')
    {
        $message = sprintf(
            'Validation error %s %s',
            $extra,
            ExceptionMessages::$validationMessages[$code]
        );

        parent::__construct($message, $code);
    }
}
