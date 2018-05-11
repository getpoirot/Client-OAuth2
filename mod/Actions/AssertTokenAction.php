<?php
namespace Module\OAuth2Client\Actions;

use Poirot\OAuth2Client\Assertion\aAssertToken;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Note:
 *
 * Usually it constructed from related service
 * @see ServiceAssertTokenAction
 *
 */
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
     * @param ServerRequestInterface $HttpRequestPsr
     *
     * @return iAccessTokenEntity|null
     */
    function __invoke(ServerRequestInterface $HttpRequestPsr = null)
    {
        if ($HttpRequestPsr === null)
            return $this;


        $token = $this->assertion->parseTokenStrFromRequest( $HttpRequestPsr );

        try
        {
            if ($token) {
                $token = $this->assertion->assertToken($token);
            }

        } catch (exOAuthAccessDenied $e) {
            // any oauth server error will set token result to false
            if ($e->getMessage() !== 'invalid_grant')
                // Something other than token invalid or expire happen;
                // its not accessDenied exception
                throw $e;


            // Invalid Token
            $token = false;
        }

        return $token;
    }

    /**
     * @return aAssertToken
     */
    function assertion()
    {
        return $this->assertion;
    }
}
