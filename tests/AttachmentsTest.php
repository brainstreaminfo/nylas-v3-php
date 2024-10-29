<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Attachment Test
 */
class AttachmentsTest extends AbsCase
{
    public function testFindAttachment()
    {
        $this->mockResponse( [
            "request_id" => $this->faker->uuid,
            "data" => [
                "content_type" => "image/png; name=\"pic.png\"",
                 "content_disposition" => "inline; filename=\"pic.png\"",
                 "filename" => "pic.png",
                 "grant_id" => $this->faker->uuid,
                 "id" => "185e56cb50e12e82",
                 "is_inline" => true,
                 "size" => 13068,
                 "content_id" => $this->faker->uuid
              ]
        ]);

        $response = [];
        try {
            $attachmentId = $this->faker->uuid;
            $messageId = $this->faker->uuid;

            $response = $this->client->Attachments->Attachment->find(
                $this->client->Options->getGrantId(),
                $attachmentId,
                $messageId
            );
        } catch (GuzzleException) {}

        $this->assertArrayHasKey('id', $response['data']);
    }

    public function testDownloadAttachment()
    {
        $this->mockResponse(['data' => 'some binary data']);

        $response = [];
        try {
            $attachmentId = $this->faker->uuid;
            $messageId = $this->faker->uuid;

            $response = $this->client->Attachments->Attachment->download(
                $this->client->Options->getGrantId(),
                $attachmentId,
                $messageId
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }
}
