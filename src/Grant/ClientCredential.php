<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class ClientCredential
    extends aGrantRequest
    implements iGrantTokenRequest
{
    const GRANT_TYPE = 'client_credentials';

    protected $code;
    protected $state;
    protected $redirectUri;
    protected $clientSecret;


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return self::GRANT_TYPE;
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
}
