<?php
use Module\OAuth2Client\Actions\ServiceAssertTokenAction;

return [
    \Module\OAuth2Client\Module::CONF => [

        \Module\OAuth2Client\Services\ServiceOAuthClient::CONF => [
            'base_url'      => 'http://172.17.0.1:8000/',
            'client_id'     => 'test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD',
            'client_secret' => 'xPWIpmzBK38MmDRd',

            /** @see \Poirot\OAuth2Client\Authorization */
        ],

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
                    'client' => '/module/oauth2client/services/OAuthClient',
                ]
            ),
        ],
    ],
];
