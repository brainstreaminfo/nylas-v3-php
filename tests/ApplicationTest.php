<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
//use JsonException;
use function time;

/**
 * Application Test
 */
class ApplicationTest extends AbsCase
{
    /**
     * @return void
     * @throws GuzzleException $e
     */
    public function testGetApplications()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "application_id" => $this->faker->uuid,
                "branding" => [
                    "icon_url" => "https://nylas-static-assets.s3.us-west-2.amazonaws.com/nylas-logo-300x300.png",
                    "name" => "Sandbox",
                ],
                "created_at" => 1727960752,
                "environment" => "sandbox",
                "hosted_authentication" => [
                    "subtitle" => "Log in to your account to continue to Sandbox",
                    "title" => "Welcome",
                ],
                "organization_id" => $this->faker->uuid,
                "region" => "us",
                "updated_at" => 1728026880,
            ],
        ]);

        $data = $this->client->Administration->Application->list();
        $this->assertNotEmpty($data['data']);
    }

    /**
     * @throws GuzzleException $e
     */
    public function testUpdateApplicationDetails(): void
    {
        $this->mockResponse([
            'data' => [
                'application_id' => 'string',
                'organization_id' => 'string',
                'region' => 'string',
                'environment' => 'string',
                'icon_url'         => 'https://inbox-developer-resources.s3.amazonaws.com/icons/da5b3a1c-448c-11e7-872b-0625ca014fd6',
                'redirect_uris'    => ['string'],
            ],
        ]);

        $param = [
            'application_id' => 'test_' . time(),
            'organization_id' => 'test_' . time(),
            'region' => 'test_' . time(),
            'environment' => 'sandbox',
            'callback_uris'    => [
                'url' => 'http://www.test-nylas-test.com',
                'platform' => 'web'
            ],
        ];

        $data = $this->client->Administration->Application->update($param);

        $this->assertArrayHasKey('application_id', $data['data']);
    }

    public function testAddAppCallbackUrl()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid(),
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Application->addCallbackUrl([
                'url' => sprintf('https://test+%s.com', $this->faker->randomNumber()),
                'platform' => 'web',
            ]);
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testGetAppCallbackUrls()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid(),
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Application->listCallbackUrls();
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteAppCallbackUrl()
    {
        $id = $this->faker->uuid();
        $this->mockResponse([
            'data' => [
                'id' => $id,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Application->deleteCallbackUrl($id);
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testGetAppCallbackUrlDetails()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid(),
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Application->getCallbackUrlDetails('test-123');
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }
}
