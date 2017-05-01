<?php
namespace Poirot\OAuth2Client\Authorization;

use Poirot\ApiClient\Response\ExpectedJson;
use Poirot\ApiClient\ResponseOfClient;
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

        $expected = $this->expected();
        if ($expected instanceof DataEntity) {
            // Response Body Can parsed To Data Structure
            if ( $exception = $expected->get('error') ) {
                $this->exception = new \Exception(
                    $exception->get('error_description')
                    .' '
                    .$expected->get('hint')

                    , 400
                );
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
        if ( $callable === null && false !== strpos($this->getMeta('content_type'), 'application/json') )
            // Retrieve Json Parsed Data Result
            $callable = new ExpectedJson;

        return parent::expected($callable);
    }
}
