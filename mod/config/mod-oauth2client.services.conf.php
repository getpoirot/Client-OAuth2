<?php
/**
 * @see \Poirot\Ioc\Container\BuildContainer
 */

use Poirot\Ioc\Container\BuildContainer;
use Module\OAuth2Client\Services\ServiceOAuthClient;

return array(
    'services' => array(
        'OAuthClient' => [ BuildContainer::INST => ServiceOAuthClient::class ]
            + \Poirot\Std\catchIt(function () {
                if (false === $c = \Poirot\Config\load('oauth2client/server_credential'))
                    throw new \Exception('Config (oauth2client/server_credential) not loaded.');

                return $c->value;
            })
        ,
    ),
);
