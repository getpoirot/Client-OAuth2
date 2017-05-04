<?php
namespace Poirot\OAuth2Client\Federation\TokenProvider;

use Poirot\OAuth2Client\Federation\aTokenProvider;
use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class TokenFromOAuthClient
    extends aTokenProvider
{
    protected $client;
    protected $grant;
    /** @var iAccessTokenObject */
    protected $token;
    protected $isTokenExchanged = false;


    /**
     * TokenFromPlatform constructor.
     *
     * @param iClientOfOAuth     $client
     * @param iGrantTokenRequest $grant
     */
    function __construct(iClientOfOAuth $client, iGrantTokenRequest $grant)
    {
        $this->client = $client;
        $this->grant  = $grant;

        $this->load();
    }

    /**
     * Retrieve Token
     *
     * @return iAccessTokenObject
     */
    function getToken()
    {
        if (! $this->token )
            $this->token = $this->exchangeToken();
        elseif (\Poirot\OAuth2Client\checkExpiry( $this->token->getDateTimeExpiration() ))
            $this->token = $this->exchangeToken();


        return $this->token;
    }

    /**
     * Exchange New Token
     *
     * @return iAccessTokenObject
     */
    function exchangeToken()
    {
        $this->isTokenExchanged = true;

        $token = $this->client->attainAccessToken( $this->grant );
        return $this->token = $token;
    }


    function load()
    {
        if (! file_exists($this->_getTmpFilepath()) )
            return;

        $content = file_get_contents( $this->_getTmpFilepath() );
        $token = unserialize($content);
        $this->token = $token;
    }

    function save()
    {
        file_put_contents($this->_getTmpFilepath(), serialize($this->getToken()));
    }

    // ..

    function _getTmpFilepath()
    {
        return sys_get_temp_dir().'/token_stored.dt';
    }

    function __destruct()
    {
        if ( $this->isTokenExchanged )
            $this->save();
    }
}
