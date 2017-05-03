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

