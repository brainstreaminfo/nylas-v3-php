<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Contact Test
 */
class ContactsTest extends AbsCase
{
    public function testListContacts()
    {
        $this->mockResponse(
            [
                "request_id" => $this->faker->uuid,
                "data" => [
                    [
                        "birthday" => "1960-12-31",
                        "company_name" => "Nylas",
                        "emails" => [
                            [
                                "type" => "work",
                                "email" => $this->faker->email
                            ],
                            [
                                "type" => "home",
                                "email" => $this->faker->email
                            ]
                        ],
                        "given_name" => "John",
                        "grant_id" => $this->faker->uuid,
                        "groups" => [
                            [
                                "id" => "starred"
                            ],
                            [
                                "id" => "friends"
                            ]
                        ],
                        "id" => $this->faker->uuid,
                        "im_addresses" => [
                            [
                                "type" => "jabber",
                                "im_address" => "myjabberaddress"
                            ],
                            [
                                "type" => "msn",
                                "im_address" => "mymsnaddress"
                            ]
                        ],
                        "job_title" => "Software Engineer",
                        "manager_name" => "Bill",
                        "middle_name" => "Jacob",
                        "nickname" => "JD",
                        "notes" => "Loves ramen",
                        "object" => "contact",
                        "office_location" => "123 Main Street",
                        "phone_numbers" => [
                            [
                                "type" => "work",
                                "number" => $this->faker->phoneNumber
                            ],
                            [
                                "type" => "home",
                                "number" => $this->faker->phoneNumber
                            ]
                        ],
                        "physical_addresses" => [
                            [
                                "type" => "work",
                                "street_address" => "123 Main Street",
                                "postal_code" => "94107",
                                "state" => "CA",
                                "country" => "US",
                                "city" => "San Francisco"
                            ],
                            [
                                "type" => "home",
                                "street_address" => "123 Main Street",
                                "postal_code" => "94107",
                                "state" => "CA",
                                "country" => "US",
                                "city" => "San Francisco"
                            ]
                        ],
                        "picture_url" => "https://example.com/picture.jpg",
                        "source" => "address_book",
                        "suffix" => "Jr.",
                        "surname" => "Doe",
                        "web_pages" => [
                            [
                                "type" => "work",
                                "url" => $this->faker->url
                            ],
                            [
                                "type" => "home",
                                "url" => $this->faker->url
                            ]
                        ]
                    ]
                ],
                "next_cursor" => $this->faker->uuid
            ]
        );

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->list(
                $this->client->Options->getGrantId(),
                [
                    'limit' => 1,
                ]
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['request_id']);
    }

    public function testCreatContact()
    {
        $this->mockResponse($this->contactResponse());

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->create(
                $this->client->Options->getGrantId(),
                $this->prepareCreatContact()
            );
        } catch (GuzzleException $e) {}

        $this->assertNotEmpty($response['data']);
    }

    public function testFindContact()
    {
        $contactId = $this->faker->uuid;

        $this->mockResponse($this->contactResponse());

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->find(
                $this->client->Options->getGrantId(),
                $contactId
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('id', $response['data']);
    }

    public function testUpdateContact()
    {
        $contactId = $this->faker->uuid;

        $this->mockResponse($this->contactResponse());

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->update(
                $this->client->Options->getGrantId(),
                $contactId,
                array_merge(
                    $this->prepareCreatContact(),
                    [
                        'birthday' => date('Y-m-d')
                    ]
                )
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('id', $response['data']);
    }

    public function testDeleteContact()
    {
        $contactId = $this->faker->uuid;

        $this->mockResponse([
            'request_id' => $contactId,
        ]);

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->delete(
                $this->client->Options->getGrantId(),
                $contactId
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('request_id', $response);
    }

    public function testContactGroups()
    {
        $this->mockResponse([
            'data' => [
                [
                    'id' => $this->faker->uuid,
                    'grant_id' => $this->faker->uuid,
                    'group_type' => 'system',
                ],
            ]
        ]);

        $response = [];
        try {
            $response = $this->client->Contacts->Contact->contactGroups(
                $this->client->Options->getGrantId()
            );
        } catch (GuzzleException $e) {}

        $this->assertArrayHasKey('id', $response['data'][0]);
    }

    private function prepareCreatContact(): array
    {
        return  [
            "birthday" => "1960-12-31",
            "company_name" => "Nylas",
            "emails" => [
                [
                    "email" => $this->faker->email,
                    "type" => "work"
                ],
                [
                    "email" => $this->faker->email,
                    "type" => "home"
                ]
            ],
            "given_name" => $this->faker->name,
//            "groups" => [
//                [
//                    "id" => "6c0a0720893fb75d"
//                ],
//                [
//                    "id" => "all"
//                ]
//            ],
            "im_addresses" => [
                [
                    "type" => "jabber",
                    "im_address" => "myjabberaddress"
                ],
                [
                    "type" => "msn",
                    "im_address" => "mymsnaddress"
                ]
            ],
            "job_title" => "Software Engineer",
            "manager_name" => $this->faker->name,
            "middle_name" => $this->faker->name,
            "nickname" => "JD",
            "notes" => "Loves Ramen",
            "office_location" => "123 Main Street",
            "phone_numbers" => [
                [
                    "number" => $this->faker->phoneNumber,
                    "type" => "work"
                ],
                [
                    "number" => $this->faker->phoneNumber,
                    "type" => "home"
                ]
            ],
            "physical_addresses" => [
                [
                    "type" => "work",
                    "street_address" => "123 Main Street",
                    "postal_code" => "94107",
                    "state" => "CA",
                    "country" => "USA",
                    "city" => "San Francisco"
                ],
                [
                    "type" => "home",
                    "street_address" => "456 Main Street",
                    "postal_code" => "94107",
                    "state" => "CA",
                    "country" => "USA",
                    "city" => "San Francisco"
                ]
            ],
            "source" => "address_book",
            "suffix" => "Jr.",
            "surname" => "Doe",
            "web_pages" => [
                [
                    "type" => "work",
                    "url" => $this->faker->url
                ],
                [
                    "type" => "home",
                    "url" => $this->faker->url
                ]
            ]
        ];
    }

    private function contactResponse()
    {
        return [
            "request_id" => $this->faker->uuid,
            "data" => [
                "birthday" => "1960-12-31",
                "company_name" => "Nylas",
                "emails" => [
                    [
                        "type" => "work",
                        "email" => $this->faker->email
                    ],
                    [
                        "type" => "home",
                        "email" => $this->faker->email
                    ]
                ],
                "given_name" => "John",
                "grant_id" => $this->faker->uuid,
                "groups" => [
                    [
                        "id" => "starred"
                    ],
                    [
                        "id" => "friends"
                    ]
                ],
                "id" => $this->faker->uuid,
                "im_addresses" => [
                    [
                        "type" => "jabber",
                        "im_address" => "myjabberaddress"
                    ],
                    [
                        "type" => "msn",
                        "im_address" => "mymsnaddress"
                    ]
                ],
                "job_title" => "Software Engineer",
                "manager_name" => "Bill",
                "middle_name" => "Jacob",
                "nickname" => "JD",
                "notes" => "Loves ramen",
                "object" => "contact",
                "office_location" => "123 Main Street",
                "phone_numbers" => [
                    [
                        "type" => "work",
                        "number" => $this->faker->phoneNumber
                    ],
                    [
                        "type" => "home",
                        "number" => $this->faker->phoneNumber
                    ]
                ],
                "physical_addresses" => [
                    [
                        "type" => "work",
                        "street_address" => "123 Main Street",
                        "postal_code" => "94107",
                        "state" => "CA",
                        "country" => "US",
                        "city" => "San Francisco"
                    ],
                    [
                        "type" => "home",
                        "street_address" => "321 Pleasant Drive",
                        "postal_code" => "94107",
                        "state" => "CA",
                        "country" => "US",
                        "city" => "San Francisco"
                    ]
                ],
                "picture_url" => "https://example.com/picture.jpg",
                "source" => "address_book",
                "suffix" => "Jr.",
                "surname" => "Doe",
                "web_pages" => [
                    [
                        "type" => "work",
                        "url" => $this->faker->url
                    ],
                    [
                        "type" => "home",
                        "url" => $this->faker->url
                    ]
                ]
            ]
        ];
    }
}
