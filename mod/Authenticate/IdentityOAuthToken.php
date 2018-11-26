<?php
namespace Module\OAuth2Client\Authenticate;

use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
use Poirot\AuthSystem\Authenticate\Identity\IdentityOfUser;
use Poirot\AuthSystem\Authenticate\Interfaces\Identity\iIdentityAccTokenAware;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentity;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentityOfUser;
use Poirot\AuthSystem\Authenticate\Interfaces\Identity\iIdentityAccTokenProvider;
use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;


class IdentityOAuthToken
    extends IdentityOfUser
    implements iIdentity
    , iIdentityOfUser
    , iIdentityAccTokenProvider
    , iIdentityAccTokenAware
{
    protected $accessToken;
    protected $assertTokenEntity;


    /**
     * Set Access Token
     *
     * @param iAccessTokenObject $accToken
     *
     * @return $this
     */
    function setAccessToken(iAccessTokenObject $accToken = null)
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
     * @return mixed
     */
    function getAssertTokenEntity()
    {
        return $this->assertTokenEntity;
    }

    /**
     * @param iAccessTokenEntity $assertTokenEntity
     * @return $this
     */
    function setAssertTokenEntity($assertTokenEntity)
    {
        $this->assertTokenEntity = $assertTokenEntity;
        return $this;
    }
}
