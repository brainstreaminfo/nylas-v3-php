<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Webhook Test
 */
class WebhooksTest extends AbsCase
{
    public function testListWebhook()
    {
        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->list();
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testCreateWebhook()
    {
        $this->mockResponse([
            "data" => [
                "id" => $this->faker->uuid,
                "description" => "Production webhook destination",
                "trigger_types" => [
                    "calendar.created"
                ],
                "webhook_url" => "https://example.com/webhooks",
                "webhook_secret" => $this->faker->uuid,
                "status" => "active",
                "notification_email_addresses" => [
                    "test@example.com",
                    "test1@example.com"
                ]
            ],
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->create([
                "description" => "webhook destination test",
                "trigger_types" => [
                    "calendar.created"
                ],
                "webhook_url" => "https://example.com/webhooks",
                "notification_email_addresses" => [
                    $this->faker->email,
                    "test1@example.com"
                ],
            ]);
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testFindWebhook()
    {
        $this->mockResponse([
            "data" => [
                "id" => $this->faker->uuid,
                "description" => "Production webhook destination",
                "trigger_types" => [
                    "calendar.created"
                ],
                "webhook_url" => "https://example.com/webhooks",
                "webhook_secret" => $this->faker->uuid,
                "status" => "active",
                "notification_email_addresses" => [
                    $this->faker->email
                ]
            ],
            "request_id" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->find($this->faker->uuid);
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testUpdateWebhook()
    {
        $this->mockResponse([
            "data" => [
                "id" => $this->faker->uuid,
                "description" => "Production webhook destination",
                "trigger_types" => [
                    "calendar.created"
                ],
                "webhook_url" => "https://example.com/webhooks",
                "webhook_secret" => $this->faker->uuid,
                "status" => "active",
                "notification_email_addresses" => [
                    $this->faker->email
                ]
            ],
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->update(
                $this->faker->uuid,
                [
                    "description" => "webhook destination test",
                    "trigger_types" => [
                        "calendar.created"
                    ],
                    "webhook_url" => "https://example.com/webhooks",
                    "notification_email_addresses" => [
                        $this->faker->email,
                    ],
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testDeleteWebhook()
    {
        $this->mockResponse([
            "data" => [
                "status" => "success"
            ],
            "request_id" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->delete(
                $this->faker->uuid
            );
        } catch (GuzzleException $e) {}

        $this->assertEquals('success', $response['data']['status']);
    }

    public function testRotateSecretWebhook()
    {
        $this->mockResponse([
            "data" => [
                "id" => $this->faker->uuid,
                "description" => "Production webhook destination",
                "trigger_types" => [
                    "calendar.created"
                ],
                "webhook_url" => "https://example.com/webhooks",
                "webhook_secret" => $this->faker->uuid,
                "status" => "active",
                "notification_email_addresses" => [
                    $this->faker->email
                ]
            ],
            "request_id" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->rotateWebhookSecret(
                $this->faker->uuid
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testMockNotificationPayLoad()
    {
        $this->mockResponse([
            "data" => [
                "data" => [
                ]
            ],
            "request_id" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Webhooks->Webhook->getMockNotificationPayload([
                "trigger_type" => "calendar.created",
                "webhook_url" => $this->faker->url,
            ]);
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }
}
