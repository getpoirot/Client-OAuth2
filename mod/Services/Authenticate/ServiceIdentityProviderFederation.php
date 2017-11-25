<?php
namespace Module\OAuth2Client\Services\Authenticate;

use Poirot\Ioc\Container\Service\aServiceContainer;


class ServiceIdentityProviderFederation
    extends aServiceContainer
{
    /**
     * Create Service
     *
     * @return mixed
     */
    function newService()
    {
        $federation = \Module\OAuth2Client\Services::OAuthFederate();
        return new IdentityProviderFederation($federation);
    }
}
