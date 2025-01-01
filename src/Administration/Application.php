<?php

declare(strict_types=1);

namespace Nylas\Administration;

use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Application Manage
 */
class Application
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }
    /**
     * Collect all Applications
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/applications
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function list(): ?array
    {
        return $this->options
            ->getSync()
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['listApps']);
    }

    /**
     * Update Application
     * @see https://developer.nylas.com/docs/api/v3/admin/#patch-/v3/applications
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(array $params): array
    {
        V::doValidate(
            Validation::UpdateApplicationRules(),
            $params
        );

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->patch(API::LIST['listApps']);
    }

    /**
     * Get Application Redirect URI List
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/applications/redirect-uris
     *
     * @return array
     * @throws GuzzleException
     */
    public function listCallbackUrls(): array
    {
        return $this->options
            ->getSync()
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['appRedirectUris']);
    }

    /**
     * Delete Application Redirect URI
     * @see https://developer.nylas.com/docs/api/v3/admin/#delete-/v3/applications/redirect-uris/-id-
     *
     * @param string $callbackId
     * @return array
     * @throws GuzzleException
     */
    public function deleteCallbackUrl(string $callbackId): array
    {
        V::doValidate(
            V::key('callbackId', V::stringType()::notEmpty()),
            [
                'callbackId' => $callbackId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($callbackId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnAppRedirectUri']);
    }

    /**
     * Get Detail of Application Redirect URI
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/applications/redirect-uris/-id-
     *
     * @param string $callbackId
     * @return array
     * @throws GuzzleException
     */
    public function getCallbackUrlDetails(string $callbackId): array
    {
        V::doValidate(
            V::key('callbackId', V::stringType()::notEmpty()),
            [
                'callbackId' => $callbackId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($callbackId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnAppRedirectUri']);
    }

    /**
     * Add application redirect url
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/applications/redirect-uris
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function addCallbackUrl(array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('url', V::url()::notEmpty()),
                V::key('platform', V::in(API::$allowPlatforms)),
                V::keyOptional('settings', Validation::callBackUrlSettingRules())
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['appRedirectUris']);
    }
}
