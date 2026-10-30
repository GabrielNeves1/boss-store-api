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

];
