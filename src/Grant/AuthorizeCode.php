<?php
namespace Poirot\OAuth2Client\Grant;


use Poirot\OAuth2Client\Exception\exGrantRequestNotImplemented;
use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\Std\Hydrator\HydrateGetters;

class AuthorizeCode
    extends aGrantRequest
{
    protected $code;
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

    /**
     * Can Respond To Authorization Request
     *
     * @return bool
     */
    function canRespondToAuthorize()
    {
        return true;
    }

    /**
     * Can Respond To Access Token Request
     *
     * @return bool
     */
    function canRespondToToken()
    {
        return true;
    }

    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     * @throws exGrantRequestNotImplemented
     */
    function assertAuthorizeParameters()
    {
        $params = new HydrateGetters($this);
        $params->setExcludeNullValues();

        $params = iterator_to_array($params);
        unset($params['code']);
        unset($params['grant_type']);

        return $params;
    }

    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     * @throws exGrantRequestNotImplemented
     */
    function assertTokenParameters()
    {
        // TODO: Implement assertTokenParameters() method.
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
