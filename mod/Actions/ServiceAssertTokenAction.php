<?php
namespace Module\OAuth2Client\Actions;

use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\OAuth2Client\Assertion\aAssertToken;
use Poirot\OAuth2Client\Assertion\AssertByInternalServer;
use Poirot\OAuth2Client\Assertion\AssertByRemoteServer;
use Poirot\OAuth2Client\Interfaces\iAccessToken;


class ServiceAssertTokenAction
    extends aServiceContainer
{
    /** @var string Service Name */
    protected $name = 'AssertToken';

    /** @var aAssertToken */
    protected $tokenAssertion;
    protected $debugMode = false;
    protected $debugToken = [
        /** @see \Poirot\OAuth2Client\Model\Entity\AccessToken */
        # 'client_identifier' => 'test',
        # 'owner_identifier'  => 'test',
        # 'scopes'            => [ 'test', 'debug', ],
    ];


    /**
     * Create Service
     *
     * @return callable
     */
    function newService()
    {
        # Check Debug Mode:

        if ( $this->debugMode )
            // Mock Debug Mode
            return new AssertDebugTokenAction($this->debugToken);


        if ($this->tokenAssertion)
            $assertion = $this->tokenAssertion;

        else throw new \RuntimeException('Token Assertion Provider Not Injected.');

        $assertAction = new AssertTokenAction($assertion);
        return $assertAction;
    }


    // ..

    /**
     * @param boolean $debugMode
     */
    function setDebugMode($debugMode)
    {
        $this->debugMode = (bool) $debugMode;
    }

    /**
     * @param iAccessToken|array $debugToken Array allow config files
     */
    function setDebugToken($debugToken)
    {
        $this->debugToken = $debugToken;
    }

    /**
     * Token Assertion Must Given
     *
     * usually it can be:
     * - AssertByRemoteServer
     * - AssertByInternalServer
     *
     * @see AssertByRemoteServer
     * @see AssertByInternalServer
     *
     * @param aAssertToken $tokenAssertion
     */
    function setTokenAssertion(aAssertToken $tokenAssertion)
    {
        $this->tokenAssertion = $tokenAssertion;
    }
}
