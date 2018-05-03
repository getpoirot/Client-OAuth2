<?php
namespace Poirot\OAuth2Client\Federation\Command\Members;

use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Request\tCommandHelper;
use Poirot\OAuth2Client\Federation\Command\tTokenAware;


class ValidateUserIdentifier
    implements iApiCommand
{
    use tCommandHelper;
    use tTokenAware;

    protected $token;

    protected $userId;
    protected $identifier;


    /**
     * Constructor.
     *
     * @param $userId
     * @param $identifierType
     */
    function __construct($userId, $identifierType)
    {
        $this->userId = $userId;
        $this->identifier = $identifierType;
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


    // Method Arguments:

    function getUserId()
    {
        return $this->userId;
    }

    function getIdentifier()
    {
        return $this->identifier;
    }
}
