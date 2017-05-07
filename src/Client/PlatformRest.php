<?php
namespace Poirot\OAuth2Client\Client;

use Poirot\ApiClient\Exceptions\exConnection;
use Poirot\ApiClient\Exceptions\exHttpResponse;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\OAuth2Client\Client\PlatformRest\ServerUrlEndpoints;
use Poirot\OAuth2Client\Exception\exResponseError;


class PlatformRest
    extends aOAuthPlatform
{
    // Options:
    protected $usingSsl  = false;
    protected $serverUrl = null;


    // Alters

    /**
     * Get Authorize Url By Argument Specified
     *
     * @param iApiCommand $command
     *
     * @return iResponse
     */
    protected function _GetAuthorizeUrl(iApiCommand $command)
    {
        $serverUrl = $this->_getServerUrlEndpoints($command);

        $authUrl = \Poirot\OAuth2Client\appendQuery(
            $serverUrl
            , \Poirot\OAuth2Client\buildQueryString( iterator_to_array($command) )
        );

        $response = new Response( $authUrl );
        return $response;
    }

    /**
     * Request Grant Token
     * @param iApiCommand $command
     * @return iResponse
     */
    function _Token(iApiCommand $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        if (
            array_key_exists('client_id',     $args)
            && array_key_exists('client_secret', $args)
        ) {
            // Request With Client Credential
            // As Authorization Header
            $headers['Authorization'] = 'Basic '.base64_encode($args['client_id'].':'.$args['client_secret']);

            unset($args['client_id']);
            unset($args['client_secret']);
        }

        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }


    // Options

    /**
     * Set Server Url
     *
     * @param string $url
     *
     * @return $this
     */
    function setServerUrl($url)
    {
        $this->serverUrl = (string) $url;
        return $this;
    }

    /**
     * Server Url
     *
     * @return string
     */
    function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * Using SSl While Send Request To Server
     *
     * @param bool $flag
     *
     * @return $this
     */
    function setUsingSsl($flag = true)
    {
        $this->usingSsl = (bool) $flag;
        return $this;
    }

    /**
     * Ssl Enabled?
     *
     * @return bool
     */
    function isUsingSsl()
    {
        return $this->usingSsl;
    }


    // ..

    protected function _sendViaCurl($method, $url, array $data, array $headers = [])
    {
        if (! extension_loaded('curl') )
            throw new \Exception('cURL library is not loaded');


        $handle = curl_init();

        $h = [];
        foreach ($headers as $key => $val)
            $h[] = $key.': '.$val;
        $headers = $h;


        $defHeaders = [
            'Accept: application/json',
            'charset: utf-8'
        ];

        if ($method == 'POST') {
            $defHeaders += [
                'Content-Type: application/x-www-form-urlencoded'
            ];

            curl_setopt($handle, CURLOPT_POST, true);
            # build request body
            $urlEncodeData = http_build_query($data);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $urlEncodeData);

        } elseif ($method == 'GET') {
            $urlEncodeData = http_build_query($data);
            // TODO set data in query params
        }

        $headers = array_merge(
            $defHeaders
            , $headers
        );


        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);


        # Send Post Request
        $cResponse     = curl_exec($handle);
        $cResponseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $cContentType  = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);

        if ($curl_errno = curl_errno($handle)) {
            // Connection Error
            $curl_error = curl_error($handle);
            throw new exConnection($curl_error, $curl_errno);
        }

        $exception = null;
        if (! ($cResponseCode >= 200 && $cResponseCode < 300) ) {
            $message = null;
            if ($cResponseCode >= 300 && $cResponseCode < 400)
                $message = 'Response Redirected To Another Uri.';

            $exception = new exHttpResponse($cResponse, $message, $cResponseCode);
        }

        $response = new Response(
            $cResponse
            , $cResponseCode
            , ['content_type' => $cContentType]
            , $exception
        );

        return $response;
    }

    protected function _getServerUrlEndpoints($command)
    {
        $url = new ServerUrlEndpoints(
            $this->getServerUrl()
            , $command
            , $this->isUsingSsl()
        );

        return (string) $url;
    }
}
