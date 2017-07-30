<?php
namespace Module\OAuth2Client\Authenticate;

use Poirot\AuthSystem\Authenticate\Identifier\aIdentifierHttp;
use Poirot\AuthSystem\Authenticate\Interfaces\iIdentity;
use Poirot\Http\Psr\ServerRequestBridgeInPsr;
use Poirot\OAuth2Client\Assertion\aAssertToken;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\Std\Hydrator\HydrateGetters;


class IdentifierHttpToken
    extends aIdentifierHttp
{
    /** @var aAssertToken */
    protected $assertion;
    protected $_c_parsedToken;

    /**
     * Can Recognize Identity?
     *
     * note: never check remember flag
     *   the user that authenticated with
     *   Remember Me must recognized when
     *   exists.
     *
     * @return boolean
     */
    function canRecognizeIdentity()
    {
        $r = $this->_parseTokenFromRequest();
        return ($r !== null);
    }

    /**
     * Attain Identity Object From Signed Sign
     * exp. session, extract from authorize header,
     *      load lazy data, etc.
     *
     * !! called when user is signed in to retrieve user identity
     *
     * note: almost retrieve identity data from cache or
     *       storage that store user data. ie. session
     *
     * @see withIdentity()
     * @return iIdentity|\Traversable|null Null if no change need
     */
    protected function doRecognizedIdentity()
    {
        $token = $this->_parseTokenFromRequest();

        try
        {
            if ($token) {
                $itoken = $this->assertion->assertToken($token);
                $token  = new IdentityOAuthToken( new HydrateGetters($itoken) );
            }

        } catch (exOAuthAccessDenied $e) {
            // any oauth server error will set token result to false
            if ($e->getMessage() !== 'invalid_grant')
                // Something other than token invalid or expire happen;
                // its not accessDenied exception
                throw $e;

            $token = null;
        }

        return $token;
    }

    /**
     * Login Authenticated User
     *
     * - Sign user in environment and server
     *   exp. store in session, store data in cache
     *        sign user token in header, etc.
     *
     * @throws \Exception no identity defined
     * @return $this
     */
    function signIn()
    {
        throw new \Exception('SignIn Method for Token Authentication can`t implemented by mean.');
    }

    /**
     * Logout Authenticated User
     *
     * - it must destroy sign
     *   ie. destroy session or invalidate token in storage
     *
     * - destroy identity (immutable)
     *
     * @return void
     */
    function signOut()
    {
        $this->request()->headers()->del('Authorization');
    }


    // Options:

    function setTokenAssertion(aAssertToken $tokenAssertion)
    {
        $this->assertion = $tokenAssertion;
        return $this;
    }


    // ..

    /**
     * Get Default Identity Instance
     * that Signed data load into
     *
     * @return iIdentity
     */
    protected function _newDefaultIdentity()
    {
        return new IdentityOAuthToken();
    }

    /**
     * Parse Token From Request
     *
     * @return null|string
     */
    private function _parseTokenFromRequest()
    {
        if ($this->_c_parsedToken)
            // From Cached
            return $this->_c_parsedToken;


        $this->_c_parsedToken = $r = $this->assertion->parseTokenStrFromRequest(
            new ServerRequestBridgeInPsr( $this->request() )
        );

        return $r;
    }
}
