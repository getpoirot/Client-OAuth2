<?php
namespace Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;


class ListAccountsInfo
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    protected $uids;


    /**
     * AccountInfo constructor.
     *
     * @param array $uids
     */
    function __construct(array $uids)
    {
        $this->uids = array_values($uids);
    }


    // ..

    /**
     * @ignore
     */
    function getIterator()
    {
        $hyd = new \ArrayIterator([
            'uids' => $this->uids
        ]);

        return $hyd;
    }
}
