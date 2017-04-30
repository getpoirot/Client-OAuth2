<?php
namespace Poirot\OAuth2Client\Grant;


class ClientCredential
    extends aGrantRequest
{
    protected $clientSecret;

    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'client_credentials';
    }


    // Grant Request Parameters

    /**
     * Client Secret Key
     * @param string $clientSecret
     * @return ClientCredential
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
     * Is Required Property Full Filled?
     *
     * !! this method can override on classes that extend this
     *
     * @ignore ignore this as option
     * @return bool
     */
    function isFulfilled()
    {
        return (
                null !== $this->getClientId()
            &&  null !== $this->getClientSecret()
        );
    }
}
