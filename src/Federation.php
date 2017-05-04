<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\OAuth2Client\Client\aOAuthPlatform;
use Poirot\OAuth2Client\Exception\exTokenMismatch;
use Poirot\OAuth2Client\Federation\aTokenProvider;
use Poirot\OAuth2Client\Federation\Command\Members\Exists;
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
     * Check That Identifiers Is Given To Any User?
     *
     * @param array $identifiers
     *
     * @return array [ 'email' => true, 'mobile' => false ]
     */
    function checkIdentifierGivenToAnyUser(array $identifiers)
    {
        $response = $this->call( new Exists($identifiers) );
        if ( $ex = $response->hasException() )
            throw $ex;

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
