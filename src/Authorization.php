<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Request\Command;
use Poirot\OAuth2Client\Authorization\aOAuthPlatform;
use Poirot\OAuth2Client\Authorization\PlatformRest;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Grant\Container\GrantPlugins;
use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\OAuth2Client\Interfaces\iGrantAuthorizeRequest;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;
use Poirot\OAuth2Client\Interfaces\ipGrantRequest;
use Poirot\OAuth2Client\Model\Entity\AccessTokenObject;


class Authorization
    extends aClient
    implements iClient
    , iClientOfOAuth
{
    protected $urlAuthorize;
    protected $baseUrl;
    protected $defaultScopes = [];
    protected $clientId;
    protected $clientSecret;

    /** @var GrantPlugins */
    protected $grantPlugins;


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
        $response    = $this->call(
            $this->_newCommand('GetAuthUrl', $grantParams)
        );

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

        $response = $this->call(
            $this->_newCommand('Token', $grantParams)
        );

        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        return new AccessTokenObject($r);
    }

    /**
     * Retrieve Specific Grant Type
     *
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
            'scopes'        => $this->getDefaultScopes(),
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
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


    // Options

    /**
     * Set Token OAuth Endpoint
     * @param string $baseUrl
     * @return Authorization
     */
    function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim( (string) $baseUrl, '/' );
        return $this;
    }

    /**
     * Url To OAuth Retrieve Token Endpoint
     * @return string
     */
    function getBaseUrl()
    {
        return $this->baseUrl;
    }


    // Grant Default Options

    function setClientId($clientId)
    {
        $this->clientId = (string) $clientId;
        return $this;
    }

    function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Client Secret Key
     * @param string $clientSecret
     * @return Authorization
     */
    function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Default Token Request Scopes
     * @param array $defaultScopes
     * @return Authorization
     */
    function setDefaultScopes(array $defaultScopes)
    {
        $this->defaultScopes = $defaultScopes;
        return $this;
    }

    /**
     * Default Token Scopes
     * @return []
     */
    function getDefaultScopes()
    {
        return $this->defaultScopes;
    }


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


    // Implement iClient

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    function platform()
    {
        if (! $this->platform )
            $this->platform = new PlatformRest;


        # Default Options Overriding

        $this->platform->setServerUrl(
            $this->getBaseUrl()
        );

        return $this->platform;
    }


    // ..

    protected function _newCommand($methodName, array $args = null)
    {
        $method = new Command;
        $method->setMethodName($methodName);
        $method->setArguments($args);
        return $method;
    }

    protected function _grantPlugins()
    {
        if (! $this->grantPlugins )
            $this->grantPlugins = new GrantPlugins;

        return $this->grantPlugins;
    }
}
