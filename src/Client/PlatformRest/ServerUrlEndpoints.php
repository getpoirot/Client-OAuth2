<?php
namespace Poirot\OAuth2Client\Client\PlatformRest;


class ServerUrlEndpoints
{
    protected $serverBaseUrl;
    protected $commandStr;

    /**
     * ServerUrlEndpoints constructor.
     *
     * @param $serverBaseUrl
     * @param $commandStr
     */
    function __construct($serverBaseUrl, $commandStr, $ssl = false)
    {
        $this->serverBaseUrl = (string) $serverBaseUrl;
        $this->commandStr    = (string) $commandStr;
    }

    function __toString()
    {
        return $this->_getServerHttpUrlFromCommand($this->commandStr);
    }


    // ..

    /**
     * Determine Server Http Url Using Http or Https?
     *
     * @param string $cmMethod
     *
     * @return string
     * @throws \Exception
     */
    protected function _getServerHttpUrlFromCommand($cmMethod)
    {
        $base = null;

        $cmMethod = strtolower($cmMethod);
        switch ($cmMethod) {
            case 'getauthurl':
                $base = 'auth';
                break;
            case 'token':
                $base = 'auth/token';
                break;
        }

        $serverUrl = rtrim($this->serverBaseUrl, '/');
        (! $base ) ?: $serverUrl .= '/'. rtrim($base, '/');
        return $serverUrl;
    }
}
