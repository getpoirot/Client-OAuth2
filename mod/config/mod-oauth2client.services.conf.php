<?php
/**
 * @see \Poirot\Ioc\Container\BuildContainer
 *
 * ! These Services Can Be Override By Name (also from other modules).
 *   Nested in IOC here at: /module/tenderbin/services
 *
 *
 * @see \Module\OAuth2Client::getServices()
 */
return [
    'implementations' => [
        // Service named "AuthorizeToken" Must Implement this Abstraction
        'authorizeToken' => Poirot\OAuth2\Resource\Validation\aAuthorizeToken::class,
    ],
    'services' => [
        'authorizeToken' => \Module\OAuth2Client\Services\ServiceAuthorizeToken::class,
    ],
];
