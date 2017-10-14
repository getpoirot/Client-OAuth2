<?php
namespace Poirot\OAuth2Client\Exception;


class exResponseError
    extends \RuntimeException
{
    protected $errResponse;


    function __construct($errResponse, $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errResponse = $errResponse;
    }


    function getErrResponse()
    {
        return $this->errResponse;
    }
}
