<?php
namespace Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Hydrator\HydrateGetters;


/**
 * Register New User
 *
 */
class Register
    extends ConfigurableSetter
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    protected $fullname;
    protected $credential;
    protected $username;
    protected $mobile;
    protected $email;
    protected $meta;

    protected $token;



    // Method Arguments

    // Setter:

    function setFullname($fullname)
    {
        $this->fullname = (string) $fullname;
    }

    function setCredential($credential)
    {
        $this->credential = (string) $credential;
    }

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

    function setMeta($meta)
    {
        $this->meta = $meta;
    }


    // Getter:

    function getFullname()
    {
        return $this->fullname;
    }

    function getCredential()
    {
        return $this->credential;
    }

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
     * @return array
     */
    function getMeta()
    {
        return $this->meta;
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
