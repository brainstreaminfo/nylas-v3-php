<?php

declare(strict_types = 1);

namespace Nylas\Request;

use function array_merge;

use Exception;
use Nylas\Utilities\Validator as V;
use Nylas\Exceptions\NylasException;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas RESTFul Request Tool
 */
class Sync
{
    // base trait
    use AbsBase;

    /**
     * get request sync
     *
     * @param string $api
     * @return mixed
     * @throws GuzzleException
     */
    public function get(string $api): mixed
    {
        $apiPath = $this->concatApiPath($api);
        $options = $this->concatOptions();

        try {
            $response = $this->guzzle->get($apiPath, $options);
        } catch (Exception $e) {
            throw new NylasException($e);
        }

        return $this->parseResponse($response, false);
    }

    /**
     * put request sync
     *
     * @param string $api
     * @return mixed
     * @throws GuzzleException
     */
    public function put(string $api): mixed
    {
        $apiPath = $this->concatApiPath($api);
        $options = $this->concatOptions();

        try {
            $response = $this->guzzle->put($apiPath, $options);
        } catch (Exception $e) {
            throw new NylasException($e);
        }

        return $this->parseResponse($response, false);
    }

    /**
     * post request sync
     *
     * @param string $api
     * @return mixed
     * @throws GuzzleException
     */
    public function post(string $api): mixed
    {
        $apiPath = $this->concatApiPath($api);
        $options = $this->concatOptions();

        try {
            $response = $this->guzzle->post($apiPath, $options);
        } catch (Exception $e) {
            throw new NylasException($e);
        }

        return $this->parseResponse($response, false);
    }

    /**
     * patch request sync
     *
     * @param string $api
     * @return mixed
     * @throws GuzzleException
     */
    public function patch(string $api): mixed
    {
        $apiPath = $this->concatApiPath($api);
        $options = $this->concatOptions();

        try {
            $response = $this->guzzle->patch($apiPath, $options);
        } catch (Exception $e) {
            throw new NylasException($e);
        }

        return $this->parseResponse($response, false);
    }

    /**
     * delete request sync
     *
     * @param string $api
     * @return mixed
     * @throws GuzzleException
     */
    public function delete(string $api): mixed
    {
        $apiPath = $this->concatApiPath($api);
        $options = $this->concatOptions();

        try {
            $response = $this->guzzle->delete($apiPath, $options);
        } catch (Exception $e) {
            throw new NylasException($e);
        }

        return $this->parseResponse($response, false);
    }
}
