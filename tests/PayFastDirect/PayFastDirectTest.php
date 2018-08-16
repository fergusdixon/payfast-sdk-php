<?php
namespace FergusDixon\PayFastSDK\Tests\PayFastSDK;

use FergusDixon\PayFastSDK\Config\Config;
use FergusDixon\PayFastSDK\PayFastSDK;
use PHPUnit\Framework\TestCase;

class PayFastSDKTest extends TestCase
{
    /**
     * Dummy key for testing
     */
    const TEST_MERCHANT_ID = 'test';
    const TEST_PASSPHRASE = 'test';

    /**
     * Testing object instance
     *
     * @var PayFastSDK
     */
    private $client;

    /**
     * Checks if constructor works fine
     * @param $config
     * @param $expected
     */
    public function testConstructor()
    {
        $this->client = new PayFastSDK([
            'merchantId' => self::TEST_MERCHANT_ID,
            'passPhrase' => self::TEST_PASSPHRASE
        ]);

        $this->assertInstanceOf(PayFastSDK::class, $this->client);
        $this->assertInstanceOf(Config::class, $this->client->config);
    }
}
