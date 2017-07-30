<?php
return [
    'module.authorization' => [
        # ServiceAuthenticatorsContainer::CONF
        'authenticators' => [
            'plugins_container' => [
                'services' => [
                    // Authenticators Services
                    \Module\OAuth2Client\Module::AUTHENTICATOR => [
                        \Module\OAuth2Client\Services\ServiceAuthenticatorToken::class,
                        'identity_provider' => new \Poirot\Ioc\instance(
                            \Module\OAuth2Client\Services\Authenticate\ServiceIdentityProviderFederation::class
                            , [ 'federation_address' => 'http://127.0.0.1/' ]
                        )
                    ],
                ],
            ],
        ],
    ],
];
