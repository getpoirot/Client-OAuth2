<?php
namespace Module\OAuth2Client\Assertion
{
    use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
    use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;


    /**
     * Validate Asserted Token Entity (Retrieved From Server),
     * against given condition
     *
     * @param iAccessTokenEntity|null $token
     * @param object            $tokenCondition
     *
     * @throws exOAuthAccessDenied
     */
    function validateAccessToken($token = null, $tokenCondition)
    {
        if (! $token instanceof iAccessTokenEntity ) {
            if ($token === null)
                throw new exOAuthAccessDenied('Token has to be send.');

            throw new exOAuthAccessDenied('Token is revoked or mismatch.');
        }


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

    function funValidateAccessToken($tokenCondition)
    {
        return function (iAccessTokenEntity $token = null) use ($tokenCondition) {
            validateAccessToken($token, $tokenCondition);
        };
    }
}
