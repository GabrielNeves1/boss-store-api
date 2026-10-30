<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Admin User
    |--------------------------------------------------------------------------
    |
    | First panel user created by the database seeder. When any value is
    | empty, the seeder creates nobody.
    |
    */

    'admin' => [
        'name' => env('SEED_ADMIN_NAME'),
        'email' => env('SEED_ADMIN_EMAIL'),
        'password' => env('SEED_ADMIN_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Store Identity
    |--------------------------------------------------------------------------
    |
    | Values applied by the branding seeder on top of config/branding.php.
    | Logo paths are read from inside the container (see compose.yaml).
    | Empty values leave the current settings untouched.
    |
    */

    'store' => [
        'name' => env('SEED_STORE_NAME'),
        'logo_on_dark_path' => env('SEED_LOGO_ON_DARK_PATH'),
        'logo_on_light_path' => env('SEED_LOGO_ON_LIGHT_PATH'),
    ],

];
