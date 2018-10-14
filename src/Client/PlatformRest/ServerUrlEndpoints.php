<?php
namespace Poirot\OAuth2Client\Client\PlatformRest;


use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\OAuth2Client\Federation\Command\Me\AccountInfo;
use Poirot\OAuth2Client\Federation\Command\Members\Exists;
use Poirot\OAuth2Client\Federation\Command\Members\ValidateUserIdentifier;
use Poirot\OAuth2Client\Federation\Command\Members\Whois;
use Poirot\OAuth2Client\Federation\Command\Recover\Validate;

class ServerUrlEndpoints
{
    protected $serverBaseUrl;
    protected $command;

    /**
     * ServerUrlEndpoints constructor.
     *
     * @param $serverBaseUrl
     * @param $command
     */
    function __construct($serverBaseUrl, $command, $ssl = false)
    {
        $this->serverBaseUrl = (string) $serverBaseUrl;
        $this->command    = $command;
    }

    function __toString()
    {
        return $this->_getServerHttpUrlFromCommand($this->command);
    }


    // ..

    /**
     * Determine Server Http Url Using Http or Https?
     *
     * @param iApiCommand $command
     *
     * @return string
     * @throws \Exception
     */
    protected function _getServerHttpUrlFromCommand($command)
    {
        $base = null;

        $cmMethod = strtolower( (string) $command );
        switch ($cmMethod) {
            case 'getauthurl':
                $base = 'auth';
                break;
            case 'token':
                $base = 'auth/token';
                break;

            case 'register':
                $base = 'api/v1/members';
                break;

            case 'accountinfo':
                $params = iterator_to_array($command);
                if (isset($params['username']))
                    $postfix = 'u/'.$params['username'];
                else
                    $postfix = '-'.current($params);
                $base = 'api/v1/members/profile/'.$postfix;
                break;

            case 'listaccountsinfo':
                $base = 'api/v1/members/profiles';
                break;

            case 'recover::validate':
                /** @var Validate $command */
                $params = iterator_to_array($command);
                $base = 'api/v1/me/identifiers/change/confirm/'.$params['validation_code'];
                break;
            case 'recover::resendcode':
                /** @var Validate $command */
                $params = iterator_to_array($command);
                $base = 'recover/validate/resend/'.$params['validation_code'].'/'.$params['identifier_type'];
                break;

            case 'members::exists':
                /** @var Exists $command */
                $base = 'api/v1/members/exists';
                break;
            case 'members::whois':
                /** @var Whois $command */
                $base = 'api/v1/members/whois';
                break;
            case 'members::validateuseridentifier':
                /** @var ValidateUserIdentifier $command */
                $base = 'api/v1/members/'.$command->getUserId().'/validate/'.$command->getIdentifier();
                break;

            case 'me::accountinfo':
                $postfix = '';
                /** @var AccountInfo $command */
                if ( $command->getIncludeGrants() )
                    $postfix = '?grants';

                $base = 'api/v1/me/profile'.$postfix;
                break;
            case 'me::changepassword':
                $base = 'api/v1/me/grants/password';
                break;
            case 'me::changeidentity':
                $base = 'api/v1/me/identifiers/change';
                break;
        }

        $serverUrl = rtrim($this->serverBaseUrl, '/');
        (! $base ) ?: $serverUrl .= '/'. trim($base, '/');
        return $serverUrl;
    }
}
