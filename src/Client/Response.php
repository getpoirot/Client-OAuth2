<?php
namespace Poirot\OAuth2Client\Client;

use Poirot\ApiClient\Response\ExpectedJson;
use Poirot\ApiClient\ResponseOfClient;
use Poirot\OAuth2Client\Exception\exResponseError;
use Poirot\OAuth2Client\Exception\exTokenMismatch;
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
            $date = $this->expected();
            if ($date instanceof DataEntity) {
                // Response Body Can parsed To Data Structure
                if ( $exception = $date->get('error') ) {
                    $this->exception = new \Exception(
                        $date->get('error_description')
                        .' '
                        .$date->get('hint')

                        , 400
                    );
                }
            }
        }

        if ($this->exception instanceof exResponseError) {
            $expected = $this->_getDataParser();

            $data = call_user_func($expected, $this->exception->getErrResponse());
            if ($err =  $data->get('error') ) {
                switch ($err['state']) {
                    case 'exOAuthAccessDenied':
                        $this->exception = new exTokenMismatch($err['message'], (int) $err['code']);
                        break;
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


        return null;
    }
}
