<?php

namespace Nylas\Calendars;

use Nylas\Utilities\Validate as V;

class Validation
{
    public static function calenderAvailabilityRules(): V
    {
        return V::keySet(
            V::key('start_time', V::timestampType()),
            V::key('end_time', V::timestampType()),
            V::key('participants', self::participantsRules()),
            V::key('duration_minutes', V::numeric()),
            V::keyOptional('interval_minutes', V::numeric()),
            V::keyOptional('round_to', V::intType()),
            V::keyOptional('availability_rules', self::availabilityRules()),
            V::keyOptional('roundTo30Minutes', V::boolType())
        );
    }

    public static function participantsRules(): V
    {
        return V::arrayVal()->each(
            V::keySet(
                V::key('email', V::email()::notEmpty()),
                V::keyOptional('calendar_ids', V::arrayVal()::each(V::stringType())),
                V::keyOptional('open_hours', self::openHoursRules())
            )
        );
    }

    public static function openHoursRules(): V
    {
        return V::arrayVal()->each(V::keySet(
            V::key('days', V::arrayType()::each(V::intType())::notEmpty()),
            V::key('timezone', V::stringType()::notEmpty()),
            V::key('start', V::stringType()::notEmpty()),
            V::key('end', V::stringType()::notEmpty()),
            V::keyOptional('exdates', V::arrayType())
        ));
    }

    public static function availabilityRules(): V
    {
        return V::keySet(
            V::keyOptional('availability_method', V::in(['collective', 'max-fairness', 'max-availability'])),
            V::keyOptional('buffer', V::keySet(
                V::key('before', V::numeric()),
                V::key('after', V::numeric())
            )),
            V::keyOptional('default_open_hours', V::arrayVal()->each(
                V::keySet(
                    V::key('days', V::arrayType()::each(V::intType())::notEmpty()),
                    V::key('timezone', V::stringType()::notEmpty()),
                    V::key('start', V::stringType()::notEmpty()),
                    V::key('end', V::stringType()::notEmpty()),
                    V::keyOptional('exdates', V::arrayType()::each(V::stringType()))
                )
            )),
            V::keyOptional('round_robin_group_id', V::stringType())
        );
    }
}
