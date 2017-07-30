<?php
namespace Module\OAuth2Client\Services\Authenticate;

use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2Client\Federation\TokenProvider\TokenFromOAuthClient;


class ServiceIdentityProviderFederation
    extends aServiceContainer
{
    protected $oauthFederationAddress = 'http://127.0.0.1/';


    /**
     * Create Service
     *
     * @return mixed
     */
    function newService()
    {
        $authClient = \Module\OAuth2Client\Services::OAuthClient();
        $federation = new \Poirot\OAuth2Client\Federation(
            $this->oauthFederationAddress
            , new TokenFromOAuthClient($authClient, $authClient->withGrant('client_credential'))
        );


        return new IdentityProviderFederation($federation);
    }


    // Options:

    /**
     * Set OAuth Server Federation Address
     * @param $serverAddress
     * @return $this
     */
    function setFederationAddress($serverAddress)
    {
        $this->oauthFederationAddress = (string) $serverAddress;
        return $this;
    }
}
