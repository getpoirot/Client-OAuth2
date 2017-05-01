<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Interfaces\iGrantAuthorizeRequest;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


class AuthorizeCode
    extends aGrantRequest
    implements iGrantAuthorizeRequest
    , iGrantTokenRequest
{
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
        return 'authorization_code';
    }

    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertAuthorizeParams()
    {
        # Assert Params

        if ( null === $this->getClientId() )
            throw new exMissingGrantRequestParams('Request Param "client_id" must Set.');


        # Build Request Params

        $params = $this->__toArray();

        unset($params['code']);
        unset($params['grant_type']);
        unset($params['client_secret']);
        return $params;
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

        if ( null === $this->getCode() )
            throw new exMissingGrantRequestParams('Request Param "code" must Set.');

        if ( null === $this->getClientId() || null === $this->getClientSecret() )
            throw new exMissingGrantRequestParams('Request Param "client_id" & "client_secret" must Set.');


        # Build Request Params

        $params = $this->__toArray();
        unset($params['response_type']);

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
            $this->state = \Poirot\Std\generateUniqueIdentifier(10);

        return $this->state;
    }

    function getResponseType()
    {
        return 'code';
    }

    /**
     * @param string $code
     * @return AuthorizeCode
     */
    function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    function getCode()
    {
        return $this->code;
    }
}
