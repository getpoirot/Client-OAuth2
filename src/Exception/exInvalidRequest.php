<?php
namespace Poirot\OAuth2Client\Exception;


class exInvalidRequest
    extends \RuntimeException
{
    protected $code = 401;
    protected $message = 'invalid_request';

}