<?php
namespace Poirot\OAuth2Client\Assertion\RemoteServer;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Interfaces\iGrantTokenRequest;


/**
 * Request From Client Include Token:
 *
 *   POST http://authorization-server-host.com/path_to_auth/token HTTP/1.1
 *   Content-Type: application/x-www-form-urlencoded
 *   Authorization: Basic cnNfY2xpZW50OnBhc3N3b3Jk
 *
 *   grant_type=urn:poirot-framework.com:oauth2:grant_type:validate_bearer[&token=AA...ZZ][&refresh_token=AA..ZZ]
 *
 *
 * Successful Response Result In 200 OK:
 *
 *   HTTP/1.1 200 OK
 *   Content-Type: application/json;charset=UTF-8
 *
 *   {
 *     "access_token": { "resource_owner":"<resource_owner_id>", "extra":"<extra_data_of_token>" },
 *     "token_type":"Bearer",
 *     "expires_in":<time_remain_to_expire>,
 *     "scope":"<contains_token_scopes>",
 *     "client_id":"<token_client_id>"
 *   }
 *
 */
class GrantExtension
    extends aGrantRequest
    implements iGrantTokenRequest
{
    protected $token;
    protected $refreshToken;
    protected $clientSecret;


    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType()
    {
        return 'urn:poirot-framework.com:oauth2:grant_type:validate_bearer';
    }


    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertTokenParams()
    {
        # Assert Params

        if ( null === $this->getClientId() || null === $this->getClientSecret() )
            throw new exMissingGrantRequestParams('Request Param "client_id" & "client_secret" must Set.');

        if ( null === $this->getToken() && null === $this->getRefreshToken() )
            throw new exMissingGrantRequestParams('Request Param "token" or "refresh_token" must Set.');


        # Build Request Params

        $params = $this->__toArray();
        return $params;
    }


    // Grant Request Parameters

    /**
     * Client Secret Key
     * @param string $clientSecret
     * @return $this
     */
    function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $token
     * @return GrantExtension
     */
    function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $refreshToken
     * @return GrantExtension
     */
    function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return mixed
     */
    function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
