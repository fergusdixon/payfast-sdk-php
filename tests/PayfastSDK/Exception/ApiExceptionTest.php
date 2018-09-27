<?php

namespace FergusDixon\PayFastSDK\Tests\PayFastSDK\Exception;

use FergusDixon\PayFastSDK\Exception\ApiException;
use PHPUnit\Framework\TestCase;

class ApiExceptionTest extends TestCase
{
    /**
     * Test message or title
     */
    const TEST_MESSAGE = 'Test error';

    /**
     * Test status for Exception
     */
    const TEST_STATUS = 'Error';

    /**
     * Test details for Exception
     */
    const TEST_DETAIL = 'Something went wrong';

    /**
     * Test additional information for Exception
     */
    const TEST_ADDITIONAL = 'But that`s not certain';

    /**
     * Tests if exception is valid: class, code, message
     * @dataProvider dataProvider
     * @param $code
     * @param $message
     * @param $expected
     * @throws ApiException
     */
    public function testConstructor($code, $message, $expected)
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($expected);

        throw new ApiException($message, $code);
    }

    /**
     * Provides data for tests: an array of arrays with structure [ code, message, expected message ]
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                1,
                static::TEST_MESSAGE,
                static::TEST_MESSAGE,
            ],
        ];
    }
}
