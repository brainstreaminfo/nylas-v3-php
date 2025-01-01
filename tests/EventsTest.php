<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Event Test
 */
class EventsTest extends AbsCase
{
    public function testListEvents()
    {
        $this->mockResponse([
            "request_id" => "5fa64c92-e840-4357-86b9-2aa364d35b88",
            "data" => [
                [
                    "busy" => true,
                    "calendar_id" => $this->faker->uuid,
                    "conferencing" => [
                        "provider" => "Zoom Meeting",
                        "details" => [
                            "meeting_code" => $this->faker->uuid,
                            "password" => $this->faker->password,
                            "url" => $this->faker->url
                        ]
                    ],
                    "created_at" => 1661874192,
                    "description" => "Description of my new calendar",
                    "hide_participants" => false,
                    "grant_id" => $this->faker->uuid,
                    "html_link" => $this->faker->url,
                    "id" => "5d3qmne77v32r8l4phyuksl2x",
                    "location" => "Roller Rink",
                    "metadata" => [
                        "your_key" => "your_value"
                    ],
                    "object" => "event",
                    "organizer" => [
                        "email" => $this->faker->email,
                        "name" => ""
                    ],
                    "participants" => [
                        [
                            "comment" => "Aristotle",
                            "email" => "aristotle@example.com",
                            "name" => "Aristotle",
                            "phone_number" => "+1 23456778",
                            "status" => "maybe"
                        ]
                    ],
                    "read_only" => false,
                    "reminders" => [
                        "use_default" => false,
                        "overrides" => [
                            [
                                "reminder_minutes" => 10,
                                "reminder_method" => "email"
                            ]
                        ]
                    ],
                    "status" => "confirmed",
                    "title" => "Birthday Party",
                    "updated_at" => 1661874192,
                    "visibility" => "private",
                    "when" => [
                        "start_time" => 1661874192,
                        "end_time" => 1661877792,
                        "start_timezone" => "America/New_York",
                        "end_timezone" => "America/New_York"
                    ]
                ]
            ],
            "next_cursor" => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Events->Event->list(
                $this->client->Options->getGrantId(),
                [
                    'calendar_id' => $this->faker->uuid,
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testCreateEvent()
    {
        $this->mockResponse($this->eventResponse());

        $response = [];
        try {
            $response = $this->client->Events->Event->create(
                $this->client->Options->getGrantId(),
                [
                    'calendar_id' => $this->faker->uuid,
                    'notify_participants' => false,
                ],
                self::createEventParams()
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testUpdateEvent()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "busy" => true,
                "calendar_id" => $this->faker->uuid,
                "conferencing" => [
                ],
                "description" => "Come ready to skate",
                "hide_participants" => false,
                "ical_uid" => $this->faker->uuid,
                "location" => "Roller Rink",
                "organizer" => [
                    "name" => "",
                    "email" => $this->faker->email
                ],
                "participants" => [
                    [
                        "email" => $this->faker->email,
                        "name" => "Test",
                        "status" => "noreply"
                    ]
                ],
                "resources" => [
                ],
                "read_only" => false,
                "reminders" => [
                    "use_default" => true,
                    "overrides" => [
                    ]
                ],
                "title" => "Wow!! we have updated event",
                "visibility" => "default",
                "creator" => [
                    "name" => "",
                    "email" => $this->faker->email
                ],
                "html_link" => $this->faker->url,
                "master_event_id" => $this->faker->uuid,
                "grant_id" => $this->faker->uuid,
                "id" => $this->faker->uuid,
                "object" => "event",
                "status" => "confirmed",
                "when" => [
                    "start_timezone" => "America/New_York",
                    "end_timezone" => "America/New_York",
                    "object" => "timespan",
                    "start_time" => 1729515600,
                    "end_time" => 1729515600
                ],
                "created_at" => 1729060646,
                "updated_at" => 1729061011,
                "original_start_time" => 1729515600
            ]
        ]);

        $response = [];
        try {
            $calenderId = $this->faker->uuid;
            $eventId = $this->faker->uuid;
            $response = $this->client->Events->Event->update(
                $this->client->Options->getGrantId(),
                $eventId,
                [
//                    'select' => 'id,updated_at',
                    'calendar_id' => $calenderId,
                ],
                [
                    'title' => 'Amazing!! we have updated event',
                    'status' => 'cancelled',
                ]
            );

        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testFindEvent()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
            "data" => [
                "busy" => true,
                "calendar_id" => $this->faker->uuid,
                "conferencing" => [
                    "provider" => "Zoom Meeting",
                    "details" => [
                        "meeting_code" => "code-123456",
                        "password" => "password-123456",
                        "url" => $this->faker->url
                    ]
                ],
                "created_at" => 1661874192,
                "description" => "Description of my new calendar",
                "hide_participants" => false,
                "grant_id" => $this->faker->uuid,
                "html_link" => $this->faker->url,
                "id" => $this->faker->uuid,
                "location" => "Roller Rink",
                "metadata" => [
                    "your_key" => "your_value"
                ],
                "object" => "event",
                "organizer" => [
                    "email" => $this->faker->email,
                    "name" => ""
                ],
                "participants" => [
                    [
                        "comment" => "comment",
                        "email" => $this->faker->email,
                        "name" => "Test1",
                        "phone_number" => $this->faker->phoneNumber,
                        "status" => "maybe"
                    ]
                ],
                "read_only" => false,
                "reminders" => [
                    "use_default" => false,
                    "overrides" => [
                        [
                            "reminder_minutes" => 10,
                            "reminder_method" => "email"
                        ]
                    ]
                ],
                "recurrence" => [
                    "RRULE:FREQ=WEEKLY;BYDAY=MO",
                    "EXDATE:20211011T000000Z"
                ],
                "status" => "confirmed",
                "title" => "Birthday Party",
                "updated_at" => 1661874192,
                "visibility" => "private",
                "when" => [
                    "start_time" => 1661874192,
                    "end_time" => 1661877792,
                    "start_timezone" => "America/New_York",
                    "end_timezone" => "America/New_York"
                ]
            ]
        ]);

        $response = [];
        try {
            $calenderId = $this->faker->uuid;
            $eventId = $this->faker->uuid;
            $response = $this->client->Events->Event->find(
                $this->client->Options->getGrantId(),
                $eventId,
                [
                    'calendar_id' => $calenderId,
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteEvent()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $calenderId = $this->faker->uuid;
            $eventId = $this->faker->uuid;
            $response = $this->client->Events->Event->delete(
                $this->client->Options->getGrantId(),
                $eventId,
                [
                    'notify_participants' => true,
                    'calendar_id' => $calenderId,
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    public function testRsvpEvent()
    {
        $this->mockResponse([
            "request_id" => $this->faker->uuid,
        ]);

        $response = [];
        try {
            $calenderId = $this->faker->uuid;
            $eventId = $this->faker->uuid;
            $response = $this->client->Events->Event->sendRsvp(
                $this->client->Options->getGrantId(),
                $eventId,
                [
                    'calendar_id' => $calenderId,
                ],
                [
                    'status' => 'yes',
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response);
    }

    private function createEventParams(): array
    {
        return [
            "title" => sprintf("Birthday Party %s", $this->faker->randomDigit()) ,
            "busy" => true,
            "participants" => [
                [
                    "name" => "Test",
                    "email" => "test@example.com"
                ]
            ],
            "description" => "Come ready to skate",
            "when" => [
                "time" => 1633698000,
                "timezone" => "America/New_York"
            ],
            "location" => "Roller Rink",
            "recurrence" => [
                "RRULE:FREQ=WEEKLY;BYDAY=MO",
                "EXDATE:20211011T000000Z",
            ]
        ];
    }

    private function eventResponse(): array
    {
        return [
            "title" => "Birthday Party",
            "busy" => true,
            "participants" => [
                [
                    "name" => "Aristotle",
                    "email" => $this->faker->email
                ]
            ],
            "description" => "Come ready to skate",
            "when" => [
                "time" => 1633698000,
                "timezone" => "America/New_York"
            ],
            "location" => "Roller Rink",
            "recurrence" => [
                "RRULE:FREQ=WEEKLY;BYDAY=MO",
                "EXDATE:20210405T000000Z"
            ]
        ];
    }
}
