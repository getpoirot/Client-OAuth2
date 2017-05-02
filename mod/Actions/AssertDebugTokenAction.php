<?php
namespace Module\OAuth2Client\Actions;

use Poirot\OAuth2Client\Interfaces\iAccessToken;
use Poirot\OAuth2Client\Model\Entity\AccessToken;


class AssertDebugTokenAction
{
    /**
     * AssertTokenDebug constructor.
     *
     * @param $tokenSettings
     */
    function __construct($tokenSettings)
    {
        $this->tokenSettings = $tokenSettings;

    }

    /**
     * Assert Authorization Token From Request
     *
     * @return iAccessToken[]
     */
    function __invoke()
    {
        $token = new AccessToken($this->tokenSettings);
        return ['token' => $token];
    }
}
