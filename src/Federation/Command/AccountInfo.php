<?php
namespace Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;


class AccountInfo
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    const TYPE_UID = 'uid';
    const TYPE_USERNAME = 'username';

    protected $val;


    /**
     * AccountInfo constructor.
     *
     * @param string $val
     * @param string $type
     */
    function __construct($val, $type = self::TYPE_UID)
    {
        $this->val = [
            $type => $val
        ];

    }


    // ..

    /**
     * @ignore
     */
    function getIterator()
    {
        $hyd = new \ArrayIterator(
            $this->val
        );

        return $hyd;
    }
}
