<?php
namespace Module\OAuth2Client\Actions;

use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;
use Poirot\OAuth2Client\Model\Entity\AccessTokenEntity;


class AssertDebugTokenAction
{
    /**
     * AssertTokenDebug constructor.
     *
     * @param array|iAccessTokenEntity $token
     */
    function __construct($token)
    {
        if (! $token instanceof iAccessTokenEntity )
            $token = new AccessTokenEntity($token);

        $this->token = $token;
    }

    /**
     * Assert Authorization Token From Request
     *
     * @return iAccessTokenEntity[]
     */
    function __invoke()
    {
        return ['token' => $this->token];
    }
}
