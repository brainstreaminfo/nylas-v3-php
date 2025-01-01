<?php

namespace Nylas\Attachments;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;

/**
 * @property Attachment Attachment
 */
class Attachment
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Return Attachment metadata
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/attachments/-attachment_id-
     *
     * @param string $grantId
     * @param string $attachmentId
     * @param string $messageId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $attachmentId, string $messageId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('attachmentId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty())
            ),
            [
                'grantId'       => $grantId,
                'attachmentId'  => $attachmentId,
                'messageId'     => $messageId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $attachmentId)
            ->setQuery([
                'message_id' => $messageId,
            ])
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['attachment']);
    }

    /**
     * Download an Attachment
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/attachments/-attachment_id-/download
     *
     * @param string $grantId
     * @param string $attachmentId
     * @param string $messageId
     * @return array
     * @throws GuzzleException
     */
    public function download(string $grantId, string $attachmentId, string $messageId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('attachmentId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty())
            ),
            [
                'grantId'       => $grantId,
                'attachmentId'  => $attachmentId,
                'messageId'     => $messageId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $attachmentId)
            ->setQuery([
                'message_id' => $messageId,
            ])
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['downloadAttachment']);
    }
}
