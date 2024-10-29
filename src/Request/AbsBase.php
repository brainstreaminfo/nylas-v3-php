<?php

declare(strict_types = 1);

namespace Nylas\Request;

use function trim;
use function current;
use function sprintf;
use function strtolower;
use function array_merge;
use function is_callable;
use function json_decode;
use function str_contains;
use function mb_convert_encoding;

use Throwable;
use GuzzleHttp\Client;
use Nylas\Utilities\API;
use Nylas\Utilities\Errors;
use Nylas\Utilities\Helper;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Nylas RESTFul Request Base
 */
trait AbsBase
{
    /**
     * enable or disable debug mode
     *
     * @var bool|resource
     */
    private $debug = false;

    /**
     * @var Client
     */
    private Client $guzzle;

    private array $formFiles     = [];
    private array $pathParams    = [];
    private array $jsonParams    = [];
    private array $queryParams   = [];
    private array $headerParams  = [];
    private array $bodyContents  = [];
    private array $onHeadersFunc = [];

    /**
     * Request constructor.
     *
     * @param null|string   $server
     * @param null|callable $handler
     * @param bool|resource $debug
     */
    public function __construct(?string $server = null, mixed $handler = null, mixed $debug = false)
    {
        $option = [
            'verify'   => true,
            'base_uri' => trim($server ?? API::SERVER['us']),
        ];

        if (is_callable($handler)) {
            $option['handler'] = HandlerStack::create($handler);
        }

        $this->debug  = $debug;
        $this->guzzle = new Client($option);
    }

    /**
     * set path params
     *
     * @param string ...$path
     * @return Sync|AbsBase|Async
     */
    public function setPath(string ...$path): self
    {
        $this->pathParams = $path;

        return $this;
    }

    /**
     * set body value
     *
     * @param mixed $body
     * @return Sync|AbsBase|Async
     */
    public function setBody(mixed $body): self
    {
        $this->bodyContents = ['body' => $body];

        return $this;
    }

    /**
     * set query params
     *
     * @param array $query
     * @return Sync|AbsBase|Async
     */
    public function setQuery(array $query): self
    {
        $query = Helper::boolToString($query);

        if (!empty($query)){
            $this->queryParams = [
                'query' => $query
            ];
        }

        return $this;
    }

    /**
     * set form params
     *
     * @param array $params
     * @return Sync|AbsBase|Async
     */
    public function setFormParams(array $params): self
    {
        $this->jsonParams = ['json' => $params];

        return $this;
    }

    /**
     * set form files
     *
     * @param array $files
     * @return Sync|AbsBase|Async
     */
    public function setFormFiles(array $files): self
    {
        $this->formFiles = ['multipart' => Helper::arrayToMulti($files)];

        return $this;
    }

    /**
     * set header params
     * @see https://developer.nylas.com/docs/the-basics/authentication/authorizing-api-requests/
     *
     * @param array $headers
     * @param bool $hasMultipart
     * @return Sync|AbsBase|Async
     */
    public function setHeaderParams(array $headers, bool $hasMultipart = false): self
    {
        $headers['User-Agent']      = 'Nylas PHP SDK';
        $headers['Accept']          = 'application/json';

        if (!$hasMultipart) {
            $headers['Content-Type'] = 'application/json';
        }

        $this->headerParams = ['headers' => $headers];

        return $this;
    }

    /**
     * @param callable $func
     */
    public function setHeaderFunctions(callable $func): void
    {
        $this->onHeadersFunc[] = $func;
    }

    /**
     * concat api path for request
     *
     * @param string $api
     * @return string
     */
    private function concatApiPath(string $api): string
    {
        return sprintf($api, ...$this->pathParams);
    }

    /**
     * concat response data when invalid json data responded
     *
     * @param array $type
     * @param string $code
     * @param string $data
     * @return array
     */
    private function concatForInvalidJsonData(array $type, string $code, string $data): array
    {
        return [
            'httpStatus'  => $code,
            'invalidJson' => true,
            'contentType' => current($type),
            'contentBody' => $data,
        ];
    }

    /**
     * concat options for request
     *
     * @param bool $httpErrors
     * @return array
     */
    private function concatOptions(bool $httpErrors = false): array
    {
        $temp = [
            'debug'       => $this->debug,
            'on_headers'  => $this->onHeadersFunctions(),
            'http_errors' => $httpErrors,
        ];

        // When header has multipart data
        if (!empty($this->headerParams['headers']['multipart'])) {
            $temp['multipart'] = $this->headerParams['headers']['multipart'];

            unset($this->headerParams['headers']['multipart']);
        }

        return array_merge(
            $temp,
            empty($this->formFiles) ? [] : $this->formFiles,
            empty($this->jsonParams) ? [] : $this->jsonParams,
            empty($this->queryParams) ? [] : $this->queryParams,
            empty($this->headerParams) ? [] : $this->headerParams,
            empty($this->bodyContents) ? [] : $this->bodyContents
        );
    }

    /**
     * check http status code before response body
     *
     * @return callable
     */
    private function onHeadersFunctions(): callable
    {
        $request = $this;
        $excpArr = Errors::StatusExceptions;

        return static function (ResponseInterface $response) use ($request, $excpArr): void
        {
            $statusCode = $response->getStatusCode();

            // check status code
            if ($statusCode >= 400)
            {
                // normal exception
                if (isset($excpArr[$statusCode]))
                {
                    throw new $excpArr[$statusCode]();
                }

                // unexpected exception
                throw new $excpArr['default']();
            }

            // execute others on header functions
            foreach ($request->onHeadersFunc as $func)
            {
                $func($response);
            }
        };
    }

    /**
     * Parse the JSON response body and return an array
     *
     * @param ResponseInterface $response
     * @param bool $headers
     * @return mixed
     */
    private function parseResponse(ResponseInterface $response, bool $headers = false): mixed
    {
        if ($headers) {
            return $response->getHeaders();
        }

        $expc = 'application/json';
        $code = $response->getStatusCode();
        $type = $response->getHeader('Content-Type');
        $data = $response->getBody()->getContents();

        // when not json type
        if (!str_contains(strtolower(current($type)), $expc)) {
            return $this->concatForInvalidJsonData($type, (string) $code, $data);
        }

        try {
            // decode json data
            $temp = trim(mb_convert_encoding($data, 'UTF-8'));
            $temp = json_decode($temp, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return $this->concatForInvalidJsonData($type, (string) $code, $data);
        }

        return $temp ?? [];
    }
}
