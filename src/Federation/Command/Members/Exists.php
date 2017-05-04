<?php
namespace Poirot\OAuth2Client\Federation\Command\Members;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Hydrator\HydrateGetters;


/**
 * To check whether user with given identifier(s) exists?
 *
 */
class Exists
    extends ConfigurableSetter
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;

    protected $username;
    protected $mobile;
    protected $email;
    protected $token;


    /**
     * Get Namespace
     *
     * @ignore
     * @return array
     */
    function getNamespace()
    {
        return ['members'];
    }


    // Method Arguments

    // Setter:

    function setUsername($username)
    {
        $this->username = (string) $username;
    }

    function setEmail($email)
    {
        $this->email = (string) $email;
    }

    function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    function setToken(iAccessTokenObject $token)
    {
        $this->token = $token;
    }

    // Getter:

    function getUsername()
    {
        return $this->username;
    }

    function getMobile()
    {
        return $this->mobile;
    }

    function getEmail()
    {
        return $this->email;
    }

    /**
     * @return iAccessTokenObject
     */
    function getToken()
    {
        return $this->token;
    }


    // Implement Hydrator

    /**
     * @ignore
     */
    function getIterator()
    {
        $hyd = new HydrateGetters($this);
        $hyd->setExcludeNullValues();
        return $hyd;
    }
}
