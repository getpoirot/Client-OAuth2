<?php
namespace Poirot\OAuth2Client\Federation\Command\Recover;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;


class Validate
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;

    protected $validationHash;
    protected $codes = [];


    /**
     * Validate constructor.
     *
     * @param $validationHash
     * @param $codes
     */
    function __construct($validationHash, $codes)
    {
        $this->validationHash = $validationHash;
        $this->codes = $codes;
    }

    /**
     * Get Namespace
     *
     * @return array
     */
    function getNamespace()
    {
        return ['recover'];
    }


    // ..

    /**
     * @ignore
     */
    function getIterator()
    {
        $hyd = new \ArrayIterator(
            ['validation_code' => $this->validationHash]
            + $this->codes
        );
        return $hyd;
    }
}
