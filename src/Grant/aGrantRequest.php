<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Exception\exGrantRequestNotImplemented;
use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\Std\ConfigurableSetter;


abstract class aGrantRequest
    extends ConfigurableSetter
{
    protected $clientId;
    protected $scopes = [];


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    abstract function getGrantType();

    /**
     * Can Respond To Authorization Request
     *
     * @return bool
     */
    abstract function canRespondToAuthorize();

    /**
     * Can Respond To Access Token Request
     *
     * @return bool
     */
    abstract function canRespondToToken();

    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     * @throws exGrantRequestNotImplemented
     */
     function assertAuthorizeParameters()
     {
         throw new exGrantRequestNotImplemented;
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
        throw new exGrantRequestNotImplemented;
    }


    // Grant Request Parameters

    /**
     * @param mixed $clientId
     * @return aGrantRequest
     */
    function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Client ID Given By OAuth Server
     * @required
     * @return string
     */
    function getClientId()
    {
        return $this->clientId;
    }

    function setScopes(array $requiredScopes)
    {
        $this->scopes = $requiredScopes;
        return $this;
    }

    function getScopes()
    {
        return $this->scopes;
    }


    //

    /**
     * @override ensure not throw exception
     * @inheritdoc
     */
    function with(array $options, $throwException = false)
    {
        return parent::with($options, $throwException);
    }
}
