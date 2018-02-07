<?php
namespace Poirot\OAuth2Client\Grant\Extension;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class GrantSingleSignIn
    extends aGrantRequest
    implements iGrantTokenRequest
{
    protected $mobile;
    protected $clientSecret;



    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'onetime_code';
    }


    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertTokenParams()
    {
        ## Assert Params
        #
        if ( null === $this->getClientId() || null === $this->getClientSecret() )
            throw new exMissingGrantRequestParams('Request Param "client_id" & "client_secret" must Set.');


        // TODO assert for: mobile and code cant be combined together; one achieve code another for token


        ## Build Request Params
        #
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
     * Set Mobile
     * @param mixed $mobile
     * @return $this
     */
    function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    function getMobile()
    {
        return $this->mobile;
    }
}
