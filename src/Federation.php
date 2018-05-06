<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\Interfaces\Token\iTokenProvider;
use Poirot\OAuth2Client\Exception\exIdentifierExists;
use Poirot\OAuth2Client\Exception\exPasswordNotMatch;
use Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\OAuth2Client\Client\aOAuthPlatform;
use Poirot\OAuth2Client\Exception\exTokenMismatch;
use Poirot\OAuth2Client\Federation\PlatformRest;
use Poirot\Std\Interfaces\Struct\iDataEntity;


/*

// Setup OAuth Client; To Get Token From
$auth = new \Poirot\OAuth2Client\Client(
    'http://172.17.0.1:8000/'
    , 'test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD'
    , 'xPWIpmzBK38MmDRd'
);

$federation = new \Poirot\OAuth2Client\Federation(
    'http://172.17.0.1:8000/'
    , new TokenFromOAuthClient($auth, $auth->withGrant('client_credential'))
);

// Or To Access Token Owner Part :

$federation = new \Poirot\OAuth2Client\Federation(
    'http://172.17.0.1:8000/'
    , new TokenFromOAuthClient($auth, $auth->withGrant('password', [
        'username' => 'payam',
        'password' => '123456',
    ]))
);


$federation -> apiCallMethods(.. Defined as Methods below

*/


class Federation
    extends aClient
{
    protected $serverUrl;
    protected $platform;
    protected $tokenProvider;


    /**
     * Federation constructor.
     *
     * @param string         $serverUrl
     * @param iTokenProvider $tokenProvider
     */
    function __construct($serverUrl, iTokenProvider $tokenProvider)
    {
        $this->serverUrl  = rtrim( (string) $serverUrl, '/' );
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
     * @param array  $meta        Meta Embed Data
     *
     * @return array
     */
    function newUser($fullname, $credential, array $identifiers, array $meta = null)
    {
        $args = [
            'fullname'   => $fullname,
            'credential' => $credential,
            'meta'       => $meta,
        ] + $identifiers;

        $response = $this->call( new Command\Register($args) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }

    /**
     * Get User Account Info By Username
     *
     * [code:]
     * $federation->getAccountInfoByUid('58f5f1b45a4eb80012793111')
     * [/code]
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }

    /**
     * List Users Profile Info By UIDs
     *
     * @param array $uids
     *
     * @return array
     */
    function listAccountsInfoByUIDs(array $uids)
    {
        $response = $this->call( new Command\ListAccountsInfo($uids) );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }

    /**
     * Get User Account Info By UID
     *
     * [code:]
     * $federation->getAccountInfoByUid('payam')
     * [/code]
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
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
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }

    /**
     * Request For Identifier Validation
     *
     * @param mixed  $userId
     * @param string $identifierName
     *
     * @return array
     */
    function validateUserIdentifier($userId, $identifierName)
    {
        $response = $this->call( new Command\Members\ValidateUserIdentifier($userId, $identifierName) );
        if ( $ex = $response->hasException() )
            throw $ex;


        $r = $response->expected();
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }


    // Me : Token Owner Specific Endpoints
    // Token must given to an owner

    /**
     * Get Token Owner Account Information
     *
     * [code:]
     * $federation->getMyAccountInfo()
     * [/code]
     *
     * @param array $options
     *
     * @return array
     */
    function getMyAccountInfo(array $options = null)
    {
        $cmd = new Command\Me\AccountInfo;
        if ($options !== null)
            $cmd->with($cmd::parseWith($options));

        $response = $this->call( $cmd );
        if ( $ex = $response->hasException() )
            throw $ex;

        $r = $response->expected();
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }

    /**
     * Change My Password Determined By Token Owner
     *
     * [code:]
     * try {
     *  $checkExists = $federation->changeMyPassword('123123', '21123');
     * } catch (exPasswordNotMatch $e) {
     *  die ('Current Password You Entered Does Not Match On Server.');
     * }
     * [/code]
     *
     * @param $newPassword
     * @param $currentPassword
     *
     * @return bool
     * @throws exPasswordNotMatch
     */
    function changeMyPassword($newPassword, $currentPassword)
    {
        $response = $this->call( new Command\Me\ChangePassword($newPassword, $currentPassword) );
        if ( $ex = $response->hasException() )
            throw $ex;

        return true;
    }

    /**
     * Change Identifier such as Email, Mobile, etc ...
     *
     * note:
     * the flow of changing identifier(s) can follow by validation on next steps.
     *
     * @param array $changedIdentities
     *
     * @return array
     * @throws exIdentifierExists
     */
    function changeMyIdentity(array $changedIdentities)
    {
        $response = $this->call( new Command\Me\ChangeIdentity($changedIdentities) );
        if ( $ex = $response->hasException() )
            throw $ex;


        $r = $response->expected();
        $r = ($r instanceof iDataEntity) ? $r->get('result') : $r;
        return $r;
    }


    // Options

    /**
     * Set Token Provider
     *
     * @param iTokenProvider $tokenProvider
     *
     * @return $this
     */
    function setTokenProvider(iTokenProvider $tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
        return $this;
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
        $this->platform->setServerUrl( $this->serverUrl );

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


        $response = parent::call($command);

        if ($ex = $response->hasException()) {

            if ( $ex instanceof exTokenMismatch && $recall > 0 ) {
                // Token revoked or mismatch
                // Refresh Token
                // TODO Handle Errors while retrieve token (delete cache)
                try {
                    $this->tokenProvider->exchangeToken();

                } catch (\Exception $e) {
                    // Exchange Not Implemented
                }


                $recall--;

                goto recall;
            }

            throw $ex;
        }


        return $response;
    }
}
