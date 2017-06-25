<?php
use Poirot\OAuth2Client\Model\Entity\AccessToken;

return [
    // Not Connect to OAuth Server and Used Asserted Token With OwnerObject Below
    'debug_mode'  => true,
    'debug_token' => new AccessToken([
        /** @see \Poirot\OAuth2Client\Model\Entity\AccessToken */
        'client_identifier' => 'test',
        'owner_identifier'  => 'test',
        'scopes'            => [ 'test', 'debug', ]
    ]),

    'token_assertion' => new \Poirot\Ioc\instance(
        \Poirot\OAuth2Client\Assertion\AssertByRemoteServer::class
        , [
            // Client Argument Attained From Registered Service
            'client' => new \Poirot\Ioc\instance('/module/oauth2client/services/OAuthClient'),
        ]
    ),
];
