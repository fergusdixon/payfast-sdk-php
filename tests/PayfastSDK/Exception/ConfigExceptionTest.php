<?php
namespace FergusDixon\PayFastSDK\Tests\PayFastSDK\Exception;

use FergusDixon\PayFastSDK\Exception\ConfigException;
use FergusDixon\PayFastSDK\Exception\ExceptionMessages;
use PHPUnit\Framework\TestCase;

class ConfigExceptionTest extends TestCase
{
    /**
     * Extra string in Config Exception message
     */
    const EXTRA = 'extra';

    /**
     * Tests if exception is valid: class, code, message
     * @dataProvider dataProvider
     * @param $code
     * @param $message
     * @throws ConfigException
     */
    public function testConstructor($code, $message)
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessageRegExp(
            sprintf("/%s$/", $message)
        );

        throw new ConfigException($code);
    }

    /**
     * Tests if exception is valid with extra argument: class, code, message
     * @dataProvider dataProvider
     * @param $code
     * @param $message
     * @throws ConfigException
     */
    public function testConstructorWithExtra($code, $message)
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessageRegExp(
            sprintf(
                "/%s %s$/",
                static::EXTRA,
                $message
            )
        );

        throw new ConfigException($code, static::EXTRA);
    }

    /**
     * Provides data for tests
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                ConfigException::MISSING_MERCHANT_ID,
                ExceptionMessages::$configErrorMessages[ ConfigException::MISSING_MERCHANT_ID ]
            ],
            [
                ConfigException::UNDEFINED_CONFIG_EXCEPTION,
                ExceptionMessages::$configErrorMessages[ ConfigException::UNDEFINED_CONFIG_EXCEPTION ]
            ],
        ];
    }
}
