<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class RefreshToken
    extends aGrantRequest
    implements iGrantTokenRequest
{
    protected $refreshToken;
    protected $clientSecret;


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'refresh_token';
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
        if ( null === $this->getRefreshToken() )
            throw new exMissingGrantRequestParams('Request Param "refresh_token" must Set.');


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
     * @param mixed $refreshToken
     * @return RefreshToken
     */
    function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return mixed
     */
    function getRefreshToken()
    {
        return $this->refreshToken;
    }

}
