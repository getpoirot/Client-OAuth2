<?php
use Module\OAuth2Client\Actions\ServiceAssertTokenAction;

return [
    \Module\OAuth2Client\Module::CONF => [

        ServiceAssertTokenAction::CONF => [
            'debug_mode' => [
                // Not Connect to OAuth Server and Used Asserted Token With OwnerObject Below
                'enabled' => true,
                'token_settings'   => [
                    'client_identifier' => 'test',
                    'owner_identifier'  => 'test',
                    'scopes'            => [
                        'test',
                        'debug',
                    ],

                    /** @see \Poirot\OAuth2Client\Model\Entity\AccessToken */
                ],
            ],

            // aAssertion Instance Or Registered Service
            'assertion_rig' => new \Poirot\Ioc\instance(
                \Poirot\OAuth2Client\Assertion\AssertByRemoteServer::class
                , [
                    // Client Argument Attained From Registered Service
                    'client' => new \Poirot\Ioc\instance('/module/oauth2client/services/OAuthClient'),
                ]
            ),
        ],
    ],
];
