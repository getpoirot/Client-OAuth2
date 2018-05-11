<?php
namespace Poirot\OAuth2Client\Assertion;

use Poirot\ApiClient\Exceptions\exConnection;
use Poirot\OAuth2Client\Assertion\RemoteServer\GrantExtension;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Interfaces\iAccessTokenEntity;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\OAuth2Client\Model\Entity\AccessTokenEntity;
use Poirot\Std\Hydrator\HydrateGetters;


// TODO an options construct to set whether retrieve meta data included or not
/**
 * Validate Authorize By Registered Extension Grant
 * it will send http request of grant type match with
 * related registered extension in oauth server
 *
 * @see GrantExtensionTokenValidation
 */
class AssertByRemoteServer
    extends aAssertToken
{
    protected $client;


    /**
     * AuthorizeByRemoteServer constructor.
     *
     * retrieve token from server by extension registered grant.
     * it will include given token as grant parameters and send to server
     * server parse token from request sent data and check for validaty of token
     * if token is valid bind meta attribute (such as owner_id and etc.) to
     * token success response:
     *
     * { token: {"owner_id:" "fer3453s"},
     *   "expire_in": .. }
     *
     * @param iClientOfOAuth $client To retrieve token
     *
     */
    function __construct(iClientOfOAuth $client)
    {
        $this->client = $client;
    }


    /**
     * TODO Consider response variant from oauth server
     * @link https://github.com/phPoirot/Client-OAuth2/issues/2
     *
     * Assert Token String Identifier With OAuth Server
     *
     * - connect to server specification and validate given token
     * - ensure token expiration!
     *
     * @param string $tokenStr
     *
     * @return iAccessTokenEntity
     * @throws exOAuthAccessDenied Access Denied
     */
    function assertToken($tokenStr)
    {
        try {
            $tokenObject = $this->client->attainAccessToken(
                $this->client->withGrant( new GrantExtension, ['token' => $tokenStr] )
            );

        } catch (exConnection $e) {
            // Connection Error!!
            throw $e;

        } catch(\Exception $e) {
            // Catch OAuth Exceptions Only
            throw new exOAuthAccessDenied;
        }


        if (! $tokenEmbededMeta = json_decode( $tokenObject->getAccessToken(), true) )
            throw new \RuntimeException('Mismatch Token Response Structure Data; cant parse extra.');

        $result = iterator_to_array( new HydrateGetters($tokenObject) );
        $result = array_merge($result, $tokenEmbededMeta);
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

        $tokenEntity = new AccessTokenEntity;
        $tokenEntity
            ->setIdentifier($tokenStr)
            ->setDateTimeExpiration( $tokenObject->getDateTimeExpiration() )
            ->setClientIdentifier(@$result['client_id'])
            ->setOwnerIdentifier(@$result['resource_owner'])
            ->setScopes(explode(' ', @$result['scopes']))
        ;

        if (isset($result['meta']))
            $tokenEntity->setMeta( $result['meta'] );

        return $tokenEntity;
    }
}
