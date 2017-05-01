<?php
namespace Poirot\OAuth2Client\Assertion;

use Poirot\OAuth2\Model\AccessToken;
use Poirot\OAuth2\Server\Exception\exOAuthServer;
use Poirot\OAuth2\Server\Grant\GrantExtensionTokenValidation;
use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;


/**
 * Validate Authorize By Registered Extension Grant
 * it will send http request of grant type match with
 * related registered extension in oauth server
 *
 * @see GrantExtensionTokenValidation
 */
class AuthorizeByRemoteServer
    extends aAssertToken
{
    protected $endpointToken;
    protected $authorization;
    protected $grantType;


    /**
     * AuthorizeByRemoteServer constructor.
     *
     * @param string $oauthTokenEndpoint  OAuth server Token endpoint; exp. http://auth/token
     * @param string $authorizationHeader Stringify Authorization Header; exp. Authorization: Basic uyyu=
     * @param string $grantType           GrantType extension registered name
     */
    function __construct($oauthTokenEndpoint, $authorizationHeader, $grantType = GrantExtensionTokenValidation::TYPE_GRANT)
    {
        $this->endpointToken = $oauthTokenEndpoint;
        $this->authorization = $authorizationHeader;
        $this->grantType     = $grantType;
    }

    /**
     * Validate Authorize Token With OAuth Server
     *
     * note: implement grant extension http request
     *
     * @param string $token
     *
     * @return iAccessTokenObject
     * @throws exOAuthServer
     */
    function assertToken($token)
    {
        $result = $this->_sendByCurl($token);


        # Extract Result Data:
        if (! $result = json_decode($result))
            throw new \RuntimeException(sprintf(
                'Unexpected Result From Authorization Server; giveback: "%s".'
                , \Poirot\Std\flatten($result)
            ));

        if (isset($result->error)) {
            if ($result->error == 'invalid_grant')
                throw exOAuthServer::accessDenied();

            throw exOAuthServer::serverError($result->error.': '.$result->error_description);
        }

        if (!$extra  = json_decode($result->access_token))
            throw new \RuntimeException('Mismatch Token Response Structure Data; cant parse extra.');

        $result = \Poirot\Std\toArrayObject($result);
        $extra  = \Poirot\Std\toArrayObject($extra);

        $result = array_merge($result, $extra);
        unset($result['access_token']);

        /*
         * [
         *    [scope] => general
         *    [token_type] => Bearer
         *    [expires_in] => 3251
         *    [client_id] => 57b96ddd3be2ba000f64d001
         *    [resource_owner] => 58344249e1682
         * ]
         */

        $exprDateTime = __( new \DateTime() )
            ->add( new \DateInterval(sprintf('PT%sS', $result['expires_in'])) );

        $token = new AccessToken;
        $token
            ->setDateTimeExpiration($exprDateTime)
            ->setClientIdentifier($result['client_id'])
            ->setOwnerIdentifier($result['resource_owner'])
            ->setScopes(explode(' ', $result['scope']))
        ;

        return $token;
    }

    /**
     * Send Token Over Wire By Curl
     * @param $token
     * @return mixed
     * @throws exOAuthServer
     */
    protected function _sendByCurl($token)
    {
        # Connect To Remote Server and Retrieve Token Request Result as Extension:
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, trim($this->endpointToken));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS
            , http_build_query(array(
                // form data
                'grant_type' => $this->grantType,
                'token'      => $token,
            ))
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER
            , array(
                'Authorization: '.$this->authorization,
                'Connection: close'
            )
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec ($ch);
        if ($err = curl_error($ch))
            throw new \RuntimeException(sprintf(
                'Error while connecting to Authorization Server at (%s); error: "%s".'
                , $this->endpointToken
                , $err
            ));

        curl_close ($ch);

        return $result;
    }
}
