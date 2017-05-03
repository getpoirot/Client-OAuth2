<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Application\aSapi;
use Poirot\Ioc\Container\Service\aServiceContainer;
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

        if (isset($config['debug_mode']) && $config['debug_mode']['enabled'])
            // Mock Debuging Mode
            return new AssertDebugTokenAction($config['debug_mode']['token_settings']);


        # Assertion Instance From Config
        $assertion = $config['assertion_rig'];
        if ( is_string($assertion) )
            // Defined Service
            $assertion = $this->services()->get($assertion);

        $assertAction = new AssertTokenAction($assertion);
        return $assertAction;
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
