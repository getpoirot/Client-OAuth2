<?php
namespace Poirot\OAuth2Client\Grant;

class AuthorizeCode
    extends aGrantRequest
{
    protected $state;
    protected $redirectUri;


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'authorization_code';
    }


    // Grant Request Parameters

    function setRedirectUri($redirectUri)
    {
        $this->redirectUri = (string) $redirectUri;
        return $this;
    }

    function getRedirectUri()
    {
        $this->redirectUri;
    }

    function setState($state)
    {
        $this->state = (string) $state;
        return $this;
    }

    function getState()
    {
        if (! $this->state )
            $this->state = \Poirot\Std\generateUniqueIdentifier();

        return $this->state;
    }

    function getResponseType()
    {
        return 'code';
    }
}
