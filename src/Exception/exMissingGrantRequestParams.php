<?php
namespace Poirot\OAuth2Client\Exception;

class exMissingGrantRequestParams
    extends \RuntimeException
{
    protected $code = 400;
    protected $message = 'Missing Grant Argument Parameter(s).';
}