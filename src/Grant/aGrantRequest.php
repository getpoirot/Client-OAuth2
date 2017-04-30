<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\Std\Struct\aDataOptions;


abstract class aGrantRequest
    extends aDataOptions
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
     * Is Required Property Full Filled?
     * @ignore ignore this as option
     *
     * !! this method can override on classes that extend this
     *
     * @param null|string $property_key
     *
     * @return bool
     */
    function isFulfilled($property_key = null)
    {
        return parent::isFulfilled($property_key);
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
}
