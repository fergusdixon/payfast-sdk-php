<?php
namespace FergusDixon\PayFastSDK\Tests\PayFastSDK\Config;

use FergusDixon\PayFastSDK\Config\Config;
use FergusDixon\PayFastSDK\Exception\ConfigException;
use FergusDixon\PayFastSDK\Exception\ExceptionMessages;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ConfigTest extends TestCase
{
    /**
     * Test key value
     */
    const TEST_MERCHANT_ID = '10000000';

    /**
     * Test passphrase
     */
    const TEST_PASSPHRASE = 'xxxxxxxx';

    /**
     * Test dummy endpoint value
     */
    const TEST_ENDPOINT = 'http://localhost:8082';

    /**
     * @var Config
     */
    private $configMock;

    /**
     * Creates mock for an abstract class
     */
    public function setUp()
    {
        $this->configMock = $this
            ->getMockBuilder(\FergusDixon\PayFastSDK\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * Checks that constructor works well and getRequestHandlerConfig method returns right values
     */
    public function testGetRequestHandlerConfig()
    {
        $reflectedClass = new ReflectionClass(Config::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, [
            'merchantId' => static::TEST_MERCHANT_ID,
            'passPhrase' => static::TEST_PASSPHRASE,
            'endpoint' => static::TEST_ENDPOINT
        ]);

        $handlerConfig = $this->configMock->getRequestHandlerConfig();

        $this->assertTrue(is_array($handlerConfig), 'Config is not an array');
        $this->assertArrayHasKey('merchantId', $handlerConfig, 'Config does not contain key `merchantId`');
        $this->assertEquals(static::TEST_MERCHANT_ID, $handlerConfig['merchantId'], 'Key is wrong');
        $this->assertEquals(static::TEST_PASSPHRASE, $handlerConfig['passPhrase'], 'Passphrase is wrong');
        $this->assertArrayHasKey('endpoint', $handlerConfig, 'Config does not contain key `endpoint`');
        $this->assertEquals(static::TEST_ENDPOINT, $handlerConfig['endpoint'], 'Endpoint is wrong');
    }

    /**
     * Checks that constructor init methods throws Exception
     */
    public function testConstructorException()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::MISSING_MERCHANT_ID);
        $this->expectExceptionMessage(ExceptionMessages::$configErrorMessages[ConfigException::MISSING_MERCHANT_ID]);

        $reflectedClass = new ReflectionClass(Config::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, []);
    }

    /**
     * Checks that constructor init methods throws Exception
     */
    public function testConstructorPassphraseException()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::MISSING_PASSPHRASE);
        $this->expectExceptionMessage(ExceptionMessages::$configErrorMessages[ConfigException::MISSING_PASSPHRASE]);

        $reflectedClass = new ReflectionClass(Config::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($this->configMock, ['merchantId' => 'xxx']);
    }
}
