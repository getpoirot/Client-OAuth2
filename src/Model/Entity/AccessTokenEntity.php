<?php
namespace Poirot\OAuth2Client\Model\Entity;

use Poirot\Std\Struct\DataOptionsOpen;
use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;


class AccessTokenEntity
    extends DataOptionsOpen
    implements iAccessTokenEntity
{
    protected $identifier;
    protected $clientIdentifier;
    protected $expiryDateTime;
    protected $scopes = array();
    protected $ownerIdentifier;


    /**
     * Token Identifier
     *
     * @return string
     */
    function __toString()
    {
        return (string) $this->getIdentifier();
    }

    /**
     * Unique Token Identifier
     *
     * @return string|int
     */
    function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set Token Identifier
     * 
     * @param string|int $identifier
     * 
     * @return $this
     */
    function setIdentifier($identifier)
    {
        $this->identifier = (string) $identifier;
        return $this;
    }

    /**
     * Client Identifier That Token Issued To
     *
     * @return string|int
     */
    function getClientIdentifier()
    {
        return $this->clientIdentifier;
    }

    /**
     * Set Client Identifier That Token Issued To
     * 
     * @param string|int $clientIdentifier
     * 
     * @return $this
     */
    function setClientIdentifier($clientIdentifier)
    {
        $this->clientIdentifier = (string) $clientIdentifier;
        return $this;
    }

    /**
     * Get the token's expiry date time
     *
     * @return \DateTime
     */
    function getDateTimeExpiration()
    {
        return $this->expiryDateTime;
    }

    /**
     * Set Expiry DateTime
     * 
     * @param \DateTime $expiryDateTime
     * 
     * @return $this
     */
    function setDateTimeExpiration(\DateTime $expiryDateTime)
    {
        $this->expiryDateTime = $expiryDateTime;
        return $this;
    }

    /**
     * Get Issued Scopes
     * 
     * @return string[]
     */
    function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set Issued Scopes
     * 
     * @param string[] $scopes
     * 
     * @return $this
     */
    function setScopes($scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * Add Scope To Current Scopes
     *
     * @param string $scope
     * 
     * @return $this
     */
    function setScope($scope)
    {
        $scopes = array_merge($this->getScopes(), array( (string) $scope) );
        $this->setScopes($scopes);
        return $this;
    }

    /**
     * Resource Owner Of Token
     *
     * @return string|int|null
     */
    function getOwnerIdentifier()
    {
        return $this->ownerIdentifier;
    }

    /**
     * Set Resource Owner Identifier If Has
     *
     * @param string|int $ownerIdentifier
     * 
     * @return $this
     */
    function setOwnerIdentifier($ownerIdentifier)
    {
        $this->ownerIdentifier = $ownerIdentifier;
        return $this;
    }

    /**
     * Is Token Issued To Resource Owner?
     * @ignore
     *
     * @return boolean
     */
    function isIssuedToResourceOwner()
    {
        return null !== $this->getOwnerIdentifier();
    }
}
