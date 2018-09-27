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
 * API exception for PayFastSDK
 *
 * @package PayFastSDK
 */
class ApiException extends Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        // Construct message from JSON if required.
        if (preg_match('/^[\[\{]\"/', $message)) {
            $messageObject = json_decode($message);
            $response = [
                'code' => $code,
                'message' => $messageObject,
                'reason' => 'Exception',
            ];

            $errors = [];
            if ($code === 400) {
                $response['reason'] = 'There were validation errors.';
                foreach ($messageObject->data->response as $key => $value) {
                    $name = ucwords(join(' ', explode('_', $key)));
                    $validation = [
                        'key' => $key,
                        'value' => $value,
                        'name' => $name,
                        'message' => sprintf('%s: %s', $key, $value),
                    ];

                    if (stristr($value, 'is required')) {
                        $validation['message'] = sprintf('%s is required.', $name);
                    }

                    if (stristr($value, 'be between')) {
                        $validation['message'] = sprintf('%s %s.', $name, strtolower($value));
                    }

                    $errors[] = $validation;
                }
            }

            $response['errors'] = $errors;

            $message = json_encode($response);
        }

        parent::__construct($message, $code, $previous);
    }
}
