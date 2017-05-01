<?php
namespace Poirot\OAuth2Client\Authorization;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiCommand;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Request\Command;
use Poirot\Std\ConfigurableSetter;


abstract class aOAuthPlatform
    extends ConfigurableSetter
    implements iPlatform
{
    /** @var Command */
    protected $Command;

    // Options:
    protected $usingSsl  = false;
    protected $serverUrl = null;


    /**
     * Build Platform Specific Expression To Send Trough Transporter
     *
     * @param iApiCommand $command Method Interface
     *
     * @return iPlatform Self or Copy/Clone
     */
    function withCommand(iApiCommand $command)
    {
        $self = clone $this;
        $self->Command = $command;
        return $self;
    }

    /**
     * Build Response with send Expression over Transporter
     *
     * - Result must be compatible with platform
     * - Throw exceptions if response has error
     *
     * @throws \Exception Command Not Set
     * @return iResponse
     */
    final function send()
    {
        if (! $command = $this->Command )
            throw new \Exception('No Command Is Specified.');


        return $this->doSend($command);
    }

    /**
     * Do Execute Command
     *
     * @param iApiCommand $command
     *
     * @return iResponse
     */
    abstract function doSend(iApiCommand $command);
}
