<?php

declare(strict_types = 1);

namespace Nylas\Administration;

use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Connectors
 */
class Connectors
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
     * List All Connectors
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connectors
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function list(array $params = []): array
    {
        V::doValidate(
            V::keySet(
                V::keyOptional('limit', V::intType()::min(1)),
                V::keyOptional('offset', V::intType()::min(0)),
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['listConnectors']);
    }

    /**
     * Get Connector Details
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connectors/-provider-
     *
     * @param string $provider
     * @return array
     * @throws GuzzleException
     */
    public function find(string $provider): array
    {
        V::doValidate(
            V::in(API::$authProviders)->setTemplate('Provider is invalid'),
            $provider
        );

        return $this->options
            ->getSync()
            ->setPath($provider)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnConnector']);
    }

    /**
     * Returns the provider if one is detected.
     * This operation is rate limited to 20 calls per minute for each Nylas application ID.
     *
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/providers/detect
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function detectProviderByEmail(array $params): array
    {
        $params['all_provider_types'] = $params['all_provider_types'] ?? false;

        V::doValidate(
            V::keySet(
                V::key('email', V::email()),
                V::key('all_provider_types', V::boolType())
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['detectConnectors']);
    }

    /**
     * Update the connector for the specified provider.
     * @see https://developer.nylas.com/docs/api/v3/admin/#patch-/v3/connectors/-provider-
     *
     * @param string $provider
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $provider, array $params): array
    {
        V::doValidate(
            V::in(API::$authProviders)->setTemplate('Invalid provider'),
            $provider
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('settings', V::arrayType()),
                V::keyOptional('scope', V::arrayType())
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($provider)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->patch(API::LIST['crudOnConnector']);
    }

     /**
     * Delete the existing connector for the provider you specify
     * @see https://developer.nylas.com/docs/api/v3/admin/#delete-/v3/connectors/-provider-
     *
     * @param string $provider
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $provider): array
    {
        V::doValidate(V::in(API::$authProviders), $provider);

        return $this->options
            ->getSync()
            ->setPath($provider)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnConnector']);
    }

    /**
     * Create Provider
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connectors
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(array $params): array
    {
        V::doValidate(
            V::oneOf(
                Validation::googleCreateConnectorRequestRules(),
                Validation::yahooCreateConnectorRequestRules(),
                Validation::microsoftCreateConnectorRequestRules(),
                Validation::zoomCreateConnectorRequestRules(),
                Validation::generalCreateConnectorRequestRules(API::$authProvider_imap),
                Validation::generalCreateConnectorRequestRules(API::$authProvider_ews),
                Validation::generalCreateConnectorRequestRules(API::$authProvider_virtual_calendar),
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['listConnectors']);
    }
}
