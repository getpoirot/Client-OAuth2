<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessToken;


class AssertToken
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
        # Retrieve Token Assertion From OAuth Resource Server
        /** @var aAssertToken $validator */
        $validator  = $this->services()->get('/module/oauth2client/services/');

        $token = $validator->parseTokenFromRequest( new ServerRequestBridgeInPsr($request) );

        try
        {
            if ($token)
                $token = $validator->assertToken($token);

        } catch (exOAuthAccessDenied $e) {
            // any oauth server error will set token result to false
            if ($e->getError()->getError() !== DataErrorResponse::ERR_INVALID_GRANT)
                // Something other than token invalid or expire happen;
                // its not accessDenied exception
                throw $e;

            $token = null;
        }

        return ['token' => $token];
    }
}
