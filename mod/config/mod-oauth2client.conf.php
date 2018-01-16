<?php
use Module\Authorization\Services\ServiceAuthenticatorsContainer;
use Module\OAuth2Client\Services\Authenticate\ServiceIdentityProviderFederation;
use Module\OAuth2Client\Services\Authenticators\ServiceAuthenticatorToken;

if (! class_exists('\Module\Authorization\Module') )
    // Authorization Module Not Loaded/Enabled!!
    return [];


return [

    ## Authenticator:
    #
    \Module\Authorization\Module::CONF => [

        ServiceAuthenticatorsContainer::CONF => [
            'plugins_container' => [
                'services' => [
                    // Authenticators Services
                    \Module\OAuth2Client\Module::AUTHENTICATOR => [
                        ServiceAuthenticatorToken::class,
                        // Embed User Profile returned from oauth server into identity
                        // Link @user_profile
                        'identity_provider' => new \Poirot\Ioc\instance(
                            ServiceIdentityProviderFederation::class
                        )
                    ],
                ],
            ],
        ],
    ],

];

/**
 * #user_profile
 *
 * Array
 * (
 *   [identifier] => 59df418477607d32ac63
 *   [client_identifier] => test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD
 *   [date_time_expiration] => DateTime Object (
 *     [date] => 2017-07-31 06:26:33.000000
 *     [timezone_type] => 1
 *     [timezone] => +00:00
 *   )
 *   [scopes] => Array
 *   (
 *     [0] => profile
 *   )
 *  [owner_identifier] => 58f5f1b45a4eb80012793111
 *  [user] => Array
 *  (
 *    [uid] => 58f5f1b45a4eb80012793111
 *    [fullname] =>  پیام نادری (Payam Naderi)
 *    [username] => payam
 *    [email] => naderi.payam@gmail.com
 *    ...
 */
