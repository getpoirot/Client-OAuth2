<?php
namespace Poirot\OAuth2Client\Exception;

class exGrantRequestNotImplemented
    extends \RuntimeException
{
    protected $code = 400;
    protected $message = 'Grant Request Not Implemented.';
}