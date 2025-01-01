<?php

declare(strict_types=1);

namespace Tests;

use Exception;
//use JsonException;
use function json_encode;

/**
 * Signature Test
 */
class SignatureTest extends AbsCase
{
    public function testEchoChallenge(): void
    {
        $this->client->Webhooks->Signature->echoChallenge();

        $this->assertPassed();
    }

    /**
     * @expectedException Exception
     */
    public function testGetNotification(): void
    {
        //$this->expectException(Exception::class);

        $this->client->Webhooks->Signature->getNotification();
    }

    /**
     */
    public function testParseNotification(): void
    {
        $params = json_encode(['deltas' => ['aaa' => 'bbb']]);

        $data = $this->client->Webhooks->Signature->parseNotification($params);

        static::assertArrayHasKey('aaa', $data);
    }

    public function testXSignatureVerification(): void
    {
        $para = $this->faker->name;
        $code = $this->faker->uuid;

        $data = $this->client->Webhooks->Signature->xSignatureVerification($code, $para);

        static::assertFalse($data);
    }
}
