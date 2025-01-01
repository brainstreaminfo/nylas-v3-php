<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Thread Test
 */
class ThreadsTest extends AbsCase
{
    public function testListThread()
    {
        $this->mockResponse($this->prepareListResponse());

        $response = [];
        try {
            $response = $this->client->Threads->Thread->list(
                $this->client->Options->getGrantId(),
                [
                    'limit' => 1,
                    'any_email' => [
                        $this->faker->email,
                        $this->faker->email,
                    ],
                    'unread' => true,
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']['threads']);
        $this->assertGreaterThanOrEqual(1, count($response['data']));
    }

    public function testListThreadWithNativeQuery()
    {
        $this->mockResponse($this->prepareListResponse());

        $response = [];
        try {
            $response = $this->client->Threads->Thread->list(
                $this->client->Options->getGrantId(),
                [
                    'limit' => 1,
                    'search_query_native' => 'test 1',
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']['threads']);
        $this->assertGreaterThanOrEqual(1, count($response['data']));
    }

    public function testFindThread()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "starred" => false,
                "unread" => true,
                "folders" => [
                    "CATEGORY_PERSONAL",
                    "IMPORTANT",
                    "INBOX",
                    "SENT",
                    "UNREAD"
                ],
                "latest_draft_or_message" => [
                    "starred" => false,
                    "unread" => true,
                    "folders" => [
                        "UNREAD",
                        "IMPORTANT",
                        "CATEGORY_PERSONAL",
                        "INBOX"
                    ],
                    "subject" => "Re: Testing message by Rina with Best",
                    "thread_id" => $this->faker->uuid,
                ],
            ],
        ]);

        $response = [];
        try {
            $threadId = $this->faker->uuid;
            $response = $this->client->Threads->Thread->find(
                $this->client->Options->getGrantId(),
                $threadId
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']['latest_draft_or_message']['thread_id']);
    }

    public function testUpdateThread()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "grant_id" => $this->faker->uuid,
                "id" => $this->faker->uuid,
                "object" => "thread",
                "has_attachments" => false,
                "has_drafts" => false,
                "earliest_message_date" => 1634149514,
                "latest_message_received_date" => 1634832749,
                "latest_message_sent_date" => 1635174399,
                "snippet" => "jnlnnn --Sent with Nylas",
                "starred" => false,
                "subject" => "Dinner Wednesday?",
                "unread" => false,
            ],
            "next_cursor" => "CigKGjRlaDdyNGQydTFqbWJ0bGo5a2QxdWJtdDZnGAEggIDAu7fw7bEYGg8IABIAGPjh2PGEi_0CIAEiBwgCEOqs6i4="
        ]);

        $response = [];
        try {
            $grantId = $this->faker->uuid;
            $threadId = $this->faker->uuid;
            $response = $this->client->Threads->Thread->update(
                $this->client->Options->getGrantId(),
                $threadId,
                [
                    'starred' => false,
//                    'folders' => [
//                        'starred',
//                    ],
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteThread()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $threadId = $this->faker->uuid;
            $response = $this->client->Threads->Thread->delete(
                $this->client->Options->getGrantId(),
                $threadId
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['request_id']);
    }

    private function prepareListResponse(): array
    {
        return  [
            "request_id" => $this->faker->uuid,
            "data" => [
                "threads" => [
                    [
                        "grant_id" => $this->faker->uuid,
                        "id" => $this->faker->uuid,
                        "object" => "thread",
                        "has_attachments" => false,
                        "has_drafts" => false,
                        "participants" => [
                            [
                                "email" => $this->faker->email,
                                "name" => $this->faker->name,
                            ],
                        ],
                        "snippet" => "jnlnnn --Sent with Nylas",
                        "starred" => false,
                        "subject" => "Dinner Wednesday?",
                        "unread" => false,
                        "message_ids" => [
                            $this->faker->uuid,
                        ],
                        "draft_ids" => [
                            $this->faker->uuid,
                        ],
                        "folders" => [
                            $this->faker->uuid,
                        ],
                        "latest_draft_or_message" => [
                            "date" => 1635355739,
                            "attachments" => [
                                [
                                    "content_id" => $this->faker->uuid,
                                    "content_type" => "text/calendar",
                                    "id" => "4kj2jrcoj9ve5j9yxqz5cuv98",
                                    "size" => 1708
                                ],
                                [
                                    "content_type" => "application/ics",
                                    "filename" => "invite.ics",
                                    "id" => "70jcsv367jaiavt4njeu4xswg",
                                    "size" => 1708
                                ]
                            ],
                            "folders" => [
                                $this->faker->uuid,
                            ],
                            "grant_id" => $this->faker->uuid,
                            "id" => $this->faker->uuid,
                            "object" => "message",
                            "subject" => "Hello from Nylas!",
                            "thread_id" => $this->faker->uuid,
                            "unread" => true
                        ]
                    ]
                ]
            ],
            "next_cursor" => $this->faker->url,
        ];
    }
}
