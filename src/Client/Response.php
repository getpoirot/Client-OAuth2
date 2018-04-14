<?php
namespace Poirot\OAuth2Client\Client;

use Poirot\ApiClient\Exceptions\exHttpResponse;
use Poirot\ApiClient\Response\ExpectedJson;
use Poirot\ApiClient\ResponseOfClient;
use Poirot\OAuth2Client\Exception\exIdentifierExists;
use Poirot\OAuth2Client\Exception\exInvalidRequest;
use Poirot\OAuth2Client\Exception\exOAuthAccessDenied;
use Poirot\OAuth2Client\Exception\exPasswordNotMatch;
use Poirot\OAuth2Client\Exception\exServerError;
use Poirot\OAuth2Client\Exception\exTokenMismatch;
use Poirot\OAuth2Client\Exception\exUnexpectedValue;
use Poirot\OAuth2Client\Exception\exUserNotFound;
use Poirot\Std\Struct\DataEntity;


class Response
    extends ResponseOfClient
{
    /**
     * Has Exception?
     *
     * @return \Exception|false
     */
    function hasException()
    {
        // Check exception from raw response
        // Server Response Status is 200 but Logical Error May Happen And Returned in Response Body
        if (! $this->exception ) {
            $res = $this->expected();
            if ($res instanceof DataEntity) {
                // Response Body Can parsed To Data Structure
                if ( $exception = $res->get('error') ) {
                    // TODO handle token revoke exceptions; OAuth Exceptions
                    $this->exception = new exTokenMismatch(
                        $res->get('error_description')
                        .' '
                        .$res->get('hint')

                        , 401
                    );
                }
            }
        } else if ($this->exception instanceof exHttpResponse) {
            // Determine Known Errors ...
            $expected = $this->expected();
            if ($expected && $err = $expected->get('error') ) {

                if ( is_string($err) )
                {
                    switch ($err) {
                        case 'access_denied':
                        case 'invalid_grant':
                            $description = $expected->get('error_description');
                            $this->exception = new exOAuthAccessDenied($description);
                            break;
                        case 'invalid_request':
                            $description = $expected->get('error_description');
                            $this->exception = new exInvalidRequest($description);
                            break;
                    }

                } elseif (isset($err['state']))
                {
                    switch (@$err['state']) {
                        case 'exUserNotFound':
                            $this->exception = new exUserNotFound($err['message'], (int)$err['code']);
                            break;
                        case 'exOAuthAccessDenied':
                            $this->exception = new exTokenMismatch($err['message'], (int)$err['code']);
                            break;
                        case 'exIdentifierExists':
                            $this->exception = new exIdentifierExists($err['message'], (int)$err['code']);
                            break;
                        case 'exPasswordNotMatch':
                            $this->exception = new exPasswordNotMatch($err['message'], (int)$err['code']);
                            break;
                        case 'exUnexpectedValue':
                            $this->exception = new exUnexpectedValue($err['message'], (int)$err['code']);
                            break;
                        case 'exMessageMalformed':
                            $this->exception = new exUnexpectedValue($err['message'], (int)$err['code']);
                            break;
                    }
                }
            }
        }


        return $this->exception;
    }

    /**
     * Process Raw Body As Result
     *
     * :proc
     * mixed function($originResult, $self);
     *
     * @param callable $callable
     *
     * @return mixed
     */
    function expected(/*callable*/ $callable = null)
    {
        if ( $callable === null )
            // Retrieve Json Parsed Data Result
            $callable = $this->_getDataParser();


        return parent::expected($callable);
    }


    // ...

    function _getDataParser()
    {
        if ( false !== strpos($this->getMeta('content_type'), 'application/json') )
            // Retrieve Json Parsed Data Result
            return new ExpectedJson;


        if ($this->responseCode == 204) {
            return function() {
                return null;
            };
        }

        throw new exServerError($this->rawBody);
    }
}
