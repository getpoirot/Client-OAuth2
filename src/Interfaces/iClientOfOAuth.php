<?php
namespace Poirot\OAuth2Client\Interfaces;

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


}
