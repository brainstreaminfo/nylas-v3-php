<?php

declare(strict_types=1);

namespace Nylas\Messages;

use Nylas\Utilities\Validate as V;
use Psr\Http\Message\StreamInterface;

/**
 * ----------------------------------------------------------------------------------
 * Nylas Message validations
 * ----------------------------------------------------------------------------------
 */
class Validation
{
    /**
     * search contact rules
     *
     * @return V
     */
    public static function searchFilterRules(): V
    {
        return V::keySet(
            V::keyOptional('limit', V::intType()::length(1, 200)),
            V::keyOptional('page_token', V::stringType()),
            self::selectFilterRule(),
            V::keyOptional('subject', V::stringType()),
            V::keyOptional('any_email', V::stringType()),
            V::keyOptional('to', V::stringType()),
            V::keyOptional('from', V::stringType()),
            V::keyOptional('cc', V::stringType()),
            V::keyOptional('bcc', V::stringType()),
            V::keyOptional('in', V::stringType()),
            V::keyOptional('unread', V::boolType()),
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('thread_id', V::boolType()),
            V::keyOptional('received_before', V::intType()),
            V::keyOptional('received_after', V::intType()),
            V::keyOptional('has_attachment', V::boolType()),
            V::keyOptional('fields', V::in(['standard', 'include_headers'])),
            V::keyOptional('search_query_native', V::stringType())
        );
    }

    public static function createMessageRules(): V
    {
        return V::keySet(
            V::key('subject', V::stringType()::notEmpty()),
            V::key('body', V::stringType()::notEmpty()),
            V::keyOptional('from', self::messageEmailRules()),
            V::key('to', self::messageEmailRules()),
            V::keyOptional('cc', self::messageEmailRules()),
            V::keyOptional('bcc', self::messageEmailRules()),
            V::keyOptional('reply_to', self::messageEmailRules()),
            V::keyOptional('tracking_options', self::messageTrackingOptionRules()),
            V::keyOptional('send_at', V::intType()),
            V::keyOptional('reply_to_message_id', V::stringType()),
            V::keyOptional('use_draft', V::boolType()),
            V::keyOptional('attachments', self::messageAttachmentRule()),
            V::keyOptional('custom_headers', self::messageCustomHeadersRules())
        );
    }

    private static function messageEmailRules(): V
    {
        return V::arrayVal()->each(
            V::keySet(
                V::keyOptional('name', V::stringType()),
                V::key('email', V::email()::notEmpty())
            )
        );
    }

    private static function messageTrackingOptionRules(): V
    {
        return  V::keySet(
            V::keyOptional('opens', V::boolType()),
            V::keyOptional('thread_replies', V::boolType()),
            V::keyOptional('links', V::boolType()),
            V::keyOptional('label', V::stringType()::length(null, 2048))
        );
    }

    private static function messageAttachmentRule(): V
    {
        return V::arrayVal()->each(V::keySet(
            V::key('content', V::oneOf(
                V::resourceType(),
                V::stringType(),
                V::instance(StreamInterface::class)
            )),
            V::key('content_type', V::stringType()::notEmpty()),
            V::key('filename', V::stringType()::notEmpty()),
            V::keyOptional('size', V::intType()),
            V::keyOptional('content_id', V::stringType()),
            V::keyOptional('content_disposition', V::stringType()),
            V::keyOptional('is_inline', V::boolType())
        ));
    }

    private static function messageCustomHeadersRules(): V
    {
        return V::arrayVal()->each(V::keySet(
            V::keyOptional('name', V::stringType()),
            V::keyOptional('value', V::stringType())
        ));
    }

    public static function updateMessageRules(): V
    {
        return V::keySet(
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('unread', V::boolType()),
            V::keyOptional('folders', V::arrayType()::each(V::stringType()::notEmpty())),
            V::keyOptional('metadata', V::arrayVal()->each())
        );
    }

    public static function cleanMessageRules(): V
    {
        return V::keySet(
            V::keyOptional('message_id', V::arrayType()::each(V::stringType()::notEmpty())),
            V::keyOptional('ignore_links', V::boolType()),
            V::keyOptional('ignore_images', V::boolType()),
            V::keyOptional('images_as_markdown', V::boolType()),
            V::keyOptional('ignore_tables', V::boolType()),
            V::keyOptional('remove_conclusion_phrases', V::boolType())
        );
    }

    public static function selectFilterRule(): V
    {
        return V::keyOptional(
            'select',
            V::stringType()
        );
    }
}
