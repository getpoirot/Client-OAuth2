<?php
namespace Poirot\OAuth2Client\Assertion;

use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessToken;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Called when a Token should be Authorized by an authorization server.
 *
 */
abstract class aAssertToken
{
    /**
     * Assert Token String Identifier With OAuth Server
     *
     * - connect to server specification and validate given token
     * - ensure token expiration!
     *
     * @param string $tokenStr
     *
     * @return iAccessToken
     * @throws exOAuthAccessDenied Access Denied
     */
    abstract function assertToken($tokenStr);

    /**
     * As per the Bearer spec (draft 8, section 2) - there are three ways for a client
     * to specify the bearer token, in order of preference: Authorization Header,
     * POST and GET.
     *
     * @param ServerRequestInterface $request
     *
     * @return null|string Token
     */
    function parseTokenStrFromRequest(ServerRequestInterface $request)
    {
        return $this->parseTokenStrFromRequest($request);
    }
}
