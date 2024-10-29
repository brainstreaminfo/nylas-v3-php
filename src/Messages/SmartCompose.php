<?php

declare(strict_types = 1);

namespace Nylas\Messages;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;

/**
 * Nylas Smart Compose mail
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Smart-compose
 */
class SmartCompose
{
    /**
     * Contact constructor.
     *
     * @param Options $options
     */
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Generate an email message based on a prompt.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/messages/smart-compose
     *
     * @param string $grantId
     * @param string $prompt
     * @return array
     * @throws GuzzleException
     */
    public function smartComposeEmail(string $grantId, string $prompt): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(
            V::key('prompt', V::stringType()::notEmpty()::length(max: 1000)),
            [
                'prompt' => $prompt
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setFormParams(['prompt' => $prompt])
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['smartCompose']);
    }

    /**
     * Generate a reply to the specified email message based on a prompt.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/messages/-message_id-/smart-compose
     *
     * @param string $grantId
     * @param string $messageId
     * @param string $prompt
     * @return array
     * @throws GuzzleException
     */
    public function smartComposeReply(string $grantId, string $messageId, string $prompt): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty())
            ),
            [
                'grantId' => $grantId,
                'messageId' => $messageId
            ]
        );

        V::doValidate(
            V::key('prompt', V::stringType()::notEmpty()::length(max: 1000)),
            [
                'prompt' => $prompt
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $messageId)
            ->setFormParams(['prompt' => $prompt])
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['smartComposeReply']);
    }
}
