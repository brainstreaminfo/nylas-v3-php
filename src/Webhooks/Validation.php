<?php

namespace Nylas\Webhooks;

use Nylas\Utilities\Validate as V;

/**
 * Webhooks Validation
 */
class Validation
{
    /**
     * @return V
     */
    public static function createWebhookRules(): V
    {
        return V::keySet(
            V::keyOptional('description', V::stringType()),
            V::key(
                'trigger_types',
                V::arrayType()::each(
                    V::in([
                        'calendar.created',
                        'calendar.updated',
                        'calendar.deleted',
                        'event.created',
                        'event.updated',
                        'event.deleted',
                        'grant.created',
                        'grant.updated',
                        'grant.deleted',
                        'grant.expired',
                        'message.send_success',
                        'message.send_failed',
                        'message.bounce_detected',
                        'message.created',
                        'message.opened',
                        'message.updated',
                        'contact.updated',
                        'contact.deleted',
                        'folder.created',
                        'folder.updated',
                        'folder.deleted',
                    ])
                )
            ),
            V::key('webhook_url', V::url()::notEmpty()),
            V::keyOptional('notification_email_addresses', V::arrayType()::each(V::email()))
        );
    }

    /**
     * @return V
     */
    public static function updateWebhookRules(): V
    {
        return V::keySet(
            V::keyOptional('description', V::stringType()),
            V::keyOptional(
                'trigger_types',
                V::arrayType()::each(
                    V::in([
                        'calendar.created',
                        'calendar.updated',
                        'calendar.deleted',
                        'event.created',
                        'event.updated',
                        'event.deleted',
                        'grant.created',
                        'grant.updated',
                        'grant.deleted',
                        'grant.expired',
                        'message.send_success',
                        'message.send_failed',
                        'message.bounce_detected',
                        'message.created',
                        'message.opened',
                        'message.updated',
                        'contact.updated',
                        'contact.deleted',
                        'folder.created',
                        'folder.updated',
                        'folder.deleted',
                    ])
                )
            ),
            V::keyOptional('webhook_url', V::url()),
            V::keyOptional('notification_email_addresses', V::arrayType()::each(V::email())),
            V::keyOptional('status', V::in(['active', 'pause']))
        );
    }

    /**
     * @return V
     */
    public static function mockNotificationPlayLoadRules(): V
    {
        return V::keySet(
            V::key(
                'trigger_type',
                V::in([
                    'calendar.created',
                    'calendar.updated',
                    'calendar.deleted',
                    'event.created',
                    'event.updated',
                    'event.deleted',
                    'grant.created',
                    'grant.updated',
                    'grant.deleted',
                    'grant.expired',
                    'message.send_success',
                    'message.send_failed',
                    'message.bounce_detected',
                    'message.created',
                    'message.created.truncated',
                    'message.updated',
                    'message.updated.truncated',
                    'contact.updated',
                    'contact.deleted',
                    'folder.created',
                    'folder.updated',
                    'folder.deleted',
                ])
            ),
            V::key('webhook_url', V::url()::notEmpty())
        );
    }
}
