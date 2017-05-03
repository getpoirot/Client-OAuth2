<?php
namespace Poirot\OAuth2Client;

use Poirot\ApiClient\Interfaces\iClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Interfaces\Response\iResponse;


class Federation
    implements iClient
{
    /**
     * Execute Request
     *
     * - prepare/validate transporter with platform
     * - build expression via method/params with platform
     * - send expression as request with transporter
     *    . build response with platform
     * - return response
     *
     * @param iApiCommand $command Server Exec Method
     *
     * @throws \Exception
     *
     * throws Exception when $method Object is null
     *
     * @return iResponse
     */
    function call(iApiCommand $command)
    {
        // TODO: Implement call() method.
    }

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    function platform()
    {
        // TODO: Implement platform() method.
    }
}
