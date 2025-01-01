<?php

declare(strict_types=1);

namespace Nylas\Administration;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;

use function http_build_query;
use function trim;

/**
 * Nylas Authentication
 */
class Authentication
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Build the URL for authenticating users to your application with OAuth 2.0
     *
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connect/auth
     * @see https://developer.nylas.com/docs/v3/auth/v3-scopes/?redirected=true#nylas-scopes
     *
     * @param array $params
     * @return string
     */
    public function urlForOauth2(array $params): string
    {
        $params['client_id']        = $this->options->getClientId();
        $params['access_type']      = $params['access_type'] ?? 'online';
        $params['response_type']    = 'code';

        V::doValidate(Validation::URLForAuthenticationConfigRules(), $params);

        if (!empty($params['scope'])) {
            $params['scope'] = self::prepareScopeString($params['scope']);
        }

        $query  = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $apiUrl = trim($this->options->getServer(), '/') . API::LIST['oAuthAuthorize'];

        return trim($apiUrl, '/') . '?' . $query;
    }

    /**
     * Send authorization code. An access token will return as part of the response.
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connect/token
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function exchangeCodeForToken(array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('code', V::stringType()::notEmpty()),
                V::key('redirect_uri', V::stringType()::notEmpty()),
                V::keyOptional('client_secret', V::stringType())
            ),
            $params
        );

        if (empty($params['client_secret'])) {
            $params['client_secret'] = $this->options->getApiKey();
        }

        return $this->options
            ->getSync()
            ->setFormParams(array_merge([
                'client_id'     => $this->options->getClientId(),
                'client_secret' => $params['client_secret'],
                'grant_type'    => 'authorization_code',
            ], $params))
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['oAuthToken']);
    }

    /**
     * Build the URL for authenticating users to your application with OAuth 2.0 and PKCE
     * IMPORTANT: YOU WILL NEED TO STORE THE 'secret' returned to use it inside the CodeExchange flow
     *
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connect/auth
     * @see https://developer.nylas.com/docs/v3/auth/v3-scopes/?redirected=true#nylas-scopes
     *
     * @param array $params
     * @return string
     */
    public function urlForOauth2Pkce(array $params): string
    {
        $params['client_id']        = $this->options->getClientId();
        $params['access_type']      = $params['access_type'] ?? 'online';
        $params['response_type']    = 'code';

        V::doValidate(Validation::URLForAuthenticationConfigWithPKCERules(), $params);

        if (!empty($params['scope'])) {
            $params['scope'] = self::prepareScopeString($params['scope']);
        }

        $query  = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $apiUrl = trim($this->options->getServer(), '/') . API::LIST['oAuthAuthorize'];

        return trim($apiUrl, '/') . '?' . $query;
    }

    /**
     * Exchange an authorization code for an access token
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connect/token
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function exchangeCodeForTokenWithPKCE(array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('code', V::stringType()::notEmpty()),
                V::key('redirect_uri', V::url()),
                V::key('code_verifier', V::stringType()::notEmpty()),
                V::keyOptional('client_secret', V::stringType())
            ),
            $params
        );

        if (empty($params['client_secret'])) {
            $params['client_secret'] = $this->options->getApiKey();
        }

        return $this->options
            ->getSync()
            ->setFormParams(array_merge([
                'client_id'     => $this->options->getClientId(),
                'client_secret' => $params['client_secret'],
                'grant_type'    => 'authorization_code'
            ], $params))
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['oAuthToken']);
    }

    /**
     * Refresh an access token.
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connect/token
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function refreshAccessToken(array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('refresh_token', V::stringType()::notEmpty()),
                V::keyOptional('client_secret', V::stringType())
            ),
            $params
        );

        if (empty($params['client_secret'])) {
            $params['client_secret'] = $this->options->getApiKey();
        }
        $params['client_id']    = $this->options->getClientId();
        $params['grant_type']   = 'refresh_token';

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['oAuthToken']);
    }

    /**
     * Builds the URL for admin consent authentication for Microsoft.
     *
     * @param array $params
     * @return string - The URL for admin consent authentication
     */
    public function urlForAdminConsent(array $params): string
    {
        V::doValidate(
            V::keySet(
                V::key('provider', V::equals(API::$authProvider_microsoft)),
                V::key('credential_id', V::stringType()::notEmpty())
            ),
            $params
        );

        $params['response_type'] = 'adminconsent';

        $query  = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $apiUrl = trim($this->options->getServer(), '/') . API::LIST['oAuthAuthorize'];

        return trim($apiUrl, '/') . '?' . $query;
    }

    /**
     * Revokes a single access token.
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connect/revoke
     *
     * @param array $params
     * @return bool - True if the token was revoked successfully
     * @throws GuzzleException
     */
    public function revokeAccessTokens(array $params): bool
    {
        V::doValidate(
            V::key('token', V::stringType()::notEmpty()),
            $params
        );

        $this->options
            ->getSync()
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['oAuthRevoke']);

        return true;
    }

    /**
     * Get info about a specific token based on the identifier you include. Use ID Token.
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connect/tokeninfo
     *
     * @param string $idToken
     * @return array
     * @throws GuzzleException
     */
    public function idTokenInfo(string $idToken): array
    {
        V::doValidate(V::stringType()::notEmpty(), $idToken);

        return $this->options
            ->getSync()
            ->setPath($idToken)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['oAuthTokenInfo']);
    }

    /**
     * Get info about a specific token based on the identifier you include. Use Access Token.
     * @see https://developer.nylas.com/docs/api/v3/admin/#get-/v3/connect/tokeninfo
     *
     * @param string $accessToken
     * @return array
     * @throws GuzzleException
     */
    public function accessTokenInfo(string $accessToken): array
    {
        V::doValidate(V::stringType()::notEmpty(), $accessToken);

        return $this->options
            ->getSync()
            ->setPath($accessToken)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['oAuthTokenInfo']);
    }

    /**
     * A space-delimited list of scope that identify the resources that your application may access on the end user's behalf.
     * If no scopes are set, Nylas uses the default connector scopes.
     *
     * @param array $scope
     * @return string
     */
    private static function prepareScopeString(array $scope): string
    {
        return implode(' ', $scope);
    }

    /**
     * Custom Authentication
     *
     * Manually create a grant using the Custom Authentication flow (previously called "Native Authentication").
     * If you're handling the OAuth flow in your own project, or you want to migrate existing users,
     * Custom Auth lets you provide the user's refresh_token to create a grant.
     *
     * @see https://developer.nylas.com/docs/api/v3/admin/#post-/v3/connect/custom
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function customAuthentication(array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('provider', V::in(API::$authProviders)),
                V::key('settings', V::arrayType()),
                V::keyOptional('state', V::stringType()),
                V::keyOptional('scope', V::arrayType()::each(V::stringType()))
            ),
            $params
        );

        return $this->options
            ->getSync()
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['customAuthorize']);
    }
}
