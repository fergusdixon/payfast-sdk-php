<?php
namespace FergusDixon\PayFastSDK\Tests\PayFastSDK\Request;

use FergusDixon\PayFastSDK\Request\RequestHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RequestHandlerTest extends TestCase
{
    /**
     * A dummy uri for testing purposes
     */
    const TEST_URI = '/test-uri';

    /**
     * A config for a RequestHandler
     * @var array
     */
    private $config = [
        'merchantId' => 'testId',
        'passPhrase' => 'testPhrase',
        'endpoint' => '//localhost:8082',
        'port' => 80,
        'ssl' => false,
        'testing' => true,
    ];

    /**
     * @var RequestHandler
     */
    private $handler;

    /**
     * Sets up the handler object for tests
     */
    public function setUp()
    {
        $this->handler = new RequestHandler($this->config);
    }

    /**
     * Checks if current handler object is an instance of the right class
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            $this->handler,
            sprintf('Handler must be an instance of %s', RequestHandler::class)
        );
    }

    /**
     * Checks signature for HMAC-SHA1 signing
     */
    public function testSignature()
    {
        $this->handler = new RequestHandler($this->config);
        $sign = $this->handler->generateSignature([
            'key' => 'value'
        ]);
        $this->assertEquals('ef176a6c424f954fa42d4cde03949897', $sign);
        $sign = $this->handler->generateSignature([
            'key' => [
                'value1', 'value2'
            ]
        ]);
        $this->assertEquals('8c5344671eea9e7be0c05e3c49bd1730', $sign);
        $sign = $this->handler->generateSignature([
            'key' => 'some+value_with(many){special} symbols<>!*\''
        ]);
        $this->assertEquals('434b4e67afa3a0f543516c6319af7e9e', $sign);
    }

    /**
     * Tests if current handler reacts right on different HTTP method requests
     *
     * @dataProvider dataProvider
     * @param $options
     * @param $result
     * @throws \FergusDixon\PayFastSDK\Exception\ApiException
     * @throws \FergusDixon\PayFastSDK\Exception\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
    public function testPostRequest($options, $result)
    {
        // TODO make mocking more intensive for a better test
        $this->setUpMockClient([
            new Response(
                200,
                [ 'ContentType: application/json' ],
                $result
            ),
        ]);

        $response = $this->handler->custom('POST', '/adhoc', $options);
        $this->assertEquals(
            $result,
            $response
        );
    }

    /**
     * Tests if current handler reacts right on different HTTP method requests
     *
     * @dataProvider dataProvider
     * @param $options
     * @param $result
     * @throws \FergusDixon\PayFastSDK\Exception\ApiException
     * @throws \FergusDixon\PayFastSDK\Exception\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
    public function testGetRequest($options, $result)
    {
        // TODO make mocking more intensive for a better test
        $this->setUpMockClient([
            new Response(
                200,
                [ 'ContentType: application/json' ],
                $result
            ),
        ]);

        $response = $this->handler->custom('GET', '/history', $options);
        $this->assertEquals(
            $result,
            $response
        );
    }

    /**
     * Provides data for testing
     *
     * TODO more inputs once mocks in place
     *
     * @return array
     */
    public function dataProvider()
    {
        // TODO real mocks
        $testResponse = \GuzzleHttp\json_encode([
            'status' => 'OK',
        ]);

        return [
            [
                [
                    'dummyDate' => '2018-02-02',
                    'dummyString' => 'Qwerty',
                ],
                $testResponse,
            ],
        ];
    }

    /**
     * Utility method for GuzzleHttp\MockHandler setup
     *
     * @param $results
     * @throws \ReflectionException
     */
    private function setUpMockClient($results)
    {
        $mockHandler = new MockHandler($results);

        $handler = HandlerStack::create($mockHandler);
        $mockClient = new Client([
            'handler' => $handler,
        ]);

        $reflection = new ReflectionClass($this->handler);
        $reflectedClient = $reflection->getProperty('client');
        $reflectedClient->setAccessible(true);
        $reflectedClient->setValue($this->handler, $mockClient);
    }
}
