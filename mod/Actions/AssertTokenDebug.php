<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessToken;


class AssertTokenDebug
{
    /**
     * Assert Authorization Token From Request
     *
     * @param iHttpRequest $request
     *
     * @return iAccessToken[]
     * @throws exOAuthServer
     */
    function __invoke(iHttpRequest $request)
    {
        // Mock Debuging Mode
        $accToken = new AccessToken();

        $exprDateTime = __( new \DateTime() )
            ->add( new \DateInterval(sprintf('PT%sS', 1000)) );

        $token = $config['debug_mode']['token'];

        $accToken
            ->setIdentifier('debug_mode_'.uniqid())
            ->setDateTimeExpiration($exprDateTime)
            ->setClientIdentifier($token['client_identifier'])
            ->setOwnerIdentifier($token['owner_identifier'])
            ->setScopes($token['scopes'])
        ;

        $token = $accToken;

        if ($token)
            // Debug Mode, Token is Mocked!!
            return ['token' => $token];
    }
}
