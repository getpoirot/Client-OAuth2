<?php
namespace Module\OAuth2Client;

use Module\OAuth2Client\Actions\AssertTokenAction;
use Poirot\Std\Interfaces\Struct\iDataEntity;
use Poirot\Application\Interfaces\Sapi;
use Poirot\Application\Sapi\Module\ContainerForFeatureActions;
use Poirot\Ioc\Container;
use Poirot\Ioc\Container\BuildContainer;
use Poirot\Loader\Autoloader\LoaderAutoloadAggregate;
use Poirot\Loader\Autoloader\LoaderAutoloadNamespace;
use Poirot\Loader\Interfaces\iLoaderAutoload;
use Module\OAuth2Client\Actions\ServiceAssertTokenAction;


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
 *   iAccessToken|null \Module\OAuth2Client\Actions\AssertToken(ServerRequestInterface $HttpRequestPsr)
 *
 *   @see mod-oauth2client.actions.conf.php
 *
 *
 * - Set Of Functions To Check Token Validity:
 *   @see _functions.php
 *
 *
 * - Provide an Authenticator for token based authorization
 *
 *   it has an FulfillmentLazy with companion of Federation Client
 *   retrieve User Profile Into Auth. Identity.
 *
 *   @see mod-oauth2client.conf
 *   @see ServiceAuthenticatorToken
 */
class Module implements Sapi\iSapiModule
    , Sapi\Module\Feature\iFeatureModuleAutoload
    , Sapi\Module\Feature\iFeatureModuleMergeConfig
    , Sapi\Module\Feature\iFeatureModuleNestActions
    , Sapi\Module\Feature\iFeatureModuleNestServices
{
    const AUTHENTICATOR = 'oauth2client.authenticator.token';


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
     * @inheritdoc
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

    use Poirot\OAuth2Client\Federation;
    use Poirot\OAuth2Client\Interfaces\iAccessToken;
    use Psr\Http\Message\ServerRequestInterface;

    /**
     * @property ServiceAssertTokenAction $AssertToken
     *
     * @method static iAccessToken|null|AssertTokenAction AssertToken(ServerRequestInterface $HttpRequestPsr = null)
     */
    class Actions extends \IOC
    { }


    use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;

    /**
     * @method static iClientOfOAuth OAuthClient()
     * @method static Federation     OAuthFederate()
     */
    class Services extends \IOC
    { }
