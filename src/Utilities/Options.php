<?php

declare(strict_types = 1);

namespace Nylas\Utilities;

use function fopen;
use function is_string;
use function is_resource;

use Nylas\Request\Sync;
use Nylas\Request\Async;
use Nylas\Utilities\Validator as V;

/**
 * Nylas Utils Options
 */
class Options
{
    /**
     * @var mixed
     */
    private mixed $logFile;

    /**
     * @var null|callable
     */
    private mixed $handler;

    /**
     * @var bool
     */
    private bool $debug = false;

    /**
     * @var string
     */
    private string $server;

    /**
     * @var string
     */
    private string $region;

    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $grantId;

    /**
     * Options constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        V::doValidate(V::keySet(
            V::key('api_key', V::stringType()::notEmpty()),
            V::keyOptional('debug', V::boolType()),
            V::keyOptional('region', V::in(['us', 'eu'])),
            V::keyOptional('log_file', $this->getLogFileRule()),
            V::keyOptional('client_id', V::stringType()),
            V::keyOptional('grant_id', V::stringType()),
        ), $options);

        $this->region = $options['region'] ?? 'us';

        $this->setApiKey($options['api_key']);

        if (!empty($options['client_id'])) {
            $this->setClientId($options['client_id']);
        }

        $this->setDebug($options['debug'] ?? false);
        $this->setServer($this->region);
        $this->setHandler($options['handler'] ?? null);
        $this->setLogFile($options['log_file'] ?? null);

        if (!empty($options['grant_id'])) {
            $this->grantId = $options['grant_id'];
        }
    }

    /**
     * @return string
     */
    public function getGrantId(): string
    {
        return $this->grantId;
    }

    /**
     * set guzzle client handler
     *
     * @param callable|null $handler
     * @return void
     */
    public function setHandler(?callable $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * get access token
     *
     * @return null|callable
     */
    public function getHandler(): ?callable
    {
        return $this->handler ?? null;
    }

    /**
     * Set server
     *
     * @param string|null $region
     * @return void
     */
    public function setServer(?string $region = null): void
    {
        $region = $region ?? 'us';

        $this->server = API::SERVER[$region] ?? API::SERVER['us'];
    }

    /**
     * get server
     *
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * enable/disable debug
     *
     * @param bool $debug
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * set log file
     *
     * @param mixed $logFile
     * @return void
     */
    public function setLogFile(mixed $logFile): void
    {
        if ($logFile !== null)
        {
            V::doValidate($this->getLogFileRule(), $logFile);
        }

        $this->logFile = $logFile;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * set API Key
     *
     * @param string $apiKey
     * @return void
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * set client id
     *
     * @param string $clientId
     * @return void
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * get API Key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * get client id
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * get authorization header
     *
     * @param string $accessToken
     * @return string[]
     */
    public function getAuthorizationHeader(string $accessToken = ''): array
    {
        $authorization = !empty($accessToken) ? $accessToken : $this->apiKey;

        return [
            'Authorization' => sprintf("Bearer %s", $authorization)
        ];
    }

    /**
     * get all configure options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        return
        [
            'debug'         => $this->debug,
            'log_file'      => $this->logFile,
            'api_key'       => $this->apiKey,
            'client_id'     => $this->clientId,
        ];
    }

    /**
     * get sync request instance
     *
     * @return Sync
     */
    public function getSync(): Sync
    {
        $debug   = $this->getLoggerHandler();
        $server  = $this->getServer();
        $handler = $this->getHandler();

        return new Sync($server, $handler, $debug);
    }

    /**
     * get async request instance
     *
     * @return Async
     */
    public function getAsync(): Async
    {
        $debug   = $this->getLoggerHandler();
        $server  = $this->getServer();
        $handler = $this->getHandler();

        return new Async($server, $handler, $debug);
    }

    /**
     * get log file rules
     *
     * @return Validator
     */
    private function getLogFileRule(): V
    {
        return V::oneOf(
            V::resourceType(),
            V::stringType()::notEmpty()
        );
    }

    /**
     * get logger handler
     *
     * @return mixed
     */
    private function getLoggerHandler(): mixed
    {
        return match (true) {
            is_string($this->logFile)   => fopen($this->logFile, 'ab'),
            is_resource($this->logFile) => $this->logFile,

            default => $this->debug,
        };
    }
}
