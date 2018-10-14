<?php
namespace Poirot\OAuth2Client\Federation\Command\Me;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;


class ChangePassword
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    protected $val;


    /**
     * ChangePassword constructor.
     *
     * @param $newPass
     * @param $currPass
     */
    function __construct($newPass, $currPass = null)
    {
        $v = [
            'newpass'  => $newPass,
        ];

        ($currPass === null) ?: $v['currpass'] = $currPass;

        $this->val = $v;
    }

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


    function getIterator()
    {
        $hyd = new \ArrayIterator($this->val);
        return $hyd;
    }
}
