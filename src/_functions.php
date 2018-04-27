<?php
namespace Poirot\OAuth2Client
{
    use Psr\Http\Message\ServerRequestInterface;


    /**
     * Check Expiration Of DateTime
     *
     * @param \DateTime $dateTime
     *
     * @return boolean
     */
    function checkExpiry(\DateTime $dateTime)
    {
        $currDateTime   = new \DateTime();
        $currDateTime   = $currDateTime->getTimestamp();

        $expireDateTime = $dateTime->getTimestamp();
        return ($currDateTime-$expireDateTime > 0);
    }

    /**
     * As per the Bearer spec (draft 8, section 2) - there are three ways for a client
     * to specify the bearer token, in order of preference: Authorization Header,
     * POST and GET.
     *
     * @param ServerRequestInterface $request
     *
     * @return null|string Token
     */
    function parseTokenStrFromRequest(ServerRequestInterface $request)
    {
        # Get Token From Header:
        if ($header = $request->getHeaderLine('Authorization')) {
            if ( preg_match('/Bearer\s(\S+)/', $header, $matches) )
                return $token = $matches[1];
        }


        # Get Token From POST:
        if (strtolower($request->getMethod()) === 'post'
            && $contentType = $request->getHeaderLine('Content-Type')
        ) {
            if ($contentType == 'application/x-www-form-urlencoded') {
                // The content type for POST requests must be "application/x-www-form-urlencoded
                $postData = $request->getParsedBody();
                foreach ($postData as $k => $v) {
                    if ($k !== 'access_token') continue;

                    return $token = $v;
                }
            }
        }


        # Get Token From GET:
        $queryData = $request->getQueryParams();
        $token     = (isset($queryData['access_token'])) ? $queryData['access_token'] : null;
        return $token;
    }
}
