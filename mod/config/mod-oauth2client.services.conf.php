<?php
/**
 * @see \Poirot\Ioc\Container\BuildContainer
 */
use Module\OAuth2Client\Services\ServiceOAuthClient;
use Poirot\OAuth2Client\Grant\Container\GrantPlugins;

return [
    'services' => [
        'OAuthClient' => new \Poirot\Ioc\instance(
            ServiceOAuthClient::class,
            \Poirot\Std\catchIt(function () {
                if (false === $c = \Poirot\Config\load(__DIR__.'/oauth2client/client_credential'))
                    throw new \Exception('Config (oauth2client/client_credential) not loaded.');

                return $c->value;
            })
        ),

        // Federation OAuth
        //
        'OAuthFederate' => new \Poirot\Ioc\instance(
            \Poirot\OAuth2Client\Federation::class,
            \Poirot\Std\catchIt(function () {
                if (false === $c = \Poirot\Config\load(__DIR__.'/oauth2client/federation'))
                    throw new \Exception('Config (oauth2client/federation) not loaded.');

                return $c->value;
            })
        ),

        // ...

        'TokenProvider' => new \Poirot\Ioc\instance(
            \Poirot\OAuth2Client\Federation\TokenProvider\TokenFromOAuthClient::class
            , [
                // Retrieve Client From Registered Service Named "ClientOauth"
                'client' => new \Poirot\Ioc\instance('/module/oauth2client/services/OAuthClient'),
                // Retrieve Client Credential From Within ClientOAuth Service
                // returned value will inject into construct
                'grant'  => new \Poirot\Ioc\instance(function () {
                    return \Module\OAuth2Client\Services::OAuthClient()
                        ->withGrant(GrantPlugins::CLIENT_CREDENTIALS);
                }, [':late_binding' => true])
            ]
        ),

    ],
];
