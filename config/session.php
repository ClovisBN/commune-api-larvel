<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => env('SESSION_PATH', '/'),
'domain' => 'localhost',      // Assure que le domaine est défini correctement
'secure' => env('SESSION_SECURE_COOKIE', false),  // Doit être `true` en production (HTTPS)
'http_only' => true,  // Empêche les scripts JavaScript d'accéder aux cookies
'same_site' => 'lax',  // `lax` est généralement suffisant pour la plupart des cas

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
