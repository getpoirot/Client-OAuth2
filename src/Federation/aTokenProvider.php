<?php
namespace Poirot\OAuth2Client\Federation;

use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;


abstract class aTokenProvider
{
    /**
     * Get Current Token if Not Exchange New one
     *
     * @return iAccessTokenObject
     */
    abstract function getToken();

    /**
     * Exchange New Token
     *
     * @return iAccessTokenObject
     */
    abstract function exchangeToken();
}
