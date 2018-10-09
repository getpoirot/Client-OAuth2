<?php
namespace Module\OAuth2Client\Authenticator;

use Module\OAuth2Client\Authenticate\IdentifierTokenAssertion;
use Module\Authorization\Services\ContainerAuthenticatorsCapped;

use Poirot\AuthSystem\Authenticate\Authenticator;
use Poirot\Ioc\Container\Service\aServiceContainer;


class OAuthTokenAuthenticatorPlugin
    extends aServiceContainer
{
    protected $realm = 'oauth.api.realm';


    /**
     * Create Service
     *
     * @return Authenticator
     */
    function newService()
    {
        $identifier = new IdentifierTokenAssertion([
            'request'         => $this->getRequest(),
            'response'        => $this->getResponse(),
            'token_assertion' => $this->getTokenAssertion(),
            'federation'      => $this->getOAuthFederation(),
        ]);

        $identifier->setRealm(
            $this->realm
        );


        $authenticator = new Authenticator(
            $identifier
        );

        return $authenticator;
    }


    // Options:

    function setRealm($realm)
    {
        $this->realm = (string) $realm;
        return $this;
    }


    function getRequest()
    {
        return \IOC::GetIoC()->get('HttpRequest');
    }

    function getResponse()
    {
        return \IOC::GetIoC()->get('HttpResponse');
    }

    function getTokenAssertion()
    {
        return \Module\OAuth2Client\Actions::AssertToken()
            ->assertion();
    }

    function getOAuthFederation()
    {
        return \IOC::GetIoC()->get('/module/oauth2client/services/OAuthFederate');
    }


    // ..

    /**
     * @override
     * !! Access Only In Capped Collection; No Nested Containers Here
     *
     * Get Service Container
     *
     * @return ContainerAuthenticatorsCapped
     */
    function services()
    {
        return parent::services();
    }
}
