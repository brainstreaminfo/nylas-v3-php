<?php
declare(strict_types = 1);

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Nylas\Utilities\API;

/**
 * Authentication Test
 */
class AuthenticationTest extends AbsCase
{
    /**
     * @return void
     * @throws JsonException
     */
    public function testUrlForOauth2()
    {
        $params = $this->prepareHostedAuthorization();

        $response = $this->client->Administration->Authentication->urlForOauth2($params);

        $this->assertNotEmpty($response);
        $this->assertIsString($response);
    }
    
    /**
     * @return void
     * @throws JsonException
     */
    public function testCodeExchangeToken()
    {
        $this->mockResponse([
            'access_token' => $this->faker->uuid(),
        ]);

        $data = [
            'code' => 'test-1234',
            'redirect_uri' => 'https://dashboard-v3.nylas.com/',
        ];
        $response = [];

        try {
            $response = $this->client->Administration->Authentication->exchangeCodeForToken($data);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['access_token']);
    }
    /**
     * @return void
     * @throws JsonException
     */
    public function testExchangeCodeForTokenWithPKCE()
    {
        $this->mockResponse([
            'access_token' => $this->faker->uuid(),
        ]);

        $data = [
            'code' => 'test-1234',
            'redirect_uri' => 'https://dashboard-v3.nylas.com/',
            'code_verifier' => 'test-123',
        ];
        $response = [];

        try {
            $response = $this->client->Administration->Authentication->exchangeCodeForTokenWithPKCE($data);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response['access_token']);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function testAuthenticateUserWithPKCE()
    {
        $response = $this->client->Administration->Authentication->urlForOauth2Pkce(
            $this->prepareHostedAuthorization(true)
        );
        $this->assertNotEmpty($response);
        $this->assertIsString($response);
    }

    private function prepareHostedAuthorization(bool $hasPKCE = false): array
    {
        $params = [
            'provider'              => API::$authProvider_google,
            'redirect_uri'          => 'https://dashboard-v3.nylas.com/',
            'response_type'         => 'code',
            'scope'                 => [
                'email',
                'contacts',
                'calendar',
            ],
            'prompt'                => 'select_provider',
            'state'                 => 'select_provider',
            'login_hint'            => $this->faker->email,
            'access_type'           => 'online',
        ];

        if ($hasPKCE) {
            $params = array_merge($params, [
                'code_challenge'        => base64_encode('test-123'), //base64 encoded string
                'code_challenge_method' => 'S256',
            ]);
        }

        return $params;
    }

    public function testCustomAuthentication()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid()
        ]);

        $params = [
            'provider' => API::$authProvider_virtual_calendar,
            'settings' => [
                'foo' => 'bar',
            ],
            'state' => 'abc-123-state',
            'scope' => [
                'email.read_only',
            ],
        ];

        $response = [];
        try {
            $response = $this->client->Administration->Authentication->customAuthentication($params);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }

    public function testRefreshAccessToken()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Authentication->refreshAccessToken([
                'refresh_token' => 'test-123',
                'client_secret' => 'test-123',
            ]);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }

    public function testUrlForAdminConsent()
    {
        $response = [];
        try {
            $response = $this->client->Administration->Authentication->urlForAdminConsent([
                'provider' => API::$authProvider_microsoft,
                'credential_id' => $this->faker->uuid(),
            ]);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }

    public function testRevokeAccessTokens()
    {
        $this->mockResponse([
            'request_id' => $this->faker->uuid
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Authentication->revokeAccessTokens([
                'token' => $this->faker->uuid(),
            ]);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }

    public function testIdTokenInfo()
    {
        $fakeId = $this->faker->uuid;
        $this->mockResponse([
            'request_id' => $fakeId
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Authentication->idTokenInfo($fakeId);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }

    public function testAccessTokenInfo()
    {
        $fakeId = $this->faker->uuid;
        $this->mockResponse([
            'request_id' => $fakeId
        ]);

        $response = [];
        try {
            $response = $this->client->Administration->Authentication->accessTokenInfo($fakeId);
        } catch (GuzzleException) {}

        $this->assertNotEmpty($response);
    }
}
