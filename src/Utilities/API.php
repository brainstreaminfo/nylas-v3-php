<?php

declare(strict_types=1);

namespace Nylas\Utilities;

/**
 * Nylas RESTFul API List
 * @see https://developer.nylas.com/docs/new/#product-releases
 * @see https://developer.nylas.com/docs/new/release-notes/all/
 */
class API
{
    /**
     * Nylas common server list array
     * @see https://developer.nylas.com/docs/the-basics/platform/data-residency/
     */
    public const SERVER = [
        'us'    => 'https://api.us.nylas.com',
        'eu'    => 'https://api.eu.nylas.com'
    ];

    /**
     * nylas scheduler server list array
     * @see https://developer.nylas.com/docs/the-basics/platform/data-residency/
     */
    public const SERVER_SCHEDULER = [
        'us'    => 'https://api.schedule.nylas.com',
        'eu'    => 'https://ireland.api.schedule.nylas.com'
    ];

    /**
     * Supported providers in v3
     * @see https://developer.nylas.com/docs/dev-guide/provider-guides/supported-providers/#supported-providers-in-v3
     */
    public static $authProviders = [
        'google',
        'icloud',
        'imap',
        'microsoft',
        'virtual-calendar',
        'yahoo',
        'ews',
        'zoom'
    ];

    public static $authProvider_google           = 'google';
    public static $authProvider_icloud           = 'icloud';
    public static $authProvider_imap             = 'imap';
    public static $authProvider_microsoft        = 'microsoft';
    public static $authProvider_virtual_calendar = 'virtual-calendar';
    public static $authProvider_yahoo            = 'yahoo';
    public static $authProvider_ews              = 'ews';
    public static $authProvider_zoom             = 'zoom';

    public static $allowPlatforms = [
        'web',
        'desktop',
        'js',
        'iOS',
        'Android',
    ];

    /**
     * Nylas api list
     * @see https://developer.nylas.com/docs/api/#overview
     */
    public const LIST = [
        // Authorization
        'oAuthAuthorize'    => '/v3/connect/auth',
        'oAuthToken'        => '/v3/connect/token',
        'oAuthTokenInfo'    => '/v3/connect/tokeninfo',
        'oAuthRevoke'       => '/v3/connect/revoke',
        'customAuthorize'   => '/v3/connect/custom',

        // Grants
        'listGrants'        => '/v3/grants',
        'crudOnGrant'       => '/v3/grants/%s',
        'getCurrentGrant'   => '/v3/grants/me',

        // Applications
        'listApps'              => '/v3/applications',
        'appRedirectUris'       => '/v3/applications/redirect-uris',
        'crudOnAppRedirectUri'  => '/v3/applications/redirect-uris/%s',

        // Connectors
        'listConnectors'    => '/v3/connectors',
        'crudOnConnector'   => '/v3/connectors/%s',
        'detectConnectors'  => '/v3/providers/detect',

        // Connector credentials
        'listCredentials'   => '/v3/connectors/%s/creds',
        'crudOnCredential'  => '/v3/connectors/%s/creds/%s',

        // Calendars
        'calendars'             => '/v3/grants/%s/calendars',
        'crudOnCalendar'        => '/v3/grants/%s/calendars/%s',
        'calendarSchedule'      => '/v3/grants/%s/calendars/free-busy',
        'calendarAvailability'  => '/v3/calendars/availability',

        // Contacts
        'contacts'          => '/v3/grants/%s/contacts',
        'crudOnContact'     => '/v3/grants/%s/contacts/%s',
        'contactsGroups'    => '/v3/grants/%s/contacts/groups',

        // Messages
        'messages'              => '/v3/grants/%s/messages',
        'crudOnMessage'         => '/v3/grants/%s/messages/%s',
        'sendMessage'           => '/v3/grants/%s/messages/send',
        'cleanMessage'          => '/v3/grants/%s/messages/clean',
        'scheduleMessages'      => '/v3/grants/%s/messages/schedules',
        'crudOnScheduleMessage' => '/v3/grants/%s/messages/schedules/%s',

        // Smart compose email
        'smartCompose'      => '/v3/grants/%s/messages/smart-compose',
        'smartComposeReply' => '/v3/grants/%s/messages/%s/smart-compose',

        // Threads
        'threads'           => '/v3/grants/%s/threads',
        'crudOnThread'      => '/v3/grants/%s/threads/%s',

        // Folders (new PUT folder)
        'folders'       => '/v3/grants/%s/folders',
        'createFolder'  => '/v3/grants/%s/folders',
        'crudOnFolder'  => '/v3/grants/%s/folders/%s',

        // Drafts
        'drafts'        => '/v3/grants/%s/drafts',
        'crudOnDraft'   => '/v3/grants/%s/drafts/%s',

        // Attachments
        'attachment'            => '/v3/grants/%s/attachments/%s',
        'downloadAttachment'    => '/v3/grants/%s/attachments/%s/download',

        // Rooms
        'resource' => '/v3/grants/%s/resources',

        // Events
        'events'        => '/v3/grants/%s/events',
        'crudOnEvent'   => '/v3/grants/%s/events/%s',
        'rsvpEvent'     => '/v3/grants/{grant_id}/events/{event_id}/send-rsvp',

        // Webhooks
        'webhooks'              => '/v3/webhooks',
        'crudOnWebhook'         => '/v3/webhooks/%s',
        'rotateSecretWebhook'   => '/v3/webhooks/rotate-secret/%s',
        'mockPlayLoadWebhook'   => '/v3/webhooks/mock-payload',
    ];
}
