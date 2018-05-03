<?php
namespace Poirot\OAuth2Client\Federation;

use Poirot\OAuth2Client\Federation\Command;
use Poirot\ApiClient\Interfaces\Response\iResponse;


class PlatformRest
    extends \Poirot\OAuth2Client\Client\PlatformRest
{
    // Alters

    /**
     * @param Command\Register $command
     * @return iResponse
     */
    protected function _Register(Command\Register $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\AccountInfo $command
     * @return iResponse
     */
    protected function _AccountInfo(Command\AccountInfo $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('GET', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\ListAccountsInfo $command
     * @return iResponse
     */
    protected function _ListAccountsInfo(Command\ListAccountsInfo $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Recover\Validate $command
     * @return \Poirot\OAuth2Client\Client\Response
     */
    protected function recover_Validate(Command\Recover\Validate $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Recover\ResendCode $command
     * @return \Poirot\OAuth2Client\Client\Response
     */
    protected function recover_ResendCode(Command\Recover\ResendCode $command)
    {
        $url     = $this->_getServerUrlEndpoints($command);
        $args    = []; // iterator_to_array($command)
        $headers = [];

        $response = $this->_sendViaCurl('GET', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Members\Exists $command
     * @return iResponse
     */
    protected function members_Exists(Command\Members\Exists $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Members\Whois $command
     * @return iResponse
     */
    protected function members_Whois(Command\Members\Whois $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Members\ValidateUserIdentifier $command
     * @return iResponse
     */
    protected function members_ValidateUserIdentifier(Command\Members\ValidateUserIdentifier $command)
    {
        $headers = [];

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );


        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('GET', $url, [], $headers);
        return $response;
    }


    /**
     * @param Command\Me\AccountInfo $command
     * @return iResponse
     */
    protected function me_AccountInfo(Command\Me\AccountInfo $command)
    {
        $headers = [];

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );

        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('GET', $url, [], $headers);
        return $response;
    }

    /**
     * @param Command\Me\ChangePassword $command
     * @return iResponse
     */
    protected function me_ChangePassword(Command\Me\ChangePassword $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );

        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }

    /**
     * @param Command\Me\ChangeIdentity $command
     * @return iResponse
     */
    protected function me_ChangeIdentity(Command\Me\ChangeIdentity $command)
    {
        $headers = [];
        $args    = iterator_to_array($command);

        // Request With Client Credential
        // As Authorization Header
        $headers['Authorization'] = 'Bearer '. ( $command->getToken()->getAccessToken() );

        $url = $this->_getServerUrlEndpoints($command);
        $response = $this->_sendViaCurl('POST', $url, $args, $headers);
        return $response;
    }
}
