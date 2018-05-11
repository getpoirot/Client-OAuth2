<?php
namespace Poirot\OAuth2Client\Assertion;

use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;
use Poirot\OAuth2Client\Model\Entity\AccessTokenEntity;
use Poirot\Std\Hydrator\HydrateGetters;


class AssertByInternalServer
    extends aAssertToken
{
    protected $_accessTokens;


    /**
     * AuthorizeByInternalServer constructor.
     *
     * @param $repoAccessTokens
     */
    function __construct($repoAccessTokens)
    {
        if ( interface_exists('\Poirot\OAuth2\Interfaces\Server\Repository\iRepoAccessTokens') )
            if (!$repoAccessTokens instanceof \Poirot\OAuth2\Interfaces\Server\Repository\iRepoAccessTokens)
                throw new \InvalidArgumentException();
        else if (! method_exists($repoAccessTokens, 'findByIdentifier') )
                throw new \InvalidArgumentException();

        $this->_accessTokens = $repoAccessTokens;
    }

    /**
     * Validate Authorize Token With OAuth Server
     *
     * note: implement grant extension http request
     *
     * @param string $tokenStr
     *
     * @return iAccessTokenEntity
     * @throws exOAuthAccessDenied Access Denied
     */
    function assertToken($tokenStr)
    {
        if (false === $token = $this->_accessTokens->findByIdentifier((string) $tokenStr))
            throw new exOAuthAccessDenied;


        $accessToken = new AccessTokenEntity(
            new HydrateGetters($token) );

        return $accessToken;
    }
}
