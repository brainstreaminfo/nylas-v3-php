<?php

declare(strict_types=1);

namespace Nylas\Calendars;

use function count;
use function is_array;
use function array_keys;
use function array_values;
use DateTimeZone;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Calendar
 */
class Calendar
{
    /**
     * Calendar constructor.
     *
     * @param Options $options
     */
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Returns all calendars.
     * (Not supported for IMAP)
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/calendars
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $params = []): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(
            V::keySet(
                V::keyOptional('limit', V::intType()::length(1, 200)),
                V::keyOptional('page_token', V::stringType()),
                V::keyOptional('metadata_pair', V::arrayType()),
                V::keyOptional('select', V::stringType()),
            ),
            $params
        );

        return $this->options
                ->getSync()
                ->setPath($grantId)
                ->setQuery($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->get(API::LIST['calendars']);
    }

    /**
     * Create a calendar.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/calendars
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $grantId, array $params): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(
            V::keySet(
                V::key('name', V::stringType()::notEmpty()),
                V::keyOptional('timezone', V::in(DateTimeZone::listIdentifiers())),
                V::keyOptional('location', V::stringType()::notEmpty()),
                V::keyOptional('description', V::stringType()::notEmpty()),
                V::keyOptional('metadata', self::metadataRules()),
            ),
            $params
        );

        return $this->options
                ->getSync()
                ->setPath($grantId)
                ->setFormParams($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->post(API::LIST['calendars']);
    }

    /**
     * Returns a calendar by ID.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/calendars/-calendar_id-
     * 
     * @param string $grantId
     * @param string $calendarId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $calendarId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('calendarId', V::stringType()::notEmpty())
            ),
            [
                'grantId'       => $grantId,
                'calendarId'    => $calendarId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $calendarId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnCalendar']);
    }

    /**
     * Update a calendar
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/calendars/-calendar_id-
     *
     * @param string $grantId
     * @param string $calendarId
     * @param array  $params
     *
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $calendarId, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('calendarId', V::stringType()::notEmpty())
            ),
            [
                'grantId'       => $grantId,
                'calendarId'    => $calendarId
            ]
        );

        V::doValidate(
            V::keySet(
                V::key('name', V::stringType()::notEmpty()),
                V::keyOptional('timezone', V::in(DateTimeZone::listIdentifiers())),
                V::keyOptional('location', V::stringType()::notEmpty()),
                V::keyOptional('metadata', self::metadataRules()),
                V::keyOptional('description', V::stringType()::notEmpty()),
                V::keyOptional('hexColor', V::stringType()::notEmpty()),
                V::keyOptional('hexForegroundColor', V::stringType()::notEmpty()),
            ),
            $params
        );
        
        return $this->options
            ->getSync()
            ->setPath($grantId, $calendarId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnCalendar']);
    }

    /**
     * Deletes the specified calendar
     * NOTE: You cannot delete the primary calendar associated with an account ("is_primary": true).
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/calendars/-calendar_id-
     *
     * @param string $grantId
     * @param mixed $calendarId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, mixed $calendarId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('calendarId', V::stringType()::notEmpty())
            ),
            [
                'grantId'       => $grantId,
                'calendarId'    => $calendarId
            ]
        );

        return $this->options
        ->getSync()
        ->setPath($grantId, $calendarId)
        ->setHeaderParams($this->options->getAuthorizationHeader())
        ->delete(API::LIST['crudOnCalendar']);
    }

    /**
     * Check calendar free or busy status.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/calendars/free-busy
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function getFreeOrBusySchedule(string $grantId, array $params = []): array
    {
        V::doValidate(
            V::keySet(
                V::key('start_time', V::timestampType()),
                V::key('end_time', V::timestampType()),
                V::key('emails', V::simpleArray(V::email()))
            ),
            $params
        );

        return $this->options
                ->getSync()
                ->setPath($grantId)
                ->setFormParams($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->post(API::LIST['calendarSchedule']);
    }

    /**
     * Check multiple calendars to find available time slots for a single meeting.
     * It checks the provider's primary calendar.
     *
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/calendars/availability
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function findAvailability(array $params = []): array
    {
        V::doValidate(Validation::calenderAvailabilityRules(), $params);

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['calendarAvailability']);
    }

    /**
     * get metadata array rules
     * @see https://developer.nylas.com/docs/api/metadata/#keep-in-mind
     *
     * @return V
     */
    private static function metadataRules(): V
    {
        return V::callback(static function (mixed $input): bool {
            if (!is_array($input) || count($input) > 50) {
                return false;
            }

            $keys = array_keys($input);
            $isOk = V::each(V::stringType()::length(1, 40))->validate($keys);

            if (!$isOk) {
                return false;
            }

            // https://developer.nylas.com/docs/api/metadata/#delete-metadata
            return V::each(V::stringType()::length(0, 500))->validate(array_values($input));
        });
    }
}
