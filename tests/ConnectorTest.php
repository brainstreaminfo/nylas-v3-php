<?php

declare(strict_types = 1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use PHPUnit\Framework\Attributes\Depends;

/**
 * Connector Test
 */
class ConnectorTest extends AbsCase
{
    /**
     * @return void
     */
    public function testListConnectors()
    {
        $this->mockResponse([
            'data' => [
                [
                    'id' => $this->faker->uuid,
                ]
            ]
        ]);

        $response = [];
        try{
            $response = $this->client->Administration->Connectors->list();
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateConnector()
    {
        $this->mockResponse(['data' => [
            'provider' => $this->faker->randomElement
        ]]);

        $response = [];
        try {
            $response = $this->client->Administration->Connectors->create(
                $this->prepareCreateConnector(API::$authProvider_virtual_calendar)
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    #[Depends('testListConnectors')]
    public function testGetConnector()
    {
        $response = [];
        try{
            $response = $this->client->Administration->Connectors->find(
                API::$authProvider_google
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDetectProviderByEmail()
    {
        $response = [];

        $this->mockResponse([
            'data' => [
                'id' => 'test-123',
            ],
        ]);

        $params = [
            'email' => 'test@gmail.com',
        ];

        try {
            $response = $this->client->Administration->Connectors->detectProviderByEmail($params);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testUpdateConnector()
    {
        $this->mockResponse([
            'data' => [
                'id' => 'test-123'
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Connectors->update(
                API::$authProvider_google,
                [
    //                'settings' => [
    //                    'tenant' => 'common',
    //                ],
                    'scope' => [
                        'openid'
                    ]
                ]
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteProvider()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid()
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Connectors->delete(
                API::$authProvider_virtual_calendar
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['request_id']);
    }

    private function prepareCreateConnector($provider): array
    {
        return match ($provider) {
            'google' => [
                'provider' => API::$authProvider_google,
                'settings' => [
                    'client_id' => $this->faker->uuid(),
                    'client_secret' => $this->faker->uuid(),
                ],
                'scope' => [
                    'openid',
                ],
            ],
            'microsoft' => [
                'provider' => API::$authProvider_microsoft,
                'settings' => [
                    'client_id' => $this->faker->uuid(),
                    'client_secret' => $this->faker->uuid(),
                ],
            ],
            'yahoo' => [
                'provider' => API::$authProvider_yahoo,
                'settings' => [
                    'client_id' => $this->faker->uuid(),
                    'client_secret' => $this->faker->uuid(),
                ],
            ],
            'zoom' => [
                'provider' => API::$authProvider_zoom,
                'settings' => [
                    'client_id' => $this->faker->uuid(),
                    'client_secret' => $this->faker->uuid(),
                ],
            ],
            default => ['provider' => API::$authProvider_virtual_calendar],
        };
    }
}
