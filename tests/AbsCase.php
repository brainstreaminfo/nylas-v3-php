<?php

declare(strict_types=1);

namespace Tests;

use function array_merge;
use function json_encode;

use Mockery;
use Nylas\Client;
use Faker\Factory;
//use JsonException;
use Faker\Generator;
use ReflectionMethod;
use ReflectionException;
use Mockery\MockInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Mockery\LegacyMockInterface;
use GuzzleHttp\Handler\MockHandler;

/**
 * Account Testcases
 * @see https://developer.nylas.com/docs/api/v3/admin/?redirect=api#overview
 *
 * @internal
 */
class AbsCase extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * init client instance
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $options = [
            'debug'     => true,
            'log_file'  => __DIR__ . '/test.log',
            'api_key'   => 'NYLAS_API_KEY',
            'region'    => 'NYLAS_REGION',
            'client_id' => 'NYLAS_CLIENT_ID',
            'grant_id'  => 'NYLAS_GRANT_ID',
        ];

        $this->client = new Client($options);
    }

    /**
     * reset client
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->client);

        Mockery::close();
    }

    /**
     * assert passed
     */
    protected function assertPassed(): void
    {
        static::assertTrue(true);
    }

    /**
     * spy with mockery
     *
     * @param mixed ...$args
     *
     * @return LegacyMockInterface|MockInterface
     */
    protected function spy(...$args)
    {
        return Mockery::spy(...$args);
    }

    /**
     * mock with mockery
     *
     * @param mixed ...$args
     *
     * @return LegacyMockInterface|MockInterface
     */
    protected function mock(...$args)
    {
        return Mockery::mock(...$args);
    }

    /**
     * overload with mockery
     *
     * @param string $class
     *
     * @return LegacyMockInterface|MockInterface
     */
    protected function overload(string $class)
    {
        return Mockery::mock('overload:' . $class);
    }

    /**
     * call private or protected method
     *
     * @param object $object
     * @param string $method
     * @param mixed  ...$params
     *
     * @return mixed
     * @throws ReflectionException
     * @throws ReflectionException
     */
    protected function call(object $object, string $method, ...$params)
    {
        $method = new ReflectionMethod($object, $method);
        $method->setAccessible(true);

        return $method->invoke($object, ...$params);
    }

    /**
     * mock any class
     *
     * @param string $name
     * @param array  $mock
     *
     * @return MockInterface
     */
    protected function mockClass(string $name, array $mock): MockInterface
    {
        $mod = $this->overload($name)->makePartial();

        foreach ($mock as $method => $return) {
            $mod->shouldReceive($method)->andReturn($return);
        }

        return $mod;
    }

    /**
     * mock api response data
     *
     * @param array $data
     * @param array $header
     * @param int   $code
     *
     */
    protected function mockResponse(array $data, array $header = [], int $code = 200): void
    {
        $body = json_encode($data);
        if ($body === false) {
            throw new \Exception('JSON encoding error: ' . json_last_error_msg());
        }

        $header = array_merge($header, ['Content-Type' => 'application/json']);

        $mock = new MockHandler([new Response($code, $header, $body)]);

        $this->client->Options->setHandler($mock);
    }
}
