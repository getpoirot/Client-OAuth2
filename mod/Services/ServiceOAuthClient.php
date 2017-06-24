<?php
namespace Module\OAuth2Client\Services;

use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2Client\Client;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;


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

        return new Client($baseUrl, $clientID, $clientSecret, $scopes);
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
