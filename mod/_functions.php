<?php
namespace Module\OAuth2Client\Assertion
{
    use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
    use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
    use Poirot\OAuth2Client\Exception\exOAuthScopeRequired;
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
        if ( !$token instanceof iAccessTokenEntity && !$token instanceof iAccessTokenObject) {
            if ($token === null)
                throw new exOAuthAccessDenied('Token has to be send.');

            throw new exOAuthAccessDenied('Token is revoked or mismatch.');
        }


        if ($tokenCondition)
        {
            ## Check Have Resource Owner
            #
            $owner = $token->getOwnerIdentifier();
            if (
                isset($tokenCondition->mustHaveOwner)
                && $tokenCondition->mustHaveOwner
                && empty($owner)
            )
                throw new exOAuthAccessDenied('Token Not Granted To Resource Owner; But Have To.');


            ## Check Scopes Validity
            #
            if (
                isset($tokenCondition->scopes)
                && ! empty($tokenCondition->scopes)
            ) {
                $tokenScopes = $token->getScopes();
                $reqScopes   = $tokenCondition->scopes;


                $t = microtime(true);

                // Sort token scopes to achieve better finding performance
                //
                ksort($tokenScopes);
                $tScopeSorted = [];
                foreach ($tokenScopes as $scope) {
                    $tChr = strtolower($scope[0]);
                    if (! isset($tScopeSorted[$tChr]) )
                        $tScopeSorted[$tChr] = [];

                    $tScopeSorted[$tChr][] = $scope;
                }

                // Check for require scope
                //
                foreach ($reqScopes as $rScope)
                {
                    $tChr = strtolower($rScope[0]);
                    if (! isset($tScopeSorted[$tChr]) )
                        break;

                    foreach ($tScopeSorted[$tChr] as $ts) {
                        if ( false !== strstr($ts, $rScope) )
                            return;
                    }
                }


                throw new exOAuthScopeRequired('Scope Needed But Not Fulfilled By Token.');
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
