<?php
/**
 * @see \Poirot\Ioc\Container\BuildContainer
 */
use Module\OAuth2Client\Services\ServiceOAuthClient;

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
    ],
];
