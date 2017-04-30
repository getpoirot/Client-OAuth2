<?php
namespace Module\OAuth2Client\Actions;

use Module\OAuth2Client\Services\IOC;
use Poirot\Application\aSapi;
use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\Http\Psr\ServerRequestBridgeInPsr;
use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2\Model\AccessToken;
use Poirot\OAuth2\Resource\Validation\aAuthorizeToken;
use Poirot\OAuth2\Server\Exception\exOAuthServer;
use Poirot\OAuth2\Server\Response\Error\DataErrorResponse;
use Poirot\Std\Struct\DataEntity;


class ServiceAssertTokenAction
    extends aServiceContainer
{
    const CONF_KEY = 'ServiceAssertToken';


    /** @var string Service Name */
    protected $name = 'assertToken';


    /**
     * Create Service
     *
     * @return callable
     */
    function newService()
    {
        $config = $this->_attainConf();


        # Check Debug Mode:

        $token  = null;

        if (isset($config['debug_mode']) && $config['debug_mode']['enabled'])
        {
            // Mock Debuging Mode
            $accToken = new AccessToken;

            $exprDateTime = __( new \DateTime() )
                ->add( new \DateInterval(sprintf('PT%sS', 1000)) );

            $token = $config['debug_mode']['token'];

            $accToken
                ->setDateTimeExpiration($exprDateTime)
                ->setClientIdentifier($token['client_identifier'])
                ->setOwnerIdentifier($token['owner_identifier'])
                ->setScopes($token['scopes'])
            ;

            $token = $accToken;
        }

        /**
         * Assert Authorization Token From Request
         *
         * @param iHttpRequest $request
         *
         * @return \Poirot\OAuth2\Interfaces\Server\Repository\iEntityAccessToken[]
         * @throws exOAuthServer
         */
        return function (iHttpRequest $request) use ($token)
        {
            if ($token)
                // Debug Mode, Token is Mocked!!
                return ['token' => $token];


            # Retrieve Token Assertion From OAuth Resource Server
            /** @var aAuthorizeToken $validator */
            $validator  = IOC::AuthorizeToken();

            $token = $validator->parseTokenFromRequest( new ServerRequestBridgeInPsr($request) );

            try
            {
                if ($token)
                    $token = $validator->assertToken($token);

            } catch (exOAuthServer $e) {
                // any oauth server error will set token result to false
                if ($e->getError()->getError() !== DataErrorResponse::ERR_INVALID_GRANT)
                    // Something other than token invalid or expire happen;
                    // its not accessDenied exception
                    throw $e;

                $token = null;
            }

            return ['token' => $token];
        };
    }


    // ..

    /**
     * Attain Merged Module Configuration
     * @return array
     */
    protected function _attainConf()
    {
        $sc     = $this->services();
        /** @var aSapi $sapi */
        $sapi   = $sc->get('/sapi');
        /** @var DataEntity $config */
        $config = $sapi->config();
        $config = $config->get(\Module\OAuth2Client\Module::CONF_KEY);

        $r = array();
        if (is_array($config) && isset($config[static::CONF_KEY]))
            $r = $config[static::CONF_KEY];

        return $r;
    }
}
