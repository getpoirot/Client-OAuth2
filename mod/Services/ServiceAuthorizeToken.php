<?php
namespace Module\OAuth2Client\Services;

use Poirot\Application\aSapi;
use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\Std\Struct\DataEntity;


class ServiceAuthorizeToken
    extends aServiceContainer
{
    const CONF_KEY = 'ServiceAuthorizeToken';


    /**
     * Create Service
     *
     * @return mixed
     * @throws \Exception
     */
    function newService()
    {
        $conf    = $this->_attainConf();
        $service = $conf['service'];
        if (is_string($service)) {
            // Looking For Registered Service
            if (!$this->services()->has($service))
                throw new \Exception(sprintf(
                    'Try to retrieve SmsClient Service From (%s) but not found.'
                    , $service
                ));

            $service = $this->services()->get($service);
        }


        return $service;
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
