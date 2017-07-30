<?php
namespace Module\OAuth2Client\Services;

use Poirot\OAuth2Client;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;

use Poirot\Ioc\Container\Service\aServiceContainer;


class ServiceOAuthClient
    extends aServiceContainer
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $scopes = [];


    /**
     * Create Service
     *
     * @return iClientOfOAuth
     */
    function newService()
    {
        $baseUrl      = $this->baseUrl;
        $clientID     = $this->clientId;
        $clientSecret = $this->clientSecret;
        $scopes       = ($this->scopes) ? $this->scopes : [];

        return new OAuth2Client\Client($baseUrl, $clientID, $clientSecret, $scopes);
    }


    // Options:

    /**
     * @param mixed $baseUrl
     */
    function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param mixed $clientId
     */
    function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @param mixed $clientSecret
     */
    function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param array $scopes
     */
    function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

}
