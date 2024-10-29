<?php

declare(strict_types = 1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Nylas\Exceptions\NotFoundException;
use Nylas\Utilities\API;
use PHPUnit\Framework\Attributes\Depends;

/**
 * Grants Test
 */
class GrantsTest extends AbsCase
{
    /**
     * @return void
     * @throws JsonException
     */
    public function testListGrants()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];

        try {
            $response = $this->client->Administration->Grants->list();
        }catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    #[Depends('testListGrants')]
    public function testListGrantsByProvider()
    {
        $this->mockResponse( [
            "request_id" => "5967ca40-a2d8-4ee0-a0e0-6f18ace39a90",
            "data" => [
                [
                    "id" => "e19f8e1a-eb1c-41c0-b6a6-d2e59daf7f47",
                    "provider" => "microsoft",
                    "account_id" => $this->faker->uuid,
                    "grant_status" => "valid",
                    "email" => $this->faker->email,
                    "scope" => [
                        "Mail.Read",
                        "User.Read",
                        "offline_access"
                    ],
                    "user_agent" => "string",
                    "ip" => "string",
                    "state" => "my-state",
                    "created_at" => 1617817109,
                    "updated_at" => 1617817109
                ]
            ],
            "limit" => 10,
            "offset" => 0
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Grants->list([
                'provider' => API::$authProvider_google,
            ]);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    #[Depends('testListGrants')]
    public function testGrantById()
    {
        $this->mockResponse([
            'data' => [
                'id' => 'test-123',
            ],
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Grants->find(
                $this->client->Options->getGrantId()
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testGrantByInvalidId()
    {
        try {
            $this->client->Administration->Grants->find('invalid-grant-id-fdgdfgg');
        } catch (NotFoundException|GuzzleException $e) {
            $this->assertEquals('404', $e->getCode());
        }
    }

    /**
     * @return void
     * @throws JsonException
     */
    #[Depends('testListGrants')]
    public function testUpdateGrant()
    {
        $this->mockResponse([
            'data' => [
                'id' => 'test-123',
            ]
        ]);

        $params = [
            'scope'=> [
                'Mail.Read',
                'User.Read',
                'test'
            ]
        ];

        $response = [];
        try {
            $response = $this->client->Administration->Grants->update(
                $this->client->Options->getGrantId(),
                $params
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testGetCurrentGrant()
    {
        $this->mockResponse([
            'data' => [
                'id' => 'test-123',
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Grants->getCurrentGrant('token-123');
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteGrant()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid(),
        ]);

        $response = [];
        try {
            $grantId = $this->faker->uuid;
            $response = $this->client->Administration->Grants->delete($grantId);
        } catch (GuzzleException) {}

        $this->assertArrayHasKey('request_id', $response);
    }
}
