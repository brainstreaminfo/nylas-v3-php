<?php

declare(strict_types = 1);

namespace Nylas\Drafts;

use Nylas\Utilities\Validator as V;

/**
 * Nylas Draft validations
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
            V::keyOptional('select', V::stringType()),
            V::keyOptional('subject', V::stringType()),
            V::keyOptional('any_email', V::stringType()),
            V::keyOptional('to', V::stringType()),
            V::keyOptional('cc', V::stringType()),
            V::keyOptional('bcc', V::stringType()),
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('thread_id', V::boolType()),
            V::keyOptional('has_attachment', V::boolType()),
        );
    }

    public static function createDraftRules(): V
    {
        return V::keySet(
            V::keyOptional('bcc', self::messageEmailRules()),
            V::keyOptional('body', V::stringType()),
            V::keyOptional('cc', self::messageEmailRules()),
            V::keyOptional('tracking_options', self::messageTrackingOptionRules()),
            V::keyOptional('attachments', self::messageAttachmentRule()),
            V::keyOptional('reply_to', self::messageEmailRules()),
            V::keyOptional('reply_to_message_id', V::stringType()),
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('subject', V::stringType()),
            V::keyOptional('to', self::messageEmailRules()),
            V::keyOptional('from', self::messageEmailRules()),
            V::keyOptional('custom_headers', self::messageCustomHeadersRule()),
        );
    }

    private static function messageEmailRules(): V
    {
        return V::simpleArray(
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
            V::keyOptional('label', V::stringType()::length(max: 2048)),
        );
    }

    private static function messageAttachmentRule(): V
    {
        return V::simpleArray(V::keySet(
            V::keyOptional('filename', V::stringType()),
            V::keyOptional('content', V::stringType()),
            V::keyOptional('content_type', V::stringType()),
            V::keyOptional('size', V::intType()),
            V::keyOptional('content_id', V::stringType()),
            V::keyOptional('content_disposition', V::stringType()),
            V::keyOptional('is_inline', V::boolType()),
        ));
    }

    private static function messageCustomHeadersRule(): V
    {
        return V::simpleArray(V::keySet(
            V::keyOptional('name', V::stringType()),
            V::keyOptional('value', V::stringType()),
        ));
    }

    public static function updateDraftRules(): V
    {
        return V::keySet(
            V::keyOptional('bcc', self::messageEmailRules()),
            V::keyOptional('body', V::stringType()),
            V::keyOptional('cc', self::messageEmailRules()),
            V::keyOptional('attachments', self::messageAttachmentRule()),
            V::keyOptional('reply_to', self::messageEmailRules()),
            V::keyOptional('starred', V::boolType()),
            V::keyOptional('subject', V::stringType()),
            V::keyOptional('to', self::messageEmailRules()),
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
            V::keyOptional('remove_conclusion_phrases', V::boolType()),
        );
    }
}
