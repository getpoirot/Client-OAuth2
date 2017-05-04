# Client-OAuth2
Provides a simple and clean Abstraction for Integration with OAuth 2.0 Server Providers.

## Config Client

```php
$auth = new \Poirot\OAuth2Client\Client(
    'http://172.17.0.1:8000/'
    , 'test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD'
    , 'xPWIpmzBK38MmDRd'
);
```

## Retrieve Implicit Url Redirection

```php
$url = $auth->attainAuthorizationUrl( $auth->withGrant('implicit') );
```

## Authorization Code Grant

Retrieve Redirection To Authorize Url: 

```php
$url = $auth->attainAuthorizationUrl( $auth->withGrant(GrantPlugins::AUTHORIZATION_CODE) );
```

When User redirect back include Auth Code:
 
```php
/** @var iAccessTokenObject $token */
$token = $auth->attainAccessToken(
    $auth->withGrant(GrantPlugins::AUTHORIZATION_CODE, ['code' => 'your_auth_code'])
);

$token->getAccessToken();
$token->getScopes();
$token->getDateTimeExpiration();
// ...
```

## Client Credential Grant

override default scopes request

```php
$token = $auth->attainAccessToken(
    $auth->withGrant(GrantPlugins::CLIENT_CREDENTIALS, [ 'scopes' => ['override' ,'scopes'] ])
);
```

## Password Credential

Specific Params Passed As Argument To Grant Factory

```php
try {
    $auth->attainAccessToken(
        $auth->withGrant('password')
    );
} catch (\Poirot\OAuth2Client\Exception\exMissingGrantRequestParams $e) {
    // Request Param "username" & "password" must Set.
    echo $e->getMessage();

    $token = $token = $auth->attainAccessToken(
        $auth->withGrant('password', ['username' => 'payam', 'password' => '123456'])
    );

    $refreshTokenStr = $token->getRefreshToken();
}
```


## And So on ....


# Poirot-OAuth2 Server Federation Commands

Specific Poirot Server Federation Commands To Deal 3rd party application with Server.

! For Federation Calls we need valid token:
  this token can strictly defined to client or retrieve from server.
  
  example below show token asserted from oauth server when required!
   
```php

// Setup OAuth2 Client
$client = new \Poirot\OAuth2Client\Client(
    'http://172.17.0.1:8000/'
    , 'test@default.axGEceVCtGqZAdW3rc34sqbvTASSTZxD'
    , 'xPWIpmzBK38MmDRd'
);

// Token Provider for Federation Calls
// Use Credential Grant as Grant Type for Tokens
$tokenProvider = new TokenFromOAuthClient(
    $client
    , $client->withGrant('client_credentials') 
);

// Note: 
// Make Calls and Don`t Worry About Token Renewal And Expired Tokens.
// Platfrom Will Handle It.

$federation = new \Poirot\OAuth2Client\Federation(
    'http://172.17.0.1:8000/'
    , $tokenProvider
);

// Check wheather this identifier(s) is given by any user?
$checkExists = $federation->checkIdentifierGivenToAnyUser([
    'email'  => 'naderi.payam@gmail.com',
    'mobile' => [
        'number'  => '9355497674',
        'country' => '+98',
    ],
]);

```

