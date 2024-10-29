<?php

declare(strict_types = 1);

namespace Nylas\Administration;

use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Connectors Credentials Manage
 */
class ConnectorsCredentials
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
     * List credentials for the specified provider.
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connectors/-provider-/creds
     *
     * @param string $provider
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function list(string $provider, array $params = []): array
    {
        V::doValidate(
            V::key('provider', V::stringType()::notEmpty()),
            [
                'provider' => $provider
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('limit', V::intType()),
                V::keyOptional('offset', V::intType()),
                V::keyOptional('sort_by', V::in(['created_at', 'updated_at'])),
                V::keyOptional('order_by', V::in(['desc', 'asc'])),
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($provider)
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['listCredentials']);
    }

    /**
     * Manually create a credential record.
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connectors/-provider-/creds
     *
     * @param string $provider
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $provider, array $params = []): array
    {
        V::doValidate(
            V::key('provider', V::stringType()::notEmpty()),
            [
                'provider' => $provider
            ]
        );

        V::doValidate(Validation::createCredentialRequestRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($provider)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['listCredentials']);
    }

    /**
     * Update a specific connector credential.
     * @see https://developer.nylas.com/docs/api/v3/admin/#patch-/v3/connectors/-provider-/creds/-id-
     *
     * @param string $provider
     * @param string $credentialId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $provider, string $credentialId, array $params = []): array
    {
        V::doValidate(
            V::keySet(
                V::key('provider', V::stringType()::notEmpty()),
                V::key('credentialId', V::stringType()::notEmpty()),
            ),
            [
                'provider'      => $provider,
                'credentialId'  => $credentialId
            ]
        );

        V::doValidate(Validation::updateCredentialRequestRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($provider, $credentialId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->patch(API::LIST['crudOnCredential']);
    }

    /**
     * Get credential
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connectors/-provider-/creds/-id-
     *
     * @param string $provider
     * @param string $credentialId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $provider, string $credentialId): array
    {
        V::doValidate(
            V::keySet(
                V::key('provider', V::stringType()::notEmpty()),
                V::key('credentialId', V::stringType()::notEmpty()),
            ),
            [
                'provider'      => $provider,
                'credentialId'  => $credentialId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($provider, $credentialId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnCredential']);
    }

    /**
     * Delete a credential with the specified ID.
     * @see https://developer.nylas.com/docs/api/v3/admin/#delete-/v3/connectors/-provider-/creds/-id-
     *
     * @param string $provider
     * @param string $credentialId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $provider, string $credentialId): array
    {
        V::doValidate(
            V::keySet(
                V::key('provider', V::stringType()::notEmpty()),
                V::key('credentialId', V::stringType()::notEmpty()),
            ),
            [
                'provider'      => $provider,
                'credentialId'  => $credentialId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($provider, $credentialId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnCredential']);
    }
}
