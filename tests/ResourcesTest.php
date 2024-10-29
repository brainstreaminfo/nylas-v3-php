<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Resource Test
 */
class ResourcesTest extends AbsCase
{
    public function testListResources()
    {
        $this->mockResponse([
            "request_id" => "5fa64c92-e840-4357-86b9-2aa364d35b88",
            "data" => [
                [
                    "building" => "West Building",
                    "capacity" => "8",
                    "email" => "test@example.com",
                    "floor_name" => "7",
                    "floor_section" => "7",
                    "floor_number" => "7",
                    "name" => "Training Room 1A",
                    "object" => "room_resource"
                 ]
              ],
            "next_cursor" => "OQ=="
        ]);

        $response = [];
        try {
            $response = $this->client->Resources->Resource->list(
                $this->client->Options->getGrantId(),
                [
                    'limit' => 1,
                ]
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }
}
