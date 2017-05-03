<?php
namespace Poirot\OAuth2Client\Command;


use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\Std\Struct\DataOptionsOpen;


class GetAuthorizeUrl
    extends DataOptionsOpen
    implements iApiCommand
{
    use tCommandHelper;


}
