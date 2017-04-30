<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\OAuth2\Server\Grant\aGrant;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Grant\Container\GrantPlugins;
use Poirot\OAuth2Client\Interfaces\iAccessToken;

// TODO Implement Grants Registry
class Authorization
    extends aClient
    implements iClient
{
    protected $urlAuthorize;
    protected $urlToken;
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
     * @param aGrantRequest $grant Using specific grant
     *
     * @return string Authorization URL
     * @throws \Exception
     */
    function attainAuthorizationUrl(aGrantRequest $grant)
    {
        if (! $grant->canRespondToAuthorize() )
            throw new \Exception(sprintf(
                'Grant (%s) Cant Respond To Authorization Request.'
                , $grant->getGrantType()
            ));


        # Build Authorize Url

        $grantParams = $grant->assertAuthorizeParameters();

        $authUrl = appendQuery(
            $this->getUrlAuthorize()
            , buildQueryString($grantParams)
        );

        return $authUrl;
    }

    /**
     * Requests an access token using a specified grant.
     *
     * @param aGrantRequest $grant
     *
     * @return iAccessToken
     * @throws \Exception
     */
    function attainAccessToken(aGrantRequest $grant)
    {
        // client_id, secret_key can send as Authorization Header Or Post Request Body
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
     * @param string $grantTypeName
     * @param array  $overrideOptions
     *
     * @return aGrant
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


        $grant = $this->_grantPlugins()->fresh($grantTypeName, $options);
        return $grant;
    }


    // Options

    /**
     * Set Authorize OAuth Endpoint
     * @param string $urlAuthorize
     * @return Authorization
     */
    function setUrlAuthorize($urlAuthorize)
    {
        $this->urlAuthorize = (string) $urlAuthorize;
        return $this;
    }

    /**
     * Url To OAuth Authorize Endpoint
     * @return string
     */
    function getUrlAuthorize()
    {
        return $this->urlAuthorize;
    }

    /**
     * Set Token OAuth Endpoint
     * @param string $urlToken
     * @return Authorization
     */
    function setUrlToken($urlToken)
    {
        $this->urlToken = (string) $urlToken;
        return $this;
    }

    /**
     * Url To OAuth Retrieve Token Endpoint
     * @return string
     */
    function getUrlToken()
    {
        return $this->urlToken;
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
        // TODO: Implement platform() method.
    }


    // ..

    protected function _grantPlugins()
    {
        if (! $this->grantPlugins )
            $this->grantPlugins = new GrantPlugins;

        return $this->grantPlugins;
    }
}
