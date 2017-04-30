<?php
namespace Poirot\OAuth2Client\Grant\Container;

use Poirot\Ioc\Container\aContainerCapped;
use Poirot\Ioc\Container\BuildContainer;
use Poirot\Ioc\Container\Exception\exContainerInvalidServiceType;
use Poirot\Ioc\Container\Service\ServicePluginLoader;
use Poirot\Loader\LoaderMapResource;
use Poirot\OAuth2Client\Grant\aGrantRequest;
use Poirot\OAuth2Client\Grant\AuthorizeCode;
use Poirot\OAuth2Client\Grant\ClientCredential;


class GrantPlugins
    extends aContainerCapped
{
    const AUTHORIZATION_CODE = 'authorization_code';
    const CLIENT_CREDENTIALS = 'client_credentials';

    protected $_map_resolver_options = [
        self::AUTHORIZATION_CODE => AuthorizeCode::class,
        self::CLIENT_CREDENTIALS => ClientCredential::class,
    ];


    /**
     * Construct
     *
     * @param BuildContainer $cBuilder
     *
     * @throws \Exception
     */
    function __construct(BuildContainer $cBuilder = null)
    {
        $this->_attachDefaults();

        parent::__construct($cBuilder);
    }


    /**
     * Validate Plugin Instance Object
     *
     * @param mixed $pluginInstance
     *
     * @throws \Exception
     */
    function validateService($pluginInstance)
    {
        if (! is_object($pluginInstance) )
            throw new \Exception(sprintf('Can`t resolve to (%s) Instance.', $pluginInstance));

        if (!$pluginInstance instanceof aGrantRequest)
            throw new exContainerInvalidServiceType('Invalid Plugin Of Content Object Provided.');
    }


    // ..

    protected function _attachDefaults()
    {
        $service = new ServicePluginLoader([
            'resolver_options' => [
                LoaderMapResource::class => $this->_map_resolver_options
            ],
        ]);

        $this->set($service);
    }
}
