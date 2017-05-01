<?php
namespace Poirot\OAuth2Client\Interfaces;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;


interface iGrantAuthorizeRequest
{
    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertAuthorizeParams();
}
