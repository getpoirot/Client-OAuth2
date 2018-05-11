<?php
namespace Module\OAuth2Client\Authenticate;

use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
use Poirot\AuthSystem\Authenticate\Interfaces\Identity\iIdentityAccTokenAware;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentity;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentityOfUser;
use Poirot\AuthSystem\Authenticate\Interfaces\Identity\iIdentityAccTokenProvider;
use Poirot\Std\Struct\DataOptionsOpen;


class IdentityOAuthToken
    extends DataOptionsOpen
    implements iIdentity
    , iIdentityOfUser
    , iIdentityAccTokenProvider
    , iIdentityAccTokenAware
{
    protected $ownerId;
    protected $accessToken;
    protected $metaData = [];


    /**
     * Set Resource Owner Identifier If Has
     *
     * @param string|int $ownerIdentifier
     *
     * @return $this
     */
    function setOwnerId($ownerIdentifier)
    {
        $this->ownerId = $ownerIdentifier;
        return $this;
    }

    /**
     * Get User Unique Id
     *
     * @return mixed
     */
    function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set Access Token
     *
     * @param iAccessTokenObject $accToken
     *
     * @return $this
     */
    function setAccessToken(iAccessTokenObject $accToken)
    {
        $this->accessToken = $accToken;
        return $this;
    }

    /**
     * Get Access Token
     *
     * @return iAccessTokenObject
     */
    function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set Meta Data
     *
     * @param array $meta
     *
     * @return $this
     */
    function setMetaData(array $meta)
    {
        $this->metaData = $meta;
        return $this;
    }

    /**
     * Data Embed With User Identity
     *
     * @return array
     */
    function getMetaData()
    {
        return $this->metaData;
    }
}
