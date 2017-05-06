<?php
namespace Poirot\OAuth2Client\Federation\Command\Me;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;


class AccountInfo
    implements iApiCommand
{
    use tCommandHelper;
    use tTokenAware;

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
}
