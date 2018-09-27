<?php

namespace FergusDixon\PayFastSDK\Exception;

/**
 * Exception message strings for the Exception objects that get thrown.
 */
class ExceptionMessages
{
    // Config codes 110xx
    public static $configErrorMessages = [
        // Methods
        12000 => 'Undefined config exception',
        12001 => 'Missing merchant ID',
        12002 => 'Missing passphrase',
    ];

    // Validation codes 111xx
    public static $validationMessages = [
        12100 => 'Unknown validation error',
        12101 => 'Invalid verb',
        12102 => 'Invalid method'
    ];

    // Maps to standard HTTP error codes
    public static $strings = [
        400 => '400',
        401 => '401',
        402 => '402',
        404 => '404',
        405 => '405',
        409 => '409',
        415 => '415',
        429 => '429',
        500 => '500',
        503 => '503',
    ];
}
