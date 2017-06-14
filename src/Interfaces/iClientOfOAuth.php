<?php
namespace Poirot\OAuth2Client\Interfaces;

//use Poirot\ApiClient\Interfaces\Token\iAccessTokenObject;
use Poirot\OAuth2Client\Grant\aGrantRequest;


interface iClientOfOAuth
{
    /**
     * Builds the authorization URL
     *
     * - look in grants available with response_type code
     * - make url from grant parameters
     *
     * @param iGrantAuthorizeRequest $grant Using specific grant
     *
     * @return string Authorization URL
     * @throws \Exception
     */
    function attainAuthorizationUrl(iGrantAuthorizeRequest $grant);

    /**
     * Requests an access token using a specified grant.
     *
     * @param iGrantTokenRequest $grant
     *
     * @return iAccessTokenObject
     * @throws \Exception
     */
    function attainAccessToken(iGrantTokenRequest $grant);

    /**
     * Retrieve Specific Grant Type
     *
     * - inject default client configuration within grant object
     *
     * example code:
     *
     * $auth->withGrant(
     *  GrantPlugins::AUTHORIZATION_CODE
     *  , ['state' => 'custom_state'] )
     *
     * @param string|ipGrantRequest $grantTypeName
     * @param array                 $overrideOptions
     *
     * @return aGrantRequest
     */
    function withGrant($grantTypeName, array $overrideOptions = []);
}
