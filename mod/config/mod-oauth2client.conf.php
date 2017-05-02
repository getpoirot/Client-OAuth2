<?php
use Module\OAuth2Client\Actions\ServiceAssertTokenAction;

return [
    \Module\OAuth2Client\Module::CONF => [
        ServiceAssertTokenAction::CONF => [
            'debug_mode' => [
                // Not Connect to OAuth Server and Used Asserted Token With OwnerObject Below
                'enabled' => true,
                'token'   => [
                    'client_identifier' => 'test',
                    'owner_identifier'  => 'test',
                    'scopes'            => [
                        'test',
                        'debug',
                    ],
                ],
            ],

            'service' => new \Poirot\Ioc\instance(
                \Poirot\OAuth2Client\Assertion\AssertByRemoteServer::class
                , [
                    'oauthTokenEndpoint'  => 'http://oauth_web-server/oauth/auth/token',
                    // Basic base64(clientId:clientSecret)
                    'authorizationHeader' => 'Basic dGVzdEBkZWZhdWx0LmF4R0VjZVZDdEdxWkFkVzNyYzM0c3FidlRBU1NUWnhEOnhQV0lwbXpCSzM4TW1EUmQ=',
                ]
            ),
        ],
    ],
];
