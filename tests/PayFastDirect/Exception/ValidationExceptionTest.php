<?php
namespace FergusDixon\PayFastSDK\Tests\PayFastSDK\Exception;

use FergusDixon\PayFastSDK\Exception\ExceptionMessages;
use FergusDixon\PayFastSDK\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    /**
     * Extra string in Validation Exception message
     */
    const EXTRA = 'extra';

    /**
     * Tests if exception is valid: class, code, message
     * @throws ValidationException
     */
    public function testConstructor()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::UNDEFINED_VALIDATION_EXCEPTION);
        $this->expectExceptionMessageRegExp(
            sprintf("/%s$/", ExceptionMessages::$validationMessages[
                ValidationException::UNDEFINED_VALIDATION_EXCEPTION
            ])
        );

        throw new ValidationException(ValidationException::UNDEFINED_VALIDATION_EXCEPTION);
    }

    /**
     * Tests if exception is valid with extra argument: class, code, message
     * @throws ValidationException
     */
    public function testConstructorWithExtra()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionCode(ValidationException::UNDEFINED_VALIDATION_EXCEPTION);
        $this->expectExceptionMessageRegExp(
            sprintf(
                "/%s %s$/",
                static::EXTRA,
                ExceptionMessages::$validationMessages[
                    ValidationException::UNDEFINED_VALIDATION_EXCEPTION
                ]
            )
        );

        throw new ValidationException(ValidationException::UNDEFINED_VALIDATION_EXCEPTION, static::EXTRA);
    }
}
