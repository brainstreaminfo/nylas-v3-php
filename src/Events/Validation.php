<?php

namespace Nylas\Events;

use DateTimeZone;
use Nylas\Utilities\Validate as V;

class Validation
{
    /**
     * search contact rules
     *
     * @return V
     */
    public static function searchEventRules(): V
    {
        return V::keySet(
            V::keyOptional('limit', V::intType()::length(1, 200)),
            V::keyOptional('page_token', V::stringType()),
            V::key('calendar_id', V::stringType()::notEmpty()),
            V::keyOptional('show_cancelled', V::boolType()),
            V::keyOptional('title', V::stringType()),
            V::keyOptional('description', V::stringType()),
            V::keyOptional('ical_uid', V::stringType()),
            V::keyOptional('location', V::stringType()),
            V::keyOptional('start', V::stringType()),
            V::keyOptional('end', V::stringType()),
            V::keyOptional('master_event_id', V::stringType()),
            V::keyOptional('metadata_pair', V::stringType()),
            V::keyOptional('busy', V::boolType()),
            V::keyOptional('updated_before', V::intType()),
            V::keyOptional('updated_after', V::intType()),
            V::keyOptional('attendees', V::stringType()),
            V::keyOptional('event_type', V::in(['default', 'outOfOffice', 'focusTime', 'workingLocation'])),
            V::keyOptional('select', V::stringType()),
            V::keyOptional('expand_recurring', V::boolType())
        );
    }

    public static function createEventQueryRules(): V
    {
        return V::keySet(
            V::key('calendar_id', V::stringType()::notEmpty()),
            V::keyOptional('notify_participants', V::boolType()),
            V::keyOptional('select', V::stringType())
        );
    }

    public static function createEventRules(): V
    {
        return V::keySet(
            V::keyOptional('busy', V::boolType()),
            V::keyOptional('capacity', V::intType()),
            V::keyOptional('conferencing', self::conferenceRules()),
            V::keyOptional('description', V::stringType()::length(null, 8192)),
            V::keyOptional('hide_participants', V::boolType()),
            V::keyOptional('location', V::stringType()),
            V::keyOptional('metadata', self::metadataRules()),
            V::keyOptional('participants', self::participantsRules()),
            V::keyOptional('resources', self::resourceRules()),
            V::keyOptional('recurrence', self::recurrenceRules()),
            V::keyOptional('reminders', self::remindersRules()),
            V::keyOptional('title', V::stringType()::length(null, 1024)),
            V::keyOptional('visibility', V::in(['public', 'private'])),
            V::key('when', self::whenRules())
        );
    }

    public static function updateEventRules(): V
    {
        return V::keySet(
            V::keyOptional('busy', V::boolType()),
            V::keyOptional('capacity', V::intType()),
            V::keyOptional('conferencing', self::conferenceRules()),
            V::keyOptional('description', V::stringType()::length(null, 8192)),
            V::keyOptional('hide_participants', V::boolType()),
            V::keyOptional('location', V::stringType()),
            V::keyOptional('metadata', self::metadataRules()),
            V::keyOptional('participants', self::participantsRules()),
            V::keyOptional('resources', self::resourceRules()),
            V::keyOptional('recurrence', self::recurrenceRules()),
            V::keyOptional('reminders', self::remindersRules()),
            V::keyOptional('title', V::stringType()::length(null, 1024)),
            V::keyOptional('visibility', V::in(['public', 'private'])),
            V::keyOptional('when', self::whenRules()),
            V::keyOptional('status', V::in(['confirmed', 'cancelled', 'maybe'])),
            V::keyOptional('updated_at', V::intType())
        );
    }

    private static function whenRules(): V
    {
        // https://en.wikipedia.org/wiki/ISO_8601#Calendar_dates
        $dates = V::oneOf(V::date('Y-m'), V::date('Ymd'), V::date('Y-m-d'));

        return V::oneOf(
            // date
            V::keySet(V::keyOptional('date', $dates)),

            // date span
            V::keySet(
                V::keyOptional('end_date', $dates),
                V::keyOptional('start_date', $dates)
            ),

            // time
            V::keySet(
                V::keyOptional('time', V::timestampType()),
                V::keyOptional('timezone', V::in(DateTimeZone::listIdentifiers()))
            ),

            // timespan
            V::keySet(
                V::keyOptional('end_time', V::timestampType()),
                V::keyOptional('start_time', V::timestampType()),
                V::keyOptional('end_timezone', V::in(DateTimeZone::listIdentifiers())),
                V::keyOptional('start_timezone', V::in(DateTimeZone::listIdentifiers()))
            )
        );
    }

    private static function remindersRules(): V
    {
        return V::keySet(
            V::keyOptional('use_default', V::boolType()),
            V::keyOptional('overrides', V::arrayVal()->each(V::keySet(
                V::keyOptional('reminder_minutes', V::intType()),
                V::keyOptional('reminder_method', V::in(['popup', 'email', 'display', 'sound']))
            )))
        );
    }

    private static function recurrenceRules(): V
    {
        return V::arrayType()::each(V::stringType()::notEmpty());
    }

    private static function resourceRules(): V
    {
        return V::arrayVal()->each(V::keySet(
            V::key('email', V::email()),
            V::keyOptional('name', V::stringType())
        ));
    }

    private static function conferenceRules(): V
    {
        $autoCreate = V::keySet(
            V::key('provider', V::in(['Google Meet', 'Zoom Meeting', 'Microsoft Teams'])),
            V::key('autocreate', V::objectType())
        );

        $webEx = V::keySet(
            V::key('provider', V::equals('WebEx')),
            V::key('details', V::keySet(
                V::keyOptional('password', V::stringType()),
                V::keyOptional('pin', V::stringType()),
                V::keyOptional('url', V::stringType())
            ))
        );

        $zoomMeeting = V::keySet(
            V::key('provider', V::equals('Zoom Meeting')),
            V::key('details', V::keySet(
                V::keyOptional('meeting_code', V::stringType()),
                V::keyOptional('password', V::stringType()),
                V::keyOptional('url', V::stringType())
            ))
        );

        $goToMeeting = V::keySet(
            V::key('provider', V::equals('GoToMeeting')),
            V::key('details', V::keySet(
                V::keyOptional('meeting_code', V::stringType()),
                V::keyOptional('phone', V::arrayVal()->each()),
                V::keyOptional('url', V::stringType())
            ))
        );

        $googleMeet = V::keySet(
            V::key('provider', V::equals('Google Meet')),
            V::key('details', V::keySet(
                V::keyOptional('phone', V::arrayVal()->each()),
                V::keyOptional('pin', V::stringType()),
                V::keyOptional('url', V::stringType())
            ))
        );

        return V::oneOf($autoCreate, $webEx, $zoomMeeting, $goToMeeting, $googleMeet);
    }

    private static function metadataRules(): V
    {
        return V::callback(static function ($input): bool {
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

    private static function participantsRules(): V
    {
        return V::arrayVal()->each(V::keySet(
            V::keyOptional('comment', V::stringType()),
            V::key('email', V::email()),
            V::keyOptional('name', V::stringType()),
            V::keyOptional('phone_number', V::phone())
        ));
    }
}
