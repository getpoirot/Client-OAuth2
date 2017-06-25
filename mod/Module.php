<?php
namespace Module\OAuth2Client;

use Module\OAuth2Client\Actions\ServiceAssertTokenAction;
use Poirot\Application\Interfaces\Sapi;
use Poirot\Application\Sapi\Module\ContainerForFeatureActions;
use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\Ioc\Container;
use Poirot\Ioc\Container\BuildContainer;
use Poirot\Loader\Autoloader\LoaderAutoloadAggregate;
use Poirot\Loader\Autoloader\LoaderAutoloadNamespace;
use Poirot\Loader\Interfaces\iLoaderAutoload;


/**
 * - Provide an OAuthClient as a Service:
 *   to obtain access token grants..
 *
 *   iClientOfOAuth \Module\OAuth2Client\Services::OAuthClient()
 *
 *   @see mod-oauth2client.services.conf.php
 *
 *
 * - Token Assertion Action:
 *   parse token from http request and connect to server for
 *   token expiration check.
 *
 *   has a debug mode action that can be mocked as real assertion one.
 *
 *   @see mod-oauth2client.actions.conf.php
 *
 *
 * - Set Of Functions To Check Token Validity:
 *
 *   @see _functions.php
 *
 */
class Module implements Sapi\iSapiModule
    , Sapi\Module\Feature\iFeatureModuleAutoload
    , Sapi\Module\Feature\iFeatureModuleNestActions
    , Sapi\Module\Feature\iFeatureModuleNestServices
{
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
        $conf = \Poirot\Config\load(__DIR__ . '/config/mod-oauth2client.services', true);
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
        return \Poirot\Config\load(__DIR__ . '/config/mod-oauth2client.actions', true);
    }
}


    /**
     * @property ServiceAssertTokenAction $AssertToken
     *
     * @method static array AssertToken(iHttpRequest $request)
     */
    class Actions extends \IOC
    { }


    use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;

    /**
     * @method static iClientOfOAuth OAuthClient()
     */
    class Services extends \IOC
    { }
