<?php

return [

    'orwell' => [
        'driver' => 'pgsql',
        'host' => env('PG_HOST', '127.0.0.1'),
        'port' => env('PG_PORT', '5432'),
        'database' => env('PG_DATABASE', 'forge'),
        'username' => env('PG_USERNAME', 'forge'),
        'password' => env('PG_PASSWORD', ''),
        'charset' => 'utf8',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],

];