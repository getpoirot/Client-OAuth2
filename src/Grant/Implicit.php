<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Interfaces\iGrantAuthorizeRequest;


class Implicit
    extends aGrantRequest
    implements iGrantAuthorizeRequest
{
    const GRANT_TYPE = 'implicit';

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
    function assertAuthorizeParams()
    {
        # Assert Params

        if ( null === $this->getClientId() )
            throw new exMissingGrantRequestParams('Request Param "client_id" must Set.');


        # Build Request Params

        $params = $this->__toArray();

        unset($params['grant_type']);
        return $params;
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


    function getResponseType()
    {
        return 'token';
    }
}
