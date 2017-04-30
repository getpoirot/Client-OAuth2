<?php
/**
 *
 * @see \Poirot\Ioc\Container\BuildContainer
 */
use Poirot\Ioc\Container\BuildContainer;

return array(
    'services' => array(
        // assertToken(iHttpRequest $request)
        \Module\OAuth2Client\Actions\ServiceAssertTokenAction::class,
    ),
);
