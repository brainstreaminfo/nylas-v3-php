<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Exceptions\NylasException;

/**
 * Message Test
 */
class MessagesTest extends AbsCase
{
    public function testListMessages()
    {
        $this->mockResponse($this->searchResponse());

        $response = [];
        try {
            $response = $this->client->Messages->Message->list(
                $this->client->Options->getGrantId(),
                [
                    'limit' => 1,
                ]
            );
        } catch (GuzzleException $e) {
        }

        //$this->assertIsArray($response['data']);
        $this->assertTrue(is_array($response['data']));
    }

    public function testListMessagesWithSearchFilter()
    {
        $this->mockResponse($this->searchResponse());

        $response = [];
        try {
            $response = $this->client->Messages->Message->list(
                $this->client->Options->getGrantId(),
                [
                    'unread' => true,
                    'limit'  => 1,
                ]
            );
        } catch (GuzzleException $e) {
        }

        //$this->assertIsArray($response['data']);
        $this->assertTrue(is_array($response['data']));
    }

    public function testFindMessageById()
    {
        $this->mockResponse($this->messageResponse());

        $response = [];
        try {
            $messageId = $this->faker->uuid;
            $response = $this->client->Messages->Message->find(
                $this->client->Options->getGrantId(),
                $messageId
            );
        } catch (\Exception $e) {
            $this->assertEquals(404, $e->getCode());
        }

        $this->assertNotEmpty($response['subject']);
    }

    public function testCreateMessageWithTracking()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                self::prepareMessageWithTracking()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateSimpleTextMessage()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                [
                    "subject" => "Simple email",
                    "body" => "Simple email without attachment and tracking data",
                    "from" => [
                        [
                            "name" => "test",
                            "email" => "test@example.com"
                        ]
                    ],
                    "to" => [
                        [
                            "name" => "test1",
                            "email" => "test1@example.com"
                        ]
                    ],
                ]
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateMessageWithImages()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                self::prepareMessageWithImages()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateMessageWithInLineImage()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                self::prepareMessageWithInLineImage()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateMessageWithPdf()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                self::prepareMessageWithPdf()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateMessageWithDraft()
    {
        $this->mockResponse([
            'data' => [
                "bcc" => null,
                "body" => "Nylas v3 Test!",
                "cc" => null,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->send(
                $this->client->Options->getGrantId(),
                $this->prepareMessageWithDraft()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['data']);
    }

    public function testUpdateMessage()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "starred" => true,
                "unread" => true,
                "folders" => [
                    "UNREAD",
                    "STARRED",
                ]
            ],
        ]);

        $response = [];
        try {
            $messageId = $this->faker->uuid;
            $response = $this->client->Messages->Message->update(
                $this->client->Options->getGrantId(),
                $messageId,
                [
                    'starred' => $this->faker->randomElement([true, false]),
                ]
            );
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('starred', $response['data']);
    }

    public function testDeleteMessage()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $messageId = $this->faker->uuid;
            $response = $this->client->Messages->Message->delete(
                $this->client->Options->getGrantId(),
                $messageId
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response['request_id']);
    }

    public function testCleanMessage()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ],
        ]);

        $response = [];
        try {
            $messageId = $this->faker->uuid;
            $response = $this->client->Messages->Message->cleanMessage(
                $this->client->Options->getGrantId(),
                [
                    'message_id' => [
                        $messageId,
                    ],
                    'ignore_links' => true,
                ]
            );
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('data', $response);
    }

    public function testScheduleMessages()
    {
        $this->mockResponse([
            [
                "schedule_id" => "8cd56334-6d95-432c-86d1-c5dab0ce98be",
                "status" => [
                    "code" => "pending",
                    "description" => "schedule send awaiting send at time"
                ]
            ],
            [
                "schedule_id" => "rb856334-6d95-432c-86d1-c5dab0ce98be",
                "status" => [
                    "code" => "sucess",
                    "description" => "schedule send succeeded"
                ],
                "close_time" => 1690579819
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->listScheduleMessages(
                $this->client->Options->getGrantId()
            );
        } catch (GuzzleException $e) {
        }

        $this->assertNotEmpty($response);
    }

    public function testFindScheduleMessages()
    {
        $this->mockResponse([
            "schedule_id" => $this->faker->uuid,
            "status" => [
                "code" => "pending",
                "description" => "schedule send awaiting send at time"
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Messages->Message->findScheduleMessage(
                $this->client->Options->getGrantId(),
                $this->faker->uuid
            );
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('status', $response);
    }

    public function testDeleteScheduleMessages()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "message" => "successfully requested cancelation for schedule id c2a6b6da-300a-49fc-b817-32adae339d18"
            ]
        ]);

        $response = [];
        try {
            $scheduleId = $this->faker->uuid;
            $response = $this->client->Messages->Message->deleteScheduleMessage(
                $this->client->Options->getGrantId(),
                $scheduleId
            );
        } catch (GuzzleException $e) {
        }

        $this->assertArrayHasKey('message', $response['data']);
    }

    private static function prepareMessageWithImages(): array
    {
        return  [
            "subject" => "Email with attachment images",
            "body" => "Testing email with attachment images",
            "from" => [
                [
                    "name" => "Test1",
                    "email" => "test1@example.com",
                ],
            ],
            "to" => [
                [
                    "name" => "Test2",
                    "email" => "test2@example.com",
                ],
                [
                    "name" => "Test3",
                    "email" => "test3@example.com",
                ],
            ],
            "attachments" => [
                [
                    //                    "content" => "/home/example/300x50_demo.jpg",
                    "content" => "PATH_TO_FILE",
                    "content_type" => "image/jpeg",
                    "filename" => "300x50_demo.jpg",
                ],
                [
                    "content" => "PATH-TO_FILE",
                    "content_type" => "image/png",
                    "filename" => "Screenshot.png",
                ],
            ],
        ];
    }

    private static function prepareMessageWithInLineImage(): array
    {
        return  [
            "subject" => "Email with inline image",
            "body" => "Testing email with inline image <img src=\"cid:test-123\"/>",
            "from" => [
                [
                    "name" => "test",
                    "email" => "test@example.com"
                ]
            ],
            "to" => [
                [
                    "name" => "test1",
                    "email" => "test1@example.com"
                ]
            ],
            "cc" => [
                [
                    "name" => "Nylas",
                    "email" => "test2@example.com"
                ]
            ],
            "attachments" => [
                [
                    //                    "content" => "/home/example/300x50_demo.jpg",
                    "content" => "PATH-TO-FILE",
                    "content_type" => "image/jpeg",
                    "filename" => "300x50_demo.jpg",
                    "content_id" => "test-123",
                ],
            ],
        ];
    }

    private static function prepareMessageWithPdf(): array
    {
        return  [
            "subject" => "Email with PDF",
            "body" => "Testing email with PDF",
            "from" => [
                [
                    "name" => "test",
                    "email" => "test@example.com"
                ]
            ],
            "to" => [
                [
                    "name" => "test1",
                    "email" => "test1@example.com"
                ]
            ],
            "cc" => [
                [
                    "name" => "Nylas",
                    "email" => "test3@example.com"
                ]
            ],
            "attachments" => [
                [
                    "content" => "PDF-FILE-PATH",
                    "content_type" => "application/pdf",
                    "filename" => "test.pdf",
                ],
            ],
        ];
    }

    private function prepareMessageWithDraft(): array
    {
        return  [
            "subject" => "Email with PDF",
            "body" => "Testing email with PDF",
            "from" => [
                [
                    "name" => "test",
                    "email" => "test@example.com"
                ]
            ],
            "to" => [
                [
                    "name" => "test1",
                    "email" => "test1@example.com"
                ]
            ],
            "cc" => [
                [
                    "name" => "Nylas",
                    "email" => "test2@example.com"
                ]
            ],
            "send_at" => strtotime((new \DateTime('+10 day'))->format('Y-m-d')),
            "use_draft" => true,
        ];
    }

    private static function prepareMessageWithTracking(): array
    {
        return  [
            "subject" => "Email with tracking info.",
            "body" => "Testing email with tracking info.",
            "from" => [
                [
                    "name" => "test",
                    "email" => "test@example.com"
                ]
            ],
            "to" => [
                [
                    "name" => "test1",
                    "email" => "test1@example.com"
                ]
            ],
            "cc" => [
                [
                    "name" => "Nylas",
                    "email" => "test2@example.com"
                ]
            ],
            //            "bcc" => [
            //                [
            //                    "name" => "Nylas",
            //                    "email" => "test@example.com"
            //                ]
            //            ],
            "tracking_options" => [
                "opens" => true,
                "links" => true,
                "thread_replies" => true,
                "label" => "tracking my Nylas recruiting email",
            ],
        ];
    }

    private function searchResponse()
    {
        return [
            "request_id" => $this->faker->uuid,
            "data" => [
                [
                    "body" => "Hello, I just sent a message using Nylas!",
                    "cc" => [
                        [
                            "name" => $this->faker->name,
                            "email" => $this->faker->email
                        ]
                    ],
                    "date" => 1635355739,
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
                    "thread_id" => "1t8tv3890q4vgmwq6pmdwm8qgsaer",
                    "to" => [
                        [
                            "name" => $this->faker->name,
                            "email" => $this->faker->email
                        ]
                    ],
                    "unread" => true
                ]
            ],
            "next_cursor" => $this->faker->uuid
        ];
    }

    private function messageResponse()
    {
        return [
            "subject" => "Sending Emails with Nylas",
            "body" => "Nylas v3 Test!",
            "from" => [
                [
                    "name" => $this->faker->name,
                    "email" => $this->faker->email
                ]
            ],
            "to" => [
                [
                    "name" => $this->faker->name,
                    "email" => $this->faker->email
                ]
            ],
            "cc" => [
                [
                    "name" => "Nylas",
                    "email" => "nylas@example.com"
                ]
            ],
            "bcc" => [
                [
                    "name" => "Nylas",
                    "email" => "nylas@example.com"
                ]
            ],
            "reply_to" => [
                [
                    "name" => "Nylas",
                    "email" => "nylas@example.com"
                ]
            ],
            "send_at" => 1671234087,
            "use_draft" => true,
            "attachments" => [
                [
                    "content" => "HASKDJhiuahsdjlkhKJAsd=",
                    "content_type" => "text/plain",
                    "filename" => "myfile.txt"
                ]
            ]
        ];
    }
}
