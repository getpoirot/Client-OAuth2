<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Application\aSapi;
use Poirot\Http\Psr\ServerRequestBridgeInPsr;
use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2\Resource\Validation\aAuthorizeToken;
use Poirot\OAuth2\Server\Exception\exOAuthServer;
use Poirot\OAuth2\Server\Response\Error\DataErrorResponse;
use Poirot\Std\Struct\DataEntity;


class ServiceAssertTokenAction
    extends aServiceContainer
{
    const CONF = 'ServiceAssertToken';


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

        }


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
        $config = $config->get(\Module\OAuth2Client\Module::CONF);

        $r = array();
        if (is_array($config) && isset($config[static::CONF]))
            $r = $config[static::CONF];

        return $r;
    }
}
