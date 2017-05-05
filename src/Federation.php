<?php
namespace Poirot\OAuth2Client;

use Poirot\OAuth2Client\Exception\exIdentifierExists;
use Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\OAuth2Client\Client\aOAuthPlatform;
use Poirot\OAuth2Client\Exception\exTokenMismatch;
use Poirot\OAuth2Client\Federation\aTokenProvider;
use Poirot\OAuth2Client\Federation\PlatformRest;


class Federation
    extends aClient
{
    protected $baseUrl;
    protected $platform;
    protected $tokenProvider;


    /**
     * Federation constructor.
     *
     * @param string         $baseUrl
     * @param aTokenProvider $tokenProvider
     */
    function __construct($baseUrl, aTokenProvider $tokenProvider)
    {
        $this->baseUrl  = rtrim( (string) $baseUrl, '/' );
        $this->tokenProvider = $tokenProvider;
    }


    // Federation

    /**
     * Register New User
     *
     * [code:]
     * $federation->newUser('Nazi Amiri', '123456', [
     *  'mobile' => [
     *  'country' => '+98',
     *  'number'  => '938913xxxx',
     *  ],
     * ]);
     * [/code]
     *
     * @param string $fullname
     * @param string $credential
     * @param array  $identifiers
     *
     * @return array
     * @throws exIdentifierExists When given identifier exists
     */
    function newUser($fullname, $credential, array $identifiers)
    {
        $args = [
            'fullname'   => $fullname,
            'credential' => $credential,
        ] + $identifiers;

        $response = $this->call( new Command\Register($args) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Get User Account Info By Username
     *
     * @param string $uid
     *
     * @return array
     */
    function getAccountInfoByUid($uid)
    {
        $response = $this->call( new Command\AccountInfo($uid, Command\AccountInfo::TYPE_UID) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Get User Account Info By UID
     *
     * @param string $username
     *
     * @return array
     */
    function getAccountInfoByUsername($username)
    {
        $response = $this->call( new Command\AccountInfo($username, Command\AccountInfo::TYPE_USERNAME) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Validation Confirm Registration
     *
     * When You Register An User Through Register API, in Success Response You Will Receive Validation URL.
     * each Identity Must Be Then Validated To Finalize Registration Process.
     * note: when no parameter sent it will result the current state
     *
     * [code:]
     * $federation->confirmValidation('c312cd59f57b964c79f7b71a25c8f6', [
     *  'mobile' => 3252
     * ]);
     * [/code]
     *
     * @param string $validationHash
     * @param array  $codes
     *
     * @return array
     */
    function confirmValidation($validationHash, array $codes)
    {
        $response = $this->call( new Command\Recover\Validate($validationHash, $codes) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Resend Auth Code To Medium By Identifier Type
     *
     * [code:]
     * $federation->resendAuthCode('c312cd59f57b964c79f7b71a25c8f6', 'mobile')
     * [/code]
     *
     * @param string $validationHash
     * @param string $identifierType Identifier type. exp. "email" | "mobile"
     *
     * @return array
     */
    function resendAuthCode($validationHash, $identifierType)
    {
        $response = $this->call( new Command\Recover\ResendCode($validationHash, $identifierType) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Check That Identifiers Is Given To Any User?
     *
     * @param array $identifiers
     *
     * @return array [ 'email' => true, 'mobile' => false ]
     */
    function checkIdentifierGivenToAnyUser(array $identifiers)
    {
        $response = $this->call( new Command\Members\Exists($identifiers) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }

    /**
     * Retrieve uid+basic profile from given identifier
     *
     * [code:]
     * $federation->whoisIdentifier('mobile', [
     *  'country' => '+98',
     *  'number'  => '9355497674'
     * ]);
     * [/code]
     *
     * @param string $type  Identifier type, exp. "email" | "mobile"
     * @param mixed  $value Identifier value
     *
     *
     * @return null|array [ 'uid' => (str), 'profile' => ['fullname' => (str), 'username' => (str) ] ]
     */
    function whoisIdentifier($type, $value)
    {
        $response = $this->call( new Command\Members\Whois($type, $value) );
        if ( $ex = $response->hasException() )
            throw $ex;


        if ($response->getResponseCode() === 204)
            // No Identifier with given type, value match.
            return null;

        $r = $response->expected();
        $r = $r->get('result');
        return $r;
    }


    // Implement aClient

    /**
     * Set Specific Platform
     * @param aOAuthPlatform $platform
     * @return $this
     */
    function setPlatform(aOAuthPlatform $platform)
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    protected function platform()
    {
        if (! $this->platform )
            $this->platform = new PlatformRest;


        # Default Options Overriding
        $this->platform->setServerUrl( $this->baseUrl );

        return $this->platform;
    }


    // ..

    /**
     * @override handle token renewal from server
     * 
     * @inheritdoc
     */
    protected function call(iApiCommand $command)
    {
        $recall = 1;

recall:

        if (method_exists($command, 'setToken')) {
            $token = $this->tokenProvider->getToken();
            $command->setToken($token);
        }


        $platform = $this->platform();
        $platform = $platform->withCommand($command);
        $response = $platform->send();

        if ($ex = $response->hasException()) {

            if ( $ex instanceof exTokenMismatch && $recall > 0 ) {
                // Token revoked or mismatch
                // Refresh Token
                $this->tokenProvider->exchangeToken();
                $recall--;

                goto recall;
            }

            throw $ex;
        }


        return $response;
    }
}
