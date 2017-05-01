<?php
namespace Poirot\OAuth2Client\Authorization;

use Poirot\ApiClient\Exceptions\exConnection;
use Poirot\ApiClient\Exceptions\exHttpResponse;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Request\Command;
use Poirot\Std\ConfigurableSetter;


class PlatformRest
    extends ConfigurableSetter
    implements iPlatform
{
    /** @var Command */
    protected $Command;

    // Options:
    protected $usingSsl  = false;
    protected $serverUrl = null;


    /**
     * Build Platform Specific Expression To Send Trough Transporter
     *
     * @param iApiCommand $command Method Interface
     *
     * @return iPlatform Self or Copy/Clone
     */
    function withCommand(iApiCommand $command)
    {
        $self = clone $this;
        $self->Command = $command;
        return $self;
    }

    /**
     * Build Response with send Expression over Transporter
     *
     * - Result must be compatible with platform
     * - Throw exceptions if response has error
     *
     * @throws \Exception Command Not Set
     * @return iResponse
     */
    function send()
    {
        if (! $command = $this->Command )
            throw new \Exception('No Command Is Specified.');


        # Build Command


        // Alter Platform Commands
        $methodName = $command->getMethodName();
        $alterCall  = '_send'.ucfirst($methodName);
        if (method_exists($this, $alterCall))
            // Call Alternative Method Call Instead ...
            return $this->{$alterCall}($command);


        // Prepare Command Send Over Wire

        $headers   = [];
        $arguments = $command->getArguments();


        ## Send Data Over Wire ...

        $response = $this->_sendViaCurl(
            'POST'
            , $this->_getServerHttpUrlFromCommand($command)
            , $arguments
            , $headers
        );

        return $response;
    }


    // Alters

    /**
     * @param Command $command
     * @return iResponse
     */
    function _sendGetAuthUrl(Command $command)
    {
        $reqUrl  = $this->_getServerHttpUrlFromCommand($command);
        $authUrl = \Poirot\OAuth2Client\appendQuery(
            $reqUrl
            , \Poirot\OAuth2Client\buildQueryString( $command->getArguments() )
        );

        $response = new Response($authUrl);
        return $response;
    }

    /**
     * @param Command $command
     * @return iResponse
     */
    function _sendToken(Command $command)
    {
        $headers = [];
        $args    = $command->getArguments();

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

        $response = $this->_sendViaCurl(
            'POST'
            , $this->_getServerHttpUrlFromCommand($command)
            , $args
            , $headers
        );

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
        if ($cResponseCode != 200)
            $exception = new exHttpResponse($cResponse, $cResponseCode);

        $response = new Response(
            $cResponse
            , ['content_type' => $cContentType]
            , $exception
        );

        return $response;
    }

    /**
     * Determine Server Http Url Using Http or Https?
     *
     * @param Command $command
     *
     * @return string
     * @throws \Exception
     */
    protected function _getServerHttpUrlFromCommand(Command $command, $base = null)
    {
        $cmMethod = strtolower($command->getMethodName());
        switch ($cmMethod) {
            case 'getauthurl':
                $base = 'auth';
                break;
            case 'token':
                $base = 'auth/token';
                break;
        }

        $serverUrl = rtrim($this->getServerUrl(), '/');
        (! $base ) ?: $serverUrl .= '/'. rtrim($base, '/');
        return $serverUrl;
    }
}
