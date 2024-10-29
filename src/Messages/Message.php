<?php

declare(strict_types = 1);

namespace Nylas\Messages;

use Nylas\Utilities\API;
use Nylas\Utilities\Helper;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Messages Api
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Messages
 */
class Message
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
     * Return all Messages
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/messages
     *
     * @param string $grantId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $queryParams = []): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(Validation::searchFilterRules(), $queryParams);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['messages']);
    }

    /**
     * Return an email message by ID.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/messages/-message_id-
     *
     * @param string $grantId
     * @param string $messageId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $messageId, array $queryParams = []): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'messageId' => $messageId
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('fields', V::in(['standard', 'include_headers'])),
                Validation::selectFilterRule(),
            ),
            $queryParams
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $messageId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnMessage']);
    }

    /**
     * Send a Message
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/messages/send
     *
     * @param string $grantId - ID of the grant to access
     * @param array $params - Request params
     * @return array
     * @throws GuzzleException
     */
    public function send(string $grantId, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
            ),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(Validation::createMessageRules(), $params);

        $isDraftMessage     = !empty($params['use_draft']);

        // Draft message should be sent as form params
        // Simple message without attachment should be sent as form params
        if ($isDraftMessage || empty($params['attachments'])) {

            return $this->options
                ->getSync()
                ->setPath($grantId)
                ->setFormParams($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->post(API::LIST['sendMessage']);
        }

        $multipart = Helper::prepareMultipartRequestData($params);

        $headers = [];
        $hasMultipart = false;

        // attach multipart header data
        if (!empty($multipart)) {
            $hasMultipart = true;
            $headers['multipart'] = $multipart;
        }

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams(array_merge(
                $this->options->getAuthorizationHeader(),
                $headers
            ), $hasMultipart)
            ->post(API::LIST['sendMessage']);
    }

    /**
     * Update the attributes (folders, stars, read/unread status, and so on) for a specific email message.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/messages/-message_id-
     *
     * @param string $grantId
     * @param string $messageId
     * @param array $params
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $messageId, array $params, array $queryParams = []): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'messageId' => $messageId,
            ]
        );

        V::doValidate(Validation::updateMessageRules(), $params);

        V::doValidate(Validation::selectFilterRule(), $queryParams);

        return $this->options
            ->getSync()
            ->setPath(
                $grantId,
                $messageId
            )
            ->setQuery($queryParams)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnMessage']);
    }

    /**
     * Delete a Message
     * NOTE: Currently, Nylas does not support hard delete.
     * Instead, Nylas moves deleted email messages to the Trash folder (soft delete).
     *
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/messages/-message_id-
     *
     * @param string $grantId - ID of the grant to access
     * @param string $messageId - ID of the email message to access.
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $messageId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('messageId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
                'messageId' => $messageId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath(
                $grantId,
                urlencode($messageId)
            )
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnMessage']);
    }

    /**
     * Clean email messages
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/messages/clean
     *
     * @param string $grantId
     * @param array $params
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function cleanMessage(string $grantId, array $params, array $queryParams = []): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
            ]
        );

        V::doValidate(Validation::cleanMessageRules(), $params);

        V::doValidate(Validation::selectFilterRule(), $queryParams);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['cleanMessage']);
    }

    /**
     * Return scheduled messages
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/messages/schedules
     *
     * @param string $grantId - ID of the grant to access
     * @return array
     * @throws GuzzleException
     */
    public function listScheduleMessages(string $grantId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'   => $grantId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath(
                $grantId
            )
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['scheduleMessages']);
    }

    /**
     * Returns a specific scheduled email message. You can retrieve both sent and unsent email messages.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/messages/schedules/-scheduleId-
     *
     * @param string $grantId - ID of the grant to access
     * @param string $scheduleId - The ID of the scheduled email message that you want to retrieve.
     * @return array
     * @throws GuzzleException
     */
    public function findScheduleMessage(string $grantId, string $scheduleId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('scheduleId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'       => $grantId,
                'scheduleId'    => $scheduleId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath(
                $grantId,
                $scheduleId
            )
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnScheduleMessage']);
    }

    /**
     * Cancel a scheduled message
     * NOTE: Cancels the send schedule for a scheduled email message.
     * You can use this endpoint up to 10 seconds before the email message has reached its send_at time.
     * If you make a DELETE request less than 10 seconds before the send_at time,
     * Nylas cannot guarantee the schedule will be cancelled successfully.
     * You cannot use this endpoint to un-send an email message.
     *
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/messages/schedules/-scheduleId-
     *
     * @param string $grantId - ID of the grant to access
     * @param string $scheduleId - The ID of the send schedule you want to cancel
     * @return array
     * @throws GuzzleException
     */
    public function deleteScheduleMessage(string $grantId, string $scheduleId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('scheduleId', V::stringType()::notEmpty()),
            ),
            [
                'grantId'       => $grantId,
                'scheduleId'    => $scheduleId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath(
                $grantId,
                $scheduleId
            )
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnScheduleMessage']);
    }
}
