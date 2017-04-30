<?php
use Module\OAuth2Client\Actions\ServiceAssertTokenAction;

return [
    \Module\OAuth2Client\Module::CONF_KEY => [
        ServiceAssertTokenAction::CONF_KEY => [
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
        ],

        // Service Used By AssertToken To Authorize Given Request
        \Module\OAuth2Client\Services\ServiceAuthorizeToken::CONF_KEY => [
            'service' => new \Poirot\Ioc\instance(
                \Poirot\OAuth2\Resource\Validation\AuthorizeByRemoteServer::class
                , [
                    'oauthTokenEndpoint'  => 'http://oauth_web-server/oauth/auth/token',
                    // Basic base64(clientId:clientSecret)
                    'authorizationHeader' => 'Basic dGVzdEBkZWZhdWx0LmF4R0VjZVZDdEdxWkFkVzNyYzM0c3FidlRBU1NUWnhEOnhQV0lwbXpCSzM4TW1EUmQ=',
                ]
            ),
        ],
    ],
];
