<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\Http\Psr\ServerRequestBridgeInPsr;
use Poirot\OAuth2Client\Assertion\aAssertToken;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessToken;


class AssertTokenAction
{
    protected $assertion;

    /**
     * AssertTokenAction constructor.
     * @param aAssertToken $assertion
     */
    function __construct(aAssertToken $assertion)
    {
        $this->assertion = $assertion;
    }

    /**
     * Assert Authorization Token From Request
     *
     * @param iHttpRequest $request
     *
     * @return iAccessToken[]
     * @throws exOAuthAccessDenied
     */
    function __invoke(iHttpRequest $request)
    {
        $token = $this->assertion->parseTokenStrFromRequest( new ServerRequestBridgeInPsr($request) );

        try
        {
            if ($token)
                $token = $this->assertion->assertToken($token);

        } catch (exOAuthAccessDenied $e) {
            // any oauth server error will set token result to false
            if ($e->getMessage() !== 'invalid_grant')
                // Something other than token invalid or expire happen;
                // its not accessDenied exception
                throw $e;

            $token = null;
        }

        return ['token' => $token];
    }
}
