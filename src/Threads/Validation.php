<?php

namespace Nylas\Threads;

use Nylas\Utilities\Validate as V;

class Validation
{
    public static function getBaseRules(): V
    {
        return V::keySet(
            V::keyOptional('limit', V::intType()::length(1, 50)),
            V::keyOptional('page_token', V::stringType()),
            V::keyOptional('subject', V::stringType()),
            V::keyOptional('any_email', V::arrayType()::each(V::email())),
            V::keyOptional('to', V::stringType()),
            V::keyOptional('from', V::stringType()),
            V::keyOptional('from', V::stringType()),
            V::keyOptional('cc', V::stringType()),
            V::keyOptional('bcc', V::stringType()),
            V::keyOptional('in', V::stringType()),
            V::keyOptional('in', V::stringType()),
            V::keyOptional('unread', V::boolType()),
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('latest_message_before', V::intType()),
            V::keyOptional('latest_message_after', V::intType()),
            V::keyOptional('has_attachment', V::boolType()),
            V::keyOptional('search_query_native', V::stringType()),
            V::keyOptional('select', V::stringType())
        );
    }
}
