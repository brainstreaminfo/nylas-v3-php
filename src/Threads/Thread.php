<?php
declare(strict_types=1);

namespace Nylas\Threads;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;

class Thread
{
    /**
     * @param Options $options
     */
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Return all threads
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/threads
     *
     * @param string $grantId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $queryParams = []): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(
            Validation::getBaseRules(),
            $queryParams
        );

        // prepare comma separated emails
        if (!empty($queryParams['any_email'])) {
            $queryParams['any_email'] = implode(',', $queryParams['any_email']);
        }

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['threads']);
    }

    /**
     * Return a thread
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/threads/-thread_id-
     *
     * @param string $grantId
     * @param string $threadId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $threadId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('threadId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'threadId'  => $threadId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $threadId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnThread']);
    }

    /**
     * Update a thread
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/threads/-thread_id-
     *
     * @param string $grantId
     * @param string $threadId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $threadId, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('threadId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'threadId'  => $threadId,
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('starred', V::boolType()),
                V::keyOptional('unread', V::boolType()),
                V::keyOptional('folders', V::arrayType()::each(V::stringType())),
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $threadId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnThread']);
    }

    /**
     * Delete a thread
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/threads/-thread_id-
     *
     * @param string $grantId
     * @param string $threadId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $threadId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('threadId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'threadId'  => $threadId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $threadId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnThread']);
    }
}
