<?php
namespace Poirot\OAuth2Client
{
    /**
     * Build a query string from an array.
     *
     * @param array $params
     *
     * @return string
     */
    function buildQueryString(array $params)
    {
        return http_build_query($params, null, '&', \PHP_QUERY_RFC3986);
    }

    /**
     * Appends a query string to a URL
     *
     * @param  string $url
     * @param  string $query
     *
     * @return string The resulting URL
     */
     function appendQuery($url, $query)
     {
        $query = rtrim($query, '?&');

        if ($query) {
            $glue = strstr($url, '?') === false ? '?' : '&';
            return $url . $glue . $query;
        }

        return $url;
     }
}
