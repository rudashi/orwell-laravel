<?php

declare(strict_types=1);

use Rudashi\Orwell\OrwellServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Here is the database connection setup for orwell package.
    |
    */

    OrwellServiceProvider::PACKAGE => [
        'driver' => 'pgsql',
        'url' => env('DATABASE_URL'),
        'host' => env('PG_HOST', '127.0.0.1'),
        'port' => env('PG_PORT', '5432'),
        'database' => env('PG_DATABASE', 'forge'),
        'username' => env('PG_USERNAME', 'forge'),
        'password' => env('PG_PASSWORD', ''),
        'charset' => 'utf8',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
        'engine' => null,
    ],
];
