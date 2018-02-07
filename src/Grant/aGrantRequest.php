<?php
namespace Poirot\OAuth2Client\Grant;

use Poirot\OAuth2Client\Interfaces\ipGrantRequest;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Hydrator\HydrateGetters;
use Poirot\Std\Type\StdTravers;


abstract class aGrantRequest
    extends ConfigurableSetter
    implements ipGrantRequest
{
    protected $clientId;
    protected $scopes = [];


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    abstract function getGrantType();


    // Helper

    /**
     * Get Grant Request Params As Array
     *
     * @return array
     */
    function __toArray()
    {
        $params = __( new HydrateGetters($this) )
            ->setExcludeNullValues();

        $params = StdTravers::of($params)->toArray(null, true);
        return $params;
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


    // ...

    /**
     * @override ensure not throw exception
     * @inheritdoc
     */
    function with(array $options, $throwException = false)
    {
        return parent::with($options, $throwException);
    }
}
