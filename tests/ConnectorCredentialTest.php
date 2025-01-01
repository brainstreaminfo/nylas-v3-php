<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
//use JsonException;
use Nylas\Utilities\API;

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
/**
 * Connector Test
 */
class ConnectorCredentialTest extends AbsCase
{
    /**
     * @return void
     */
    public function testListCredential()
    {
        $response = [];
        try {
            $response = $this->client->Administration->ConnectorsCredentials->list(API::$authProvider_google);
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('request_id', $response);
    }

    /**
     * @return void
     */
    public function testCreateCredential()
    {
        $this->mockResponse([
            "data" => [
                [
                    "id" => "e48acbd1-e887-48e3-9aba-f7e9d5177e71",
                    "name" => "My first Google credential",
                    "credential_type" => "serviceaccount",
                    "hashed_data" => $this->faker->uuid,
                    "created_at" => 1728570218,
                    "updated_at" => 1728570218
                ]
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->ConnectorsCredentials->create(
                API::$authProvider_google,
                [
                    "name" => "My first Google credential",
                    "credential_type" => "serviceaccount",
                    "credential_data" => [
                        "type" => "service_account",
                        "project_id" => "marketplace-sa-test",
                        "private_key_id" => "abcd1234defg5678",
                        "private_key" => "-----BEGIN PRIVATE KEY-----
...
-----END PRIVATE KEY-----
",
                        "client_email" => "some-name@marketplace-sa-test.iam.gserviceaccount.com",
                        "client_id" => "123456789",
                        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                        "token_uri" => "https://oauth2.googleapis.com/token",
                        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/some-name%40marketplace-sa-test.iam.gserviceaccount.com"
                    ]
                ]
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testUpdateCredential()
    {
        $this->mockResponse([
            "data" => [
                [
                    "id" => "e48acbd1-e887-48e3-9aba-f7e9d5177e71",
                    "name" => "My first Google credential",
                    "credential_type" => "serviceaccount",
                    "hashed_data" => $this->faker->uuid,
                    "created_at" => 1728570218,
                    "updated_at" => 1728570218
                ]
            ]
        ]);

        $response = [];
        try {
            $credentialId = $this->faker->uuid;

            $response = $this->client->Administration->ConnectorsCredentials->update(
                API::$authProvider_google,
                $credentialId,
                [
                    "name" => "Updated Google credential",
                    "credential_data" => [
                        "type" => "service_account",
                        "project_id" => "marketplace-sa-test",
                        "private_key_id" => "abcd1234defg5678",
                        "private_key" => "-----BEGIN PRIVATE KEY-----
...
-----END PRIVATE KEY-----
",
                        "client_email" => "some-name@marketplace-sa-test.iam.gserviceaccount.com",
                        "client_id" => "123456789",
                        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                        "token_uri" => "https://oauth2.googleapis.com/token",
                        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/some-name%40marketplace-sa-test.iam.gserviceaccount.com"
                    ]
                ]
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testFindCredential()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "id" => $this->faker->uuid,
                "name" => "Google auth app \#2 multitenant",
                "credential_type" => "connector",
                "hashed_data" => $this->faker->uuid,
                "created_at" => 1728570686,
                "updated_at" => 1728570686
            ]
        ]);

        $response = [];
        try {
            $credentialId = $this->faker->uuid;

            $response = $this->client->Administration->ConnectorsCredentials->find(
                API::$authProvider_google,
                $credentialId
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteCredential()
    {
        $this->mockResponse(
            [
                "request_id" => $this->faker->uuid,
            ]
        );

        $response = [];
        try {
            $credentialId = $this->faker->uuid;

            $response = $this->client->Administration->ConnectorsCredentials->delete(
                API::$authProvider_google,
                $credentialId
            );
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('request_id', $response);
    }
}
