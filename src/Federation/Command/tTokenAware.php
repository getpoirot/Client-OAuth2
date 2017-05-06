<?php
namespace Poirot\OAuth2Client\Federation\Command;

use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;


trait tTokenAware
{
    protected $token;


    function setToken(iAccessTokenObject $token)
    {
        $this->token = $token;
    }

    /**
     * @ignore
     * @return iAccessTokenObject
     */
    function getToken()
    {
        return $this->token;
    }
}
