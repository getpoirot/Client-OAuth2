<?php
namespace Poirot\OAuth2Client\Exception;


class exOAuthScopeRequired
    extends exOAuthAccessDenied
{
    protected $code = 403;
    protected $message = 'invalid_grant';

}
