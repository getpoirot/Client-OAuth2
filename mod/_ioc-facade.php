<?php
namespace Module\OAuth2Client\Actions
{
    use Poirot\Http\Interfaces\iHttpRequest;


    /**
     * @property ServiceAssertTokenAction $AssertToken
     *
     * @method static array AssertToken(iHttpRequest $request)
     */
    class IOC extends \IOC
    { }
}


namespace Module\OAuth2Client\Services
{
    use Poirot\OAuth2Client\Interfaces\iClientOfOAuth;

    /**
     * @method static iClientOfOAuth OAuthClient()
     */
    class IOC extends \IOC
    { }
}
