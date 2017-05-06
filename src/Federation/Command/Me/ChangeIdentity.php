<?php
namespace Poirot\OAuth2Client\Federation\Command\Me;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Hydrator\HydrateGetters;


class ChangeIdentity
    extends ConfigurableSetter
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    protected $username;
    protected $email;
    protected $mobile;


    /**
     * Get Namespace
     *
     * @ignore Ignored by getterHydrate
     * @return array
     */
    function getNamespace()
    {
        return ['me'];
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
     * @ignore
     */
    function getIterator()
    {
        $hyd = new HydrateGetters($this);
        $hyd->setExcludeNullValues();
        return $hyd;
    }
}
