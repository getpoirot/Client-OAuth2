<?php
namespace Poirot\OAuth2Client\Interfaces;

use Poirot\OAuth2Client\Exception\exMissingGrantRequestParams;


interface iGrantTokenRequest
{
    /**
     * Assert Parameters and Give Request Parameters
     *
     * @return array
     * @throws exMissingGrantRequestParams
     */
    function assertTokenParams();
}
