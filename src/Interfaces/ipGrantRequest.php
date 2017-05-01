<?php
namespace Poirot\OAuth2Client\Interfaces;

use Poirot\Std\Interfaces\Pact\ipConfigurable;


interface ipGrantRequest
    extends ipConfigurable
{
    /**
     * Grant identifier (client_credentials, password, ...)
     *
     * @return string
     */
    function getGrantType();

}
