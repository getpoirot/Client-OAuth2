<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Interfaces\iAccessToken;

// TODO Implement Grants Registry
class Authorization
    extends aClient
    implements iClient
{
    protected $urlAuthorize;
    protected $urlToken;
    protected $defaultScopes = [];


    // OAuth Authorization

    /**
     * Builds the authorization URL
     *
     * - look in grants available with response_type code
     * - make url from grant parameters
     *
     * @param null|aGrantRequest $grant Using specific grant
     *
     * @return string Authorization URL
     */
    function attainAuthorizationUrl(aGrantRequest $grant = null)
    {
        // Look For Grant Registered With Authorization Code
        // Build Query Params From Grant
        // Append To BaseUrl Authorize

    }

    /**
     * Requests an access token using a specified grant.
     *
     * @param aGrantRequest $grant
     *
     * @return iAccessToken
     */
    function attainAccessToken(aGrantRequest $grant)
    {
        // TODO Implement Retrieve Token
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
}
