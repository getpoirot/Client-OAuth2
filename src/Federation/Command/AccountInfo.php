<?php
namespace Poirot\OAuth2Client\Federation\Command;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Interfaces\iAccessTokenObject;


class AccountInfo
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;

    const TYPE_UID = 'uid';
    const TYPE_USERNAME = 'username';

    protected $val;

    protected $token;


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


    function setToken(iAccessTokenObject $token)
    {
        $this->token = $token;
    }

    /**
     * @ignore
     * @return iAccessTokenObject
     */
    function getToken()
    {
        return $this->token;
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
