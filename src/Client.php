<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\AccessTokenObject;
use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
use Poirot\OAuth2Client\Client\aOAuthPlatform;
use Poirot\OAuth2Client\Client\PlatformRest;
use Poirot\OAuth2Client\Client\Command\GetAuthorizeUrl;
use Poirot\OAuth2Client\Client\Command\Token;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Grant\Container\GrantPlugins;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\OAuth2Client\Interfaces\iGrantAuthorizeRequest;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;
use Poirot\OAuth2Client\Interfaces\ipGrantRequest;


class Client
    extends aClient
    implements iClientOfOAuth
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $defaultScopes = [];

    /** @var GrantPlugins */
    protected $grantPlugins;
    protected $platform;


    /**
     * Client constructor.
     *
     * @param string $baseUrl      Server base url http://172.17.0.1/auth
     * @param string $clientId     Client ID Given by OAuth Server
     * @param string $clientSecret Client Secret
     * @param array $defaultScopes Default scopes when request token
     */
    function __construct($baseUrl, $clientId, $clientSecret = null, array $defaultScopes = [])
    {
        $this->baseUrl  = rtrim( (string) $baseUrl, '/' );
        $this->clientId = (string) $clientId;
        $this->clientSecret = $clientSecret;
        $this->defaultScopes = $defaultScopes;
    }


    // OAuth Authorization

    /**
     * Builds the authorization URL
     *
     * - look in grants available with response_type code
     * - make url from grant parameters
     *
     * @param iGrantAuthorizeRequest $grant Using specific grant
     *
     * @return string Authorization URL
     * @throws \Exception
     */
    function attainAuthorizationUrl(iGrantAuthorizeRequest $grant)
    {
        # Build Authorize Url

        $grantParams = $grant->assertAuthorizeParams();
        $response    = $this->call( new GetAuthorizeUrl($grantParams) );

        if ( $ex = $response->hasException() )
            throw $ex;

        return $response->expected();
    }

    /**
     * Requests an access token using a specified grant.
     *
     * @param iGrantTokenRequest $grant
     *
     * @return iAccessTokenObject
     * @throws \Exception
     */
    function attainAccessToken(iGrantTokenRequest $grant)
    {
        // client_id, secret_key can send as Authorization Header Or Post Request Body
        $grantParams = $grant->assertTokenParams();

        $response = $this->call( new Token($grantParams) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        return new AccessTokenObject($r);
    }

    /**
     * Retrieve Specific Grant Type
     *
     * - inject default client configuration within grant object
     *
     * example code:
     *
     * $auth->withGrant(
     *  GrantPlugins::AUTHORIZATION_CODE
     *  , ['state' => 'custom_state'] )
     *
     * @param string|ipGrantRequest $grantTypeName
     * @param array                 $overrideOptions
     *
     * @return aGrantRequest
     */
    function withGrant($grantTypeName, array $overrideOptions = [])
    {
        $options = [
            'scopes'        => $this->defaultScopes,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        if (! empty($overrideOptions) )
            $options = array_merge($options, $overrideOptions);


        if ($grantTypeName instanceof ipGrantRequest) {
            $grant = clone $grantTypeName;
            $grant->with($grant::parseWith($options));
        } else {
            $grant = $this->_grantPlugins()->fresh($grantTypeName, $options);
        }

        return $grant;
    }


    // Implement aClient

    /**
     * Set Specific Platform
     * @param aOAuthPlatform $platform
     * @return $this
     */
    function setPlatform(aOAuthPlatform $platform)
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    protected function platform()
    {
        if (! $this->platform )
            $this->platform = new PlatformRest;


        # Default Options Overriding
        $this->platform->setServerUrl( $this->baseUrl );

        return $this->platform;
    }


    // ..

    protected function _grantPlugins()
    {
        if (! $this->grantPlugins )
            $this->grantPlugins = new GrantPlugins;

        return $this->grantPlugins;
    }
}
