<?php
namespace Poirot\OAuth2Client\Federation\Command\Me;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;
use Poirot\Std\ConfigurableSetter;


class AccountInfo
    extends ConfigurableSetter
    implements iApiCommand
{
    use tCommandHelper;
    use tTokenAware;


    protected $includeGrants;

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


    // ..

    /**
     * @return true
     */
    function getIncludeGrants()
    {
        return $this->includeGrants;
    }

    /**
     * Set Response Include User Available Grants
     * @param bool $includeGrants
     * @return $this
     */
    function setIncludeGrants($includeGrants)
    {
        $this->includeGrants = (bool) $includeGrants;
        return $this;
    }
}
