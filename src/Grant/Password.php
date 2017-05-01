<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class Password
    extends aGrantRequest
    implements iGrantTokenRequest
{
    protected $username;
    protected $password;
    protected $clientSecret;


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'password';
    }

    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertTokenParams()
    {
        # Assert Params
        if ( null === $this->getUsername() || null === $this->getPassword() )
            throw new exMissingGrantRequestParams('Request Param "username" & "password" must Set.');


        if ( null === $this->getClientId() || null === $this->getClientSecret() )
            throw new exMissingGrantRequestParams('Request Param "client_id" & "client_secret" must Set.');


        # Build Request Params

        $params = $this->__toArray();
        return $params;
    }


    // Grant Request Parameters

    /**
     * Client Secret Key
     * @param string $clientSecret
     * @return $this
     */
    function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $username
     * @return Password
     */
    function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $password
     * @return Password
     */
    function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    function getPassword()
    {
        return $this->password;
    }
}
