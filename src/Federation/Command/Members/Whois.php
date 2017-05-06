<?php
namespace Poirot\OAuth2Client\Federation\Command\Members;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;


/**
 * To retrieve uid+basic profile from given identifier
 *
 */
class Whois
    implements iApiCommand
    , \IteratorAggregate
{
    use tCommandHelper;
    use tTokenAware;

    protected $token;
    protected $identifier;

    /**
     * Whois constructor.
     *
     * @param string $identifierType  Identifier type, exp. "email" | "mobile"
     * @param mixed  $identifierValue
     */
    function __construct($identifierType, $identifierValue)
    {
        $this->identifier = array(
            $identifierType => $identifierValue
        );
    }

    /**
     * Get Namespace
     *
     * @ignore
     * @return array
     */
    function getNamespace()
    {
        return ['members'];
    }


    // Method Arguments
    // ...


    // Implement Hydrator

    /**
     * @ignore
     */
    function getIterator()
    {
        return new \ArrayIterator($this->identifier);
    }
}
