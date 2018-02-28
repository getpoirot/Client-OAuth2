<?php
namespace Poirot\OAuth2Client\Exception;


class exOAuthAccessDenied
    extends \RuntimeException
{
    protected $code = 401;
    protected $message = 'invalid_grant';

}