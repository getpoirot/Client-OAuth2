<?php
namespace Module\OAuth2Client\Services\Authenticate;

use Poirot\AuthSystem\Authenticate\Interfaces\iProviderIdentityData;
use Poirot\OAuth2Client\Federation;
use Poirot\Std\Interfaces\Struct\iData;


class IdentityProviderFederation
    implements iProviderIdentityData
{
    /** @var Federation */
    protected $federation;


    /**
     * IdentityProviderFederation constructor.
     *
     * @param Federation $federation Prepared Federation
     */
    function __construct(Federation $federation)
    {
        $this->federation = $federation;
    }


    /**
     * Finds a user by the given user Identity.
     *
     * @param string $property ie. 'name'
     * @param mixed $value ie. 'payam@mail.com'
     *
     * @return iData
     * @throws \Exception
     */
    function findOneMatchBy($property, $value)
    {
        switch ($property) {
            case 'owner_identifier':
                return $this->federation->getAccountInfoByUid($value);
                break;
            default:
                throw new \Exception(sprintf(
                    'Identity Provider Can`t Attain with (%s).'
                    , $property
                ));
        }
    }
}
