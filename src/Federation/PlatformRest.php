<?php
namespace Poirot\OAuth2Client\Federation;

use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\OAuth2Client\Federation\Command\Members\Exists;


class PlatformRest
    extends \Poirot\OAuth2Client\Client\PlatformRest
{
    // Alters

    /**
     * Request Grant Token
     * @param Exists $command
     * @return iResponse
     */
    protected function members_Exists(Exists $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }
}
