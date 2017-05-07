<?php
namespace Module\OAuth2Client\Services;

use Poirot\Application\aSapi;
use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2Client\Client;
use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;
use Poirot\Std\Struct\DataEntity;


class ServiceOAuthClient
    extends aServiceContainer
{
    const CONF = 'conf.oauthclient.service';


    /**
     * Create Service
     *
     * @return iClientOfOAuth
     */
    function newService()
    {
        $conf = $this->_attainConf();

        $baseUrl      = $conf['base_url'];
        $clientID     = $conf['client_id'];
        $clientSecret = @$conf['client_secret'];
        $scopes       = (isset($conf['scopes'])) ? $conf['scopes'] : [];

        return new Client($baseUrl, $clientID, $clientSecret, $scopes);
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
