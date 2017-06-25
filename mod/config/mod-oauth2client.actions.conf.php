<?php
/**
 *
 * @see \Poirot\Ioc\Container\BuildContainer
 */
use Poirot\Ioc\Container\BuildContainer;

return array(
    'services' => array(
        // assertToken(iHttpRequest $request)
        'AssertToken' => new \Poirot\Ioc\instance(
            \Module\OAuth2Client\Actions\ServiceAssertTokenAction::class,
            \Poirot\Std\catchIt(function () {
                if (false === $c = \Poirot\Config\load(__DIR__.'/oauth2client/token_assertion'))
                    throw new \Exception('Config (oauth2client/token_assertion) not loaded.');

                return $c->value;
            })
        ),
    ),
);
