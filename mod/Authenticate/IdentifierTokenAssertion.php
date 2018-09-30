<?php
namespace Module\OAuth2Client\Authenticate;

use Poirot\OAuth2Client\Federation;
use Poirot\ApiClient\TokenProviderSolid;
use Poirot\Application\Exception\exAccessDenied;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentityOfUser;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\AuthSystem\Authenticate\Interfaces\Identity\iIdentityAccTokenProvider;


// TODO refresh token from oauth on recognition
class IdentifierTokenAssertion
    extends IdentifierHttpToken
{
    /** @var Federation */
    protected $federation;
    /** @var TokenProviderSolid */
    protected $_tokenProvider; // prepared federation with token provider

    protected $_c_info;
    protected $_c_federation;


    /**
     * Get Owner Identifier
     *
     * @return mixed
     */
    function getOwnerId()
    {
        if ($this->withIdentity() instanceof iIdentityOfUser)
        {
            /** @var IdentityOAuthToken|iIdentityOfUser $identity */
            $identity = $this->withIdentity();
            $userId   = $identity->getOwnerId();
        }
        else
        {
            $userInfo = $this->getAuthInfo();
            $userId   = $userInfo['user']['uid'];
        }


        return $userId;
    }


    /**
     * Retrieve User Info From OAuth Server
     *
     * @return array
     */
    function getAuthInfo()
    {
        if ($this->_c_info)
            return $this->_c_info;

        try
        {
            $info = $this->federation()->getMyAccountInfo();

        } catch (exOAuthAccessDenied $e) {
            throw new exAccessDenied($e->getMessage(), $e->getCode(), $e);
        }


        return $this->_c_info = $info;
    }


    /**
     * Access Federation Commands Of Identified User
     *
     * @return Federation
     */
    function federation()
    {
        if ($this->_c_federation)
            return $this->_c_federation;


        $federation = clone $this->federation;
        $federation->setTokenProvider(
            $this->insTokenProvider()
        );

        return $this->_c_federation = $federation;
    }


    /**
     * Token Provider Help Call API`s Behalf Of User with given token
     *
     *
     * [code]
     *   $federation = clone \Module\OAuth2Client\Services::OAuthFederate();
     *   $federation->setTokenProvider($tokenProvider);
     *
     *   $info = $federation->getMyAccountInfo();
     * [/code]
     *
     *
     * @return TokenProviderSolid
     */
    function insTokenProvider()
    {
        if ($this->_tokenProvider)
            return $this->_tokenProvider;


        /** @var IdentityOAuthToken|iIdentityAccTokenProvider $identity */
        $identity = $this->withIdentity();
        $tokenProvider = new TokenProviderSolid(
            $identity->getAccessToken()
        );

        return $tokenProvider;
    }


    // Options:

    function setFederation(Federation $federation)
    {
        $this->federation = $federation;
        return $this;
    }
}
