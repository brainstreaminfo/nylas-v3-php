<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Draft Test
 */
class DraftsTest extends AbsCase
{
    public function testListDraft()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                [
                    "body" => "Hello, I just sent a message using Nylas!",
                    "cc" => [
                        [
                            "email" => $this->faker->email
                        ]
                    ],
                    "attachments" => [
                        [
                            "content_type" => "text/calendar",
                            "id" => $this->faker->uuid,
                            "size" => 1708
                        ],
                        [
                            "content_type" => "application/ics",
                            "filename" => "invite.ics",
                            "id" => $this->faker->uuid,
                            "size" => 1708
                        ]
                    ],
                    "folders" => [
                        "8l6c4d11y1p4dm4fxj52whyr9",
                        "d9zkcr2tljpu3m4qpj7l2hbr0"
                    ],
                    "from" => [
                        [
                            "name" => $this->faker->name,
                            "email" => $this->faker->email
                        ]
                    ],
                    "grant_id" => $this->faker->uuid,
                    "id" => $this->faker->uuid,
                    "object" => "message",
                    "reply_to" => [
                        [
                            "name" => $this->faker->name,
                            "email" => $this->faker->email
                        ]
                    ],
                    "snippet" => "Hello, I just sent a message using Nylas!",
                    "starred" => true,
                    "subject" => "Hello from Nylas!",
                    "thread_id" => $this->faker->uuid,
                    "to" => [
                        [
                            "name" => $this->faker->name,
                            "email" => $this->faker->email
                        ]
                    ]
                ]
            ],
            "next_cursor" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Drafts->Draft->list(
                $this->client->Options->getGrantId()
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateDraft()
    {
        $this->mockResponse($this->draftResponse());

        try {
            $response = $this->client->Drafts->Draft->create(
                $this->client->Options->getGrantId(),
                self::prepareDraft()
            );

            $this->assertNotEmpty($response['data']);
        } catch (GuzzleException) {}
    }

    public function testFindDraft()
    {
        $this->mockResponse($this->draftResponse());

        try {
            $response = $this->client->Drafts->Draft->find(
                $this->client->Options->getGrantId(),
                $this->faker->uuid
            );

            $this->assertNotEmpty($response['data']);
        } catch (GuzzleException) {}
    }

    public function testUpdateDraft()
    {
        $this->mockResponse($this->draftResponse());

        try {
            $updateParams = array_merge(self::prepareDraft(true), [
                'subject' => 'WoW!!!! we have updated content.'
            ]);

            $response = $this->client->Drafts->Draft->update(
                $this->client->Options->getGrantId(),
                $this->faker->uuid,
                $updateParams,
            );

            $this->assertNotEmpty($response['data']);
        } catch (GuzzleException) {}
    }

    public function testSendDraft()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "body" => "Hello, I just sent a message using Nylas!",
                "cc" => [
                    [
                        "email" => $this->faker->email
                    ]
                ],
                "attachments" => [
                    [
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
                    "8l6c4d11y1p4dm4fxj52whyr9",
                    "d9zkcr2tljpu3m4qpj7l2hbr0"
                ],
                "from" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ],
                "grant_id" => $this->faker->uuid,
                "id" => "5d3qmne77v32r8l4phyuksl2x",
                "object" => "draft",
                "reply_to" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ],
                "snippet" => "Hello, I just sent a message using Nylas!",
                "starred" => true,
                "subject" => "Hello from Nylas!",
                "thread_id" => "1t8tv3890q4vgmwq6pmdwm8qgsaer",
                "to" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ]
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Drafts->Draft->sendDraft(
                $this->client->Options->getGrantId(),
                $this->faker->uuid,
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteDraft()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Drafts->Draft->delete(
                $this->client->Options->getGrantId(),
                $this->faker->uuid,
            );
        } catch (GuzzleException) {}

        $this->assertArrayHasKey('request_id', $response);
    }

    private function prepareDraft(bool $isUpdateRequest = false): array
    {
        if ($isUpdateRequest) {
            return [
                "bcc" => [
                    [
                        "email" => "test@example.com",
                        "name" => "Test"
                    ]
                ],
                "body" => "Hi, Welcome to Nylas!",
                "cc" => [
                    [
                        "email" => "test1@example.com",
                        "name" => "Test1"
                    ]
                ],
                "attachments" => [
                    [
                        "filename" => "Screenshot-1.png",
//                        "content" => "/home/example/test.png",
                        "content" => "PATH_TO_FILE",
                        "content_type" => "string",
//                        "content_id" => "string",
//                        "content_disposition" => "string"
                    ]
                ],
                "reply_to" => [
                    [
                        "email" => "test3@example.com",
                        "name" => "Test3"
                    ]
                ],
                "starred" => false,
                "subject" => "Invitation: Welcome! @ Thu Oct 28, 2021 7am - 8am (EDT) - Toronto",
                "to" => [
                    [
                        "email" => "test4@example.com",
                        "name" => "Test4"
                    ],
                    [
                        "email" => "test5@example.com",
                        "name" => "Test5"
                    ]
                ],
            ];
        }

        return [
            "bcc" => [
                [
                    "email" => "test@example.com",
                    "name" => "Test"
                ]
            ],
            "body" => "Hi, Welcome to Nylas!",
            "cc" => [
                [
                    "email" => "test1@example.com",
                    "name" => "Test1"
                ]
            ],
            "tracking_options" => [
                "opens" => false,
                "thread_replies" => false,
                "links" => false,
                "label" => "AAAAAA"
            ],
//            "attachments" => [
//                [
//                    "filename" => "string",
//                    "content" => "FILE_PATH",
//                    "content_type" => "string",
//                    "content_id" => "string",
//                    "content_disposition" => "string"
//                ]
//            ],
            "reply_to" => [
                [
                    "email" => "test2@example.com",
                    "name" => "Test2"
                ]
            ],
            "starred" => false,
            "subject" => "Invitation: Welcome! @ Thu Oct 28, 2021 7am - 8am (EDT) - Toronto",
            "to" => [
                [
                    "email" => "test3@example.com",
                    "name" => "Test3"
                ],
                [
                    "email" => "test4@example.com",
                    "name" => "Test4"
                ]
            ],
            "custom_headers" => [
                [
                    "name" => "custom-1",
                    "value" => "Yes it's custom value",
                ]
            ]
        ];
    }

    private function draftResponse()
    {
        return [
            "request_id" => $this->faker->uuid,
            "data" => [
                "body" => "Hello, I just sent a message using Nylas!",
                "cc" => [
                    [
                        "email" => $this->faker->email
                    ]
                ],
                "attachments" => [
                    [
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
                    "8l6c4d11y1p4dm4fxj52whyr9",
                    "d9zkcr2tljpu3m4qpj7l2hbr0"
                ],
                "from" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ],
                "grant_id" => $this->faker->uuid,
                "id" => "5d3qmne77v32r8l4phyuksl2x",
                "object" => "draft",
                "reply_to" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ],
                "snippet" => "Hello, I just sent a message using Nylas!",
                "starred" => true,
                "subject" => "Hello from Nylas!",
                "thread_id" => "1t8tv3890q4vgmwq6pmdwm8qgsaer",
                "to" => [
                    [
                        "name" => $this->faker->name,
                        "email" => $this->faker->email
                    ]
                ]
            ]
        ];
    }
}
