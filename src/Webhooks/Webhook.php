<?php

namespace Nylas\Webhooks;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;

class Webhook
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
     * Get a list of all webhook destinations for an application id.
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/webhooks
     *
     * @return array
     * @throws GuzzleException
     */
    public function list(): array
    {
        return $this->options
            ->getSync()
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['webhooks']);
    }

    /**
     * Get a list of all webhook destinations for an application id.
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/webhooks
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(array $params): array
    {
        V::doValidate(Validation::createWebhookRules(), $params);

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['webhooks']);
    }

    /**
     * Get the destinations for an application by webhook ID
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/webhooks/-id-
     *
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    public function find(string $id): array
    {
        V::doValidate(V::key('id', V::stringType()::notEmpty()), ['id' => $id]);

        return $this->options
            ->getSync()
            ->setPath($id)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnWebhook']);
    }

    /**
     * Update a webhook destination
     * @see https://developer.nylas.com/docs/api/v3/admin/#put-/v3/webhooks/-id-
     *
     * @param string $id
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $id, array $params): array
    {
        V::doValidate(
            V::key('id', V::stringType()::notEmpty()),
            [
                'id' => $id
            ]
        );

        V::doValidate(
            Validation::updateWebhookRules(),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($id)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnWebhook']);
    }

    /**
     * Delete a webhook destination
     * @see https://developer.nylas.com/docs/api/v3/admin/#delete-/v3/webhooks/-id-
     *
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $id): array
    {
        V::doValidate(
            V::key('id', V::stringType()::notEmpty()),
            [
                'id' => $id
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($id)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnWebhook']);
    }

    /**
     * Rotate a webhook secret
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/webhooks/rotate-secret/-id-
     *
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    public function rotateWebhookSecret(string $id): array
    {
        V::doValidate(
            V::key('id', V::stringType()::notEmpty()),
            [
                'id' => $id
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($id)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['rotateSecretWebhook']);
    }

    /**
     * Get mock notification payload
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/webhooks/mock-payload
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function getMockNotificationPayload(array $params): array
    {
        V::doValidate(
            Validation::mockNotificationPlayLoadRules(),
            $params
        );

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['mockPlayLoadWebhook']);
    }
}
