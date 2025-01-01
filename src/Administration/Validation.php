<?php

declare(strict_types=1);

namespace Nylas\Administration;

use Nylas\Utilities\API;
use Nylas\Utilities\Validate as V;

/**
 * Nylas App Administration Validations
 */
class Validation
{
    /**
     * Rule for callback url setting
     *
     * @return V
     */
    public static function callBackUrlSettingRules(): V
    {
        return V::keySet(
            V::keyOptional('origin', V::url()::notEmpty()), //js-only
            V::keyOptional('bundle_id', V::stringType()::notEmpty()), //ios -only
            V::keyOptional('app_store_id', V::stringType()::notEmpty()), //ios -only
            V::keyOptional('team_id', V::stringType()::notEmpty()), //ios -only
            V::keyOptional('package_name', V::stringType()::notEmpty()),
            V::keyOptional('sha1_certificate_fingerprint', V::stringType()::notEmpty())
        );
    }

    /**
     * auth url prepare rule
     *
     * @return V
     */
    public static function URLForAuthenticationConfigRules(): V
    {
        return V::keySet(
            V::key('client_id', V::stringType()::notEmpty()),
            V::keyOptional('provider', V::in(API::$authProviders)),
            V::key('redirect_uri', V::url()::notEmpty()),
            V::key('response_type', V::in(['code'])),
            V::keyOptional('scope', V::arrayType()::each(V::stringType()::notEmpty())),
            V::keyOptional(
                'prompt',
                V::in(['select_provider', 'detect', 'select_provider,detect', 'detect,select_provider'])
            ),
            V::keyOptional('state', V::stringType()::length(1, 256)),
            V::keyOptional('login_hint', V::email()),
            V::keyOptional('access_type', V::in(['offline', 'online'])),
            V::keyOptional('credential_id', V::stringType())
        );
    }

    /**
     * auth url prepare rule
     *
     * @return V
     */
    public static function URLForAuthenticationConfigCommonRules(): V
    {
        return V::attribute('client_id', V::stringType()::notEmpty())
            ->attribute('provider', V::in(API::$authProviders))
            ->attribute('redirect_uri', V::url())
            ->attribute('response_type', V::in(['code']))
            ->attribute('scope', V::arrayType()->each(V::stringType()::notEmpty()))
            ->attribute(
                'prompt',
                V::in(['select_provider', 'detect', 'select_provider,detect', 'detect,select_provider'])
            )
            ->attribute('state', V::stringType()::length(1, 256))
            ->attribute('login_hint', V::email())
            ->attribute('access_type', V::in([' offline', 'online']))
            ->attribute('credential_id', V::stringType()::notEmpty());
    }

    /**
     * auth url prepare rule with PKCE
     *
     * @return V
     */
    public static function URLForAuthenticationConfigWithPKCERules(): V
    {
        return V::keySet(
            V::key('client_id', V::stringType()::notEmpty()),
            V::keyOptional('provider', V::in(API::$authProviders)),
            V::key('redirect_uri', V::url()),
            V::key('response_type', V::in(['code'])),
            V::keyOptional('scope', V::arrayType()->each(V::stringType()::notEmpty())),
            V::keyOptional(
                'prompt',
                V::in(['select_provider', 'detect', 'select_provider,detect', 'detect,select_provider'])
            ),
            V::keyOptional('state', V::stringType()::length(1, 256)),
            V::keyOptional('login_hint', V::email()),
            V::keyOptional('access_type', V::in([' offline', 'online'])),
            V::keyOptional('credential_id', V::stringType()::notEmpty()),
            V::keyOptional('code_challenge', V::stringType()::notEmpty()),
            //Specifies a Base64-encoded code_verifier without padding
            V::keyOptional('code_challenge_method', V::in(['plain', 'S256']))
        );
    }

    public static function googleCreateConnectorRequestRules(): V
    {
        return V::keySet(
            V::key('provider', V::equals(API::$authProvider_google)),
            V::key('settings', V::keySet(
                V::key('client_id', V::stringType()::notEmpty()),
                V::key('client_secret', V::stringType()::notEmpty()),
                V::keyOptional('topic_name', V::stringType()::notEmpty())
            )),
            V::keyOptional('scope', V::arrayType()::each(V::stringType()))
        );
    }

    public static function microsoftCreateConnectorRequestRules(): V
    {
        return V::keySet(
            V::key('provider', V::equals(API::$authProvider_microsoft)),
            V::key('settings', V::keySet(
                V::key('client_id', V::stringType()::notEmpty()),
                V::key('client_secret', V::stringType()::notEmpty()),
                V::keyOptional('tenant', V::stringType()::notEmpty())
            )),
            V::keyOptional('scope', V::arrayType()::each(V::stringType()))
        );
    }

    public static function zoomCreateConnectorRequestRules(): V
    {
        return V::keySet(
            V::key('provider', V::equals(API::$authProvider_zoom)),
            V::key('settings', V::keySet(
                V::key('client_id', V::stringType()::notEmpty()),
                V::key('client_secret', V::stringType()::notEmpty())
            ))
        );
    }

    public static function yahooCreateConnectorRequestRules(): V
    {
        return V::keySet(
            V::key('provider', V::equals(API::$authProvider_yahoo)),
            V::key('settings', V::keySet(
                V::key('client_id', V::stringType()::notEmpty()),
                V::key('client_secret', V::stringType()::notEmpty())
            )),
            V::keyOptional('scope', V::arrayType()::each(V::stringType()))
        );
    }

    public static function generalCreateConnectorRequestRules($provider): V
    {
        return V::keySet(
            V::key('provider', V::equals($provider))
        );
    }

    public static function createCredentialRequestRules(): V
    {
        return V::oneOf(
            Validation::createGoogleCredentialRequestRules(),
            Validation::createMicrosoftCredentialRequestRules(),
            Validation::createConnectorOverrideCredentialRequestRules()
        );
    }
    public static function updateCredentialRequestRules(): V
    {
        return V::keySet(
            V::key('name', V::stringType()::notEmpty()),
            V::key('credential_data', V::oneOf(
                self::googleServiceAccountRules(),
                self::microsoftAdminConsentSettingsRules(),
                self::overwriteCredentialDataRules()
            ))
        );
    }

    public static function createMicrosoftCredentialRequestRules(): V
    {
        return V::keySet(
            V::key('name', V::stringType()::notEmpty()),
            V::key('credential_type', V::equals('adminconsent')),
            V::key('credential_data', self::microsoftAdminConsentSettingsRules())
        );
    }

    public static function createGoogleCredentialRequestRules(): V
    {
        return V::keySet(
            V::key('name', V::stringType()::notEmpty()),
            V::key('credential_type', V::equals('serviceaccount')),
            V::key('credential_data', self::googleServiceAccountRules())
        );
    }

    public static function createConnectorOverrideCredentialRequestRules(): V
    {
        return V::keySet(
            V::key('name', V::stringType()::notEmpty()),
            V::key('credential_type', V::equals('connector')),
            V::key('credential_data', self::overwriteCredentialDataRules())
        );
    }

    private static function microsoftAdminConsentSettingsRules(): V
    {
        return V::keySet(
            V::key('client_id', V::stringType()::notEmpty()),
            V::key('client_secret', V::stringType()::notEmpty()),
            V::keyOptional('tenant', V::stringType())
        );
    }

    public static function googleServiceAccountRules(): V
    {
        return V::keySet(
            V::key('private_key_id', V::stringType()::notEmpty()),
            V::key('private_key', V::stringType()::notEmpty()),
            V::key('client_email', V::stringType()::notEmpty()),
            V::keyOptional('type', V::stringType()),
            V::keyOptional('project_id', V::stringType()),
            V::keyOptional('client_id', V::stringType()),
            V::keyOptional('auth_uri', V::stringType()),
            V::keyOptional('token_uri', V::stringType()),
            V::keyOptional('auth_provider_x509_cert_url', V::stringType()),
            V::keyOptional('client_x509_cert_url', V::stringType())
        );
    }

    private static function overwriteCredentialDataRules(): V
    {
        return V::keySet(
            V::key('client_id', V::stringType()::notEmpty()),
            V::key('client_secret', V::stringType()::notEmpty())
        );
    }

    public static function UpdateApplicationRules(): V
    {
        return V::keySet(
            V::keyOptional('application_id', V::stringType()::notEmpty()),
            V::keyOptional('organization_id', V::stringType()::notEmpty()),
            V::keyOptional('region', V::stringType()::notEmpty()),
            V::keyOptional('environment', V::stringType()::notEmpty()),
            V::keyOptional('branding', V::keySet(
                V::keyOptional('name', V::stringType()::notEmpty()),
                V::keyOptional('icon_url', V::url()::notEmpty()),
                V::keyOptional('website_url', V::url()::notEmpty()),
                V::keyOptional('description', V::stringType()::notEmpty())
            )),
            V::keyOptional('hosted_authentication', (V::keySet(
                V::keyOptional('background_image_url', V::url()::notEmpty()),
                V::keyOptional('alignment', V::stringType()::notEmpty()),
                V::keyOptional('color_primary', V::stringType()::notEmpty()),
                V::keyOptional('color_secondary', V::stringType()::notEmpty()),
                V::keyOptional('title', V::stringType()::notEmpty()),
                V::keyOptional('subtitle', V::stringType()::notEmpty()),
                V::keyOptional('background_color', V::stringType()::notEmpty()),
                V::keyOptional('spacing', V::stringType()::notEmpty())
            ))),
            V::keyOptional('callback_uris', V::keySet(
                V::keyOptional('url', V::url()::notEmpty()),
                V::keyOptional('platform', V::in(API::$allowPlatforms)),
                V::keyOptional('settings',  Validation::callBackUrlSettingRules())
            ))
        );
    }

    public static function grantSearchRules(): V
    {
        return V::keySet(
            V::keyOptional('limit', V::intType()::min(1)),
            V::keyOptional('offset', V::intType()::min(0)),
            V::keyOptional('sort_by', V::in(['created_at', 'updated_at'])),
            V::keyOptional('order_by', V::in(['desc', 'asc'])),
            V::keyOptional('since', V::intType()),
            V::keyOptional('before', V::intType()),
            V::keyOptional('email', V::email()),
            V::keyOptional('grant_status', V::in(['valid', 'invalid'])),
            V::keyOptional('ip', V::stringType()),
            V::keyOptional('provider', V::in(API::$authProviders)),
            V::keyOptional('account_id', V::stringType()),
            V::keyOptional('account_ids', V::stringType())
        );
    }
}
