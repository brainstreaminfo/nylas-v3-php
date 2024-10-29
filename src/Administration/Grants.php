<?php

declare(strict_types=1);

namespace Nylas\Administration;

use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Grants Management
 * @see https://developer.nylas.com/docs/api/v3/admin/#tag--Manage-Grants
 */
class Grants
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Lists grants for an application
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/grants
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function list(array $params = []): array
    {
        V::doValidate(Validation::grantSearchRules(), $params);

        return $this->options
            ->getSync()
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['listGrants']);
    }

    /**
     * Gets a grant with the provided ID
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/grants/-grantId-
     *
     * @param string $grantId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnGrant']);
    }

    /**
     * Delete a Grant
     * @see https://developer.nylas.com/docs/api/v3/admin/#delete-/v3/grants/-grantId-
     *
     * @param string $grantId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnGrant']);
    }

    /**
     * Update a grant
     * @see https://developer.nylas.com/docs/api/v3/admin/#patch-/v3/grants/-grantId-
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, array $params): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(
            V::keySet(
                V::keyOptional('settings', V::arrayType()),
                V::keyOptional('scope', V::arrayType())
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->patch(API::LIST['crudOnGrant']);
    }

    /**
     * Gets a grant using current access token
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/grants/me
     *
     * @param string $accessToken
     * @return array
     * @throws GuzzleException
     */
    public function getCurrentGrant(string $accessToken): array
    {
        V::doValidate(V::stringType()::notEmpty(), ['accessToken' => $accessToken]);

        return $this->options
            ->getSync()
            ->setHeaderParams($this->options->getAuthorizationHeader($accessToken))
            ->get(API::LIST['getCurrentGrant']);
    }
}
