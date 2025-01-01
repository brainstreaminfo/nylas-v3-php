<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
//ref:adbrain rem
//use PHPUnit\Framework\Attributes\Depends;


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
        try {
            $response = $this->client->Administration->Connectors->list();
        } catch (GuzzleException $e) {
        }

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
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    //ref:adbrain, issue is here, maybe depends annotation
    //#[Depends('testListConnectors')]
    /**
     * @depends testListConnectors
     */
    /*public function testGetConnector()
    {
        $response = [];
        try {
            $response = $this->client->Administration->Connectors->find(
                API::$authProvider_google
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }*/

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
        } catch (GuzzleException $e) {
        }

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
        } catch (GuzzleException $e) {
        }

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
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['request_id']);
    }

    private function prepareCreateConnector($provider): array
    {
        switch ($provider) {
            case 'google':
                return [
                    'provider' => API::$authProvider_google,
                    'settings' => [
                        'client_id' => $this->faker->uuid(),
                        'client_secret' => $this->faker->uuid(),
                    ],
                    'scope' => [
                        'openid',
                    ]
                ];
                break;
            case 'microsoft':
                return [
                    'provider' => API::$authProvider_microsoft,
                    'settings' => [
                        'client_id' => $this->faker->uuid(),
                        'client_secret' => $this->faker->uuid(),
                    ]
                ];
                break;
            case 'yahoo':
                return [
                    'provider' => API::$authProvider_yahoo,
                    'settings' => [
                        'client_id' => $this->faker->uuid(),
                        'client_secret' => $this->faker->uuid(),
                    ]
                ];
                break;
            case 'zoom':
                return [
                    'provider' => API::$authProvider_zoom,
                    'settings' => [
                        'client_id' => $this->faker->uuid(),
                        'client_secret' => $this->faker->uuid(),
                    ]
                ];
                break;
            default:
                return ['provider' => API::$authProvider_virtual_calendar];
        };
    }
}
