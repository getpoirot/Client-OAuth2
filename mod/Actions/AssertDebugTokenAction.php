<?php
namespace Module\OAuth2Client\Actions;

use Poirot\OAuth2Client\Interfaces\iAccessToken;
use Poirot\OAuth2Client\Model\Entity\AccessToken;


class AssertDebugTokenAction
{
    /**
     * AssertTokenDebug constructor.
     *
     * @param array|iAccessToken $token
     */
    function __construct($token)
    {
        if (! $token instanceof iAccessToken )
            $token = new AccessToken($token);

        $this->token = $token;
    }

    /**
     * Assert Authorization Token From Request
     *
     * @return iAccessToken[]
     */
    function __invoke()
    {
        return ['token' => $this->token];
    }
}
