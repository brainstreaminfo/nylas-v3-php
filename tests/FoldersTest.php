<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Folder Test
 */
class FoldersTest extends AbsCase
{
    public function testListFolder()
    {
        $this->mockResponse([
            "request_id" => "5fa64c92-e840-4357-86b9-2aa364d35b88",
            "data" => [
                [
                    "id" => "SENT",
                    "grant_id" => $this->faker->uuid,
                    "name" => "SENT",
                    "system_folder" => true
                ],
                [
                    "id" => "INBOX",
                    "grant_id" => $this->faker->uuid,
                    "name" => "INBOX",
                    "system_folder" => true
                ],
                [
                    "id" => "Label_2",
                    "grant_id" => $this->faker->uuid,
                    "name" => "New Label with Color",
                    "system_folder" => false
                ]
            ],
            "next_cursor" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Folders->Folder->list(
                $this->client->Options->getGrantId(),
            );
        } catch (GuzzleException) {}

        $this->assertGreaterThan(1, count($response['data']));
    }

    public function testCreateFolder()
    {
        $this->mockResponse([
            "request_id" => "5fa64c92-e840-4357-86b9-2aa364d35b88",
            "data" => [
                [
                    "id" => "SENT",
                    "grant_id" => $this->faker->uuid,
                    "name" => "SENT",
                    "system_folder" => true
                ],
                [
                    "id" => "INBOX",
                    "grant_id" => $this->faker->uuid,
                    "name" => "INBOX",
                    "system_folder" => true
                ],
                [
                    "id" => "Label_2",
                    "grant_id" => $this->faker->uuid,
                    "name" => "New Label with Color",
                    "system_folder" => false
                ]
            ],
            "next_cursor" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Folders->Folder->create(
                $this->client->Options->getGrantId(),
                [
                    'name' => 'test-nylas-'.rand(1, 100),
                ]
            );
        } catch (GuzzleException) {}

        $this->assertGreaterThan(1, count($response['data']));
    }

    public function testFindFolder()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "id" => "SENT",
                "grant_id" => $this->faker->uuid,
                "name" => "SENT",
                "system_folder" => true,
                "attributes" => [
                    "Sent"
                ]
            ]
        ]);

        $response = [];
        try {
            $folderId = $this->faker->uuid;
            $response = $this->client->Folders->Folder->find(
                $this->client->Options->getGrantId(),
                $folderId
            );
        } catch (GuzzleException) {}

        $this->assertArrayHasKey('id', $response['data']);
    }

    public function testUpdateFolder()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "id" => "SENT",
                "grant_id" => $this->faker->uuid,
                "name" => "SENT",
                "system_folder" => true,
                "attributes" => [
                    "Sent"
                ]
            ]
        ]);

        $response = [];
        try {
            $folderId = $this->faker->uuid;
            $response = $this->client->Folders->Folder->update(
                $this->client->Options->getGrantId(),
                $folderId,
                [
                    'name' => 'Label_'.$this->faker->randomDigit(),
                ]
            );
        } catch (GuzzleException) {}

        $this->assertArrayHasKey('id', $response['data']);
    }

    public function testDeleteFolder()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $folderId = $this->faker->uuid;
            $response = $this->client->Folders->Folder->delete(
                $this->client->Options->getGrantId(),
                $folderId
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }
}
