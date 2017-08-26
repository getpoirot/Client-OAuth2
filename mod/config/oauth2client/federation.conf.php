<?php
return [
    'serverUrl'     => 'http://127.0.0.1/',
    'tokenProvider' => new \Poirot\Ioc\instance('/module/oauth2client/services/TokenProvider'),
];
