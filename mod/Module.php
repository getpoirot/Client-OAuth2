<?php
namespace Module\OAuth2Client;

use Poirot\Application\Interfaces\Sapi;
use Poirot\Application\Sapi\Module\ContainerForFeatureActions;
use Poirot\Ioc\Container;
use Poirot\Ioc\Container\BuildContainer;
use Poirot\Loader\Autoloader\LoaderAutoloadAggregate;
use Poirot\Loader\Autoloader\LoaderAutoloadNamespace;
use Poirot\Loader\Interfaces\iLoaderAutoload;
use Poirot\Std\Interfaces\Struct\iDataEntity;


class Module implements Sapi\iSapiModule
    , Sapi\Module\Feature\iFeatureModuleAutoload
    , Sapi\Module\Feature\iFeatureModuleMergeConfig
    , Sapi\Module\Feature\iFeatureModuleNestActions
    , Sapi\Module\Feature\iFeatureModuleNestServices
{
    const CONF_KEY = 'module.oauth2client';


    /**
     * Register class autoload on Autoload
     *
     * priority: 1000 B
     *
     * @param LoaderAutoloadAggregate $baseAutoloader
     *
     * @return iLoaderAutoload|array|\Traversable|void
     */
    function initAutoload(LoaderAutoloadAggregate $baseAutoloader)
    {
        #$nameSpaceLoader = \Poirot\Loader\Autoloader\LoaderAutoloadNamespace::class;
        $nameSpaceLoader = 'Poirot\Loader\Autoloader\LoaderAutoloadNamespace';
        /** @var LoaderAutoloadNamespace $nameSpaceLoader */
        $nameSpaceLoader = $baseAutoloader->loader($nameSpaceLoader);
        $nameSpaceLoader->addResource(__NAMESPACE__, __DIR__);


        require_once __DIR__.'/_functions.php';
        require_once __DIR__.'/_ioc-facade.php';
    }

    /**
     * Register config key/value
     *
     * priority: 1000 D
     *
     * - you may return an array or Traversable
     *   that would be merge with config current data
     *
     * @param iDataEntity $config
     *
     * @return array|\Traversable
     */
    function initConfig(iDataEntity $config)
    {
        return \Poirot\Config\load(__DIR__ . '/config/mod-oauth2client');
    }

    /**
     * Get Nested Module Services
     *
     * it can be used to manipulate other registered services by modules
     * with passed Container instance as argument.
     *
     * priority not that serious
     *
     * @param Container $moduleContainer
     *
     * @return null|array|BuildContainer|\Traversable
     */
    function getServices(Container $moduleContainer = null)
    {
        $conf = \Poirot\Config\load(__DIR__ . '/config/mod-oauth2client.services');
        return $conf;
    }

    /**
     * Get Action Services
     *
     * priority: after GrabRegisteredServices
     *
     * - return Array used to Build ModuleActionsContainer
     *
     * @return array|ContainerForFeatureActions|BuildContainer|\Traversable
     */
    function getActions()
    {
        return \Poirot\Config\load(__DIR__ . '/config/mod-oauth2client.actions');
    }
}
