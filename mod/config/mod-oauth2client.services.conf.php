<?php
/**
 *
 * @see \Poirot\Ioc\Container\BuildContainer
 */
use Poirot\Ioc\Container\BuildContainer;

return array(
    'services' => array(
        'OAuthClient' => \Module\OAuth2Client\Services\ServiceOAuthClient::class,
    ),
);
