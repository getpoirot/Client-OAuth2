<?php
namespace Poirot\OAuth2Client\Federation\Command\Recover;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;


class ResendCode
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;

    protected $validationHash;
    protected $identifierType = [];


    /**
     * Validate constructor.
     *
     * @param string $validationHash
     * @param string $identifierType
     */
    function __construct($validationHash, $identifierType)
    {
        $this->validationHash = (string) $validationHash;
        $this->identifierType = (string) $identifierType;
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
        $hyd = new \ArrayIterator([
            'validation_code' => $this->validationHash,
            'identifier_type' => $this->identifierType, ]);

        return $hyd;
    }
}
