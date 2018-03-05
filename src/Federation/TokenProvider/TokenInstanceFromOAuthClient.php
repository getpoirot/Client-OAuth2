<?php
namespace Poirot\OAuth2Client\Federation\TokenProvider;

use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
use Poirot\ApiClient\Interfaces\Token\iTokenProvider;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class TokenInstanceFromOAuthClient
    implements iTokenProvider
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
}
