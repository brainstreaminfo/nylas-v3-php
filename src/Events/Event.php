<?php

namespace Nylas\Events;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;

/**
 * Events
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Events
 */
class Event
{
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Return all events
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/events
     *
     * @param string $grantId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $queryParams): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(Validation::searchEventRules(), $queryParams);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['events']);
    }

    /**
     * Create an event
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/events
     *
     * @param string $grantId
     * @param array $queryParams
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $grantId, array $queryParams, array $params): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(Validation::createEventQueryRules(), $queryParams);

        V::doValidate(Validation::createEventRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['events']);
    }

    /**
     * Return an event
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/events/-event_id-
     *
     * @param string $grantId
     * @param string $eventId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $eventId, array $queryParams): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('eventId', V::stringType()::notEmpty()),
            ),
            [
                'grantId' => $grantId,
                'eventId' => $eventId,
            ]
        );

        V::doValidate(
            V::keySet(
                V::key('calendar_id', V::stringType()::notEmpty()),
                V::keyOptional('select', V::stringType())
            ),
            $queryParams
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $eventId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnEvent']);
    }

    /**
     * Return an event
     * NOTE:
     * You cannot update events where read_only is true.
     * You cannot update events where the parent calendar's read_only field is true.
     *
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/events/-event_id-
     *
     * @param string $grantId
     * @param string $eventId
     * @param array $queryParams
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $eventId, array $queryParams, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('eventId', V::stringType()::notEmpty()),
            ),
            [
                'grantId' => $grantId,
                'eventId' => $eventId,
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('notify_participants', V::boolType()),
                V::key('calendar_id', V::stringType()::notEmpty()),
                V::keyOptional('select', V::stringType())
            ),
            $queryParams
        );

        V::doValidate(Validation::updateEventRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId, $eventId)
            ->setQuery($queryParams)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnEvent']);
    }

    /**
     * Delete an event
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/events/-event_id-
     *
     * @param string $grantId
     * @param string $eventId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $eventId, array $queryParams): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('eventId', V::stringType()::notEmpty()),
            ),
            [
                'grantId' => $grantId,
                'eventId' => $eventId,
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('notify_participants', V::boolType()),
                V::key('calendar_id', V::stringType()::notEmpty()),
            ),
            $queryParams
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $eventId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnEvent']);
    }

    /**
     * Send RSVP
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/events/-event_id-/send-rsvp
     *
     * @param string $grantId
     * @param string $eventId
     * @param array $queryParams
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function sendRsvp(string $grantId, string $eventId, array $queryParams, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('eventId', V::stringType()::notEmpty()),
            ),
            [
                'grantId' => $grantId,
                'eventId' => $eventId,
            ]
        );

        V::doValidate(
            V::key('calendar_id', V::stringType()::notEmpty()),
            $queryParams
        );

        V::doValidate(
            V::key('status', V::in(['yes', 'no', 'maybe'])),
            $params
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $eventId)
            ->setQuery($queryParams)
            ->setFormParams($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['rsvpEvent']);
    }
}
