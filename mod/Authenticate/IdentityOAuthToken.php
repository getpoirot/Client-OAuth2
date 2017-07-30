<?php
namespace Module\OAuth2Client\Authenticate;

use Poirot\AuthSystem\Authenticate\Interfaces\iIdentity;
use Poirot\OAuth2Client\Model\Entity\AccessToken;


class IdentityOAuthToken
    extends AccessToken
    implements iIdentity
{

}
