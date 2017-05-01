<?php
namespace Poirot\OAuth2Client\Interfaces;

/*
{
  "access_token": "986bd8c7cd855546baea839a21020014e1b7ebba",
  "refresh_token": "3480fe242a9799d82e8c406c0b084e69518c326b23273fed46fb1a64b219",
  "token_type": "Bearer",
  "expires_in": 3600,
  "client_id": "test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD"
}
*/

interface iAccessTokenObject
{
    function getAccessToken();

    function getRefreshToken();

    function getTokenType();

    /**
     * Client Identifier That Token Issued To
     *
     * @return string|int
     */
    function getClientId();

    /**
     * Get the token's expiry date time
     *
     * @return \DateTime
     */
    function getDateTimeExpiration();

    /**
     * Return an array of scopes associated with the token
     *
     * @return string[]
     */
    function getScopes();
}
