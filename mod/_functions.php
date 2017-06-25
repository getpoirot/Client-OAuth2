<?php
namespace Module\OAuth2Client\Assertion
{
    use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
    use Poirot\OAuth2Client\Interfaces\iAccessToken;


    /**
     * Validate Asserted Token Entity (Retrieved From Server),
     * against given condition
     *
     * @param iAccessToken|null $token
     * @param object            $tokenCondition
     *
     * @throws exOAuthAccessDenied
     */
    function validateAccessToken(iAccessToken $token = null, $tokenCondition)
    {
        if (! $token instanceof iAccessToken )
            throw new exOAuthAccessDenied('Token is revoked or mismatch.');


        if ($tokenCondition) {
            # Check Resource Owner
            if ( $tokenCondition->mustHaveOwner && empty($token->getOwnerIdentifier()) )
                throw new exOAuthAccessDenied('Token Not Granted To Resource Owner; But Have To.');

            # Check Scopes
            if (! empty($tokenCondition->scopes) ) {
                /**
                 * TODO check scopes
                 * @link https://github.com/phPoirot/Client-OAuth2/issues/1
                 */
                kd(array_intersect($tokenCondition->scopes, $token->getScopes()));
            }
        }
    };

    function funcValidateAccessToken($tokenCondition)
    {
        return function (iAccessToken $token = null) use ($tokenCondition) {
            validateAccessToken($token, $tokenCondition);
        };
    }
}
