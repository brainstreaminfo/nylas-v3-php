<?php

declare(strict_types = 1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;

/**
 * Calendar Test
 */
class CalendarTest extends AbsCase
{
    /**
     * @return void
     * @throws JsonException
     */
    public function testAllCalenders()
    {
        $this->mockResponse([
            'data' => [
                'id' => 'test-123',
            ]
        ]);

        $response = [];

        try {
            $response = $this->client->Calendars->Calendar->list(
                $this->client->Options->getGrantId()
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testCreateACalendar()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];

        try {
            $response = $this->client->Calendars->Calendar->create(
                $this->client->Options->getGrantId(),
                [
                    'name' => 'Birthday party calender',
                    'description' => 'Birthday party calender',
                    'metadata' => [
                        'key1' => 'all-meetings',
                        'key2' => 'on-site',
                    ],
                ]
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testCalenderFind()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];

        try {
            $calendarId = $this->faker->uuid;

            $response = $this->client->Calendars->Calendar->find(
                $this->client->Options->getGrantId(),
                $calendarId
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testUpdateCalendar()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];

        try {
            $calendarId = $this->faker->uuid;
            $params = [
                'name' => 'New Calendar',
                'description' => 'Description of my new calendar',
                'location' => 'Location description',
                'timezone' => 'America/Los_Angeles',
            ];

            $response = $this->client->Calendars->Calendar->update(
                $this->client->Options->getGrantId(),
                $calendarId,
                $params
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testDeleteCalendar()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];
        try {
            $calendarId = $this->faker->uuid;

            $response = $this->client->Calendars->Calendar->delete(
                $this->client->Options->getGrantId(),
                $calendarId
            );
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testGetFreeOrBusySchedule()
    {
        $this->mockResponse([
            'data' => [
                'id' => $this->faker->uuid,
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Calendars->Calendar->getFreeOrBusySchedule(
                $this->client->Options->getGrantId(),
                [
                    'start_time' => (new \DateTime())->getTimestamp(),
                    'end_time' => (new \DateTime('+5 days'))->getTimestamp(),
                    'emails' => [
                        $this->faker->email,
                ]
            ]);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testFindAvailability()
    {
        $this->mockResponse([
            'data' => [
                'time_slots' => []
            ]
        ]);

        $response = [];
        try{
            $response = $this->client->Calendars->Calendar->findAvailability(self::prepareForAvailability());
        } catch (GuzzleException) {}

        $this->assertIsArray($response['data']['time_slots']);
    }

    private static function prepareForAvailability(): array
    {
        return [
            "participants" => [
                [
                    "email" => "test@example.com",
                    "calendar_ids" => [
                        "test1@example.com"
                    ],
                    "open_hours" => [
                        [
                            "days" => [
                                0,
                                1,
                                2
                            ],
                            "timezone" => "America/Toronto",
                            "start" => "9:00",
                            "end" => "17:00",
                            "exdates" => [
                            ]
                        ]
                    ]
                ],
                [
                    "email" => "test@example.com"
                ]
            ],
            "start_time" => 1600890600,
            "end_time" => 1600999200,
            "interval_minutes" => 30,
            "duration_minutes" => 30,
            "round_to" => 15,
            "availability_rules" => [
                "availability_method" => "collective",
                "buffer" => [
                    "before" => 15,
                    "after" => 15
                ],
                "default_open_hours" => [
                    [
                        "days" => [
                            0,
                            1,
                            2
                        ],
                        "timezone" => "America/Toronto",
                        "start" => "9:00",
                        "end" => "17:00",
                        "exdates" => [
                        ]
                    ],
                    [
                        "days" => [
                            3,
                            4,
                            5
                        ],
                        "timezone" => "America/Toronto",
                        "start" => "10:00",
                        "end" => "18:00",
                        "exdates" => [
                        ]
                    ]
                ]
            ]
        ];
    }
}
