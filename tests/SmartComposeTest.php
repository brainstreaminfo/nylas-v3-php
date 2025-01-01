<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Smart Compose Test
 */
class SmartComposeTest extends AbsCase
{
    public function testSmartComposeEmail()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "suggestion" => "test-123"
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->SmartCompose->smartComposeEmail(
                $this->client->Options->getGrantId(),
                'Reply to John Doe about the upcoming project.'
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('suggestion', $response['data']);
    }
    public function testSmartComposeReply()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "suggestion" => "test-123"
            ]
        ]);

        $response = [];
        try {
            $messageId = $this->faker->uuid;
            $response = $this->client->Messages->SmartCompose->smartComposeReply(
                $this->client->Options->getGrantId(),
                $messageId,
                'Reply to John Doe about the upcoming project.'
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('suggestion', $response['data']);
    }
}
