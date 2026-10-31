<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Store Branding Defaults
|--------------------------------------------------------------------------
|
| Initial values of the BrandingSettings group, written to the database by
| the settings migration and adjusted by the seeder. Business code reads the
| settings, never this file. Colors, fonts and radius are provisional until
| the visual direction is approved.
|
*/

return [

    'store_name' => env('APP_NAME', 'Laravel'),

    'whatsapp' => null,

    'locale' => 'pt-BR',

    'currency' => 'BRL',

    /*
    | Two palettes with the same roles: the web emits both and picks one by
    | the visitor's theme (cookie) or the system preference. Dark takes the
    | charcoal of the logo; light takes the stone of the photo bench.
    */

    'colors_dark' => [
        'background' => '#161615',
        'surface' => '#22211f',
        'foreground' => '#ecebe7',
        'muted' => '#a7a49d',
        'accent' => '#dcd8cf',
        'accent_foreground' => '#161615',
    ],

    'colors_light' => [
        'background' => '#e4e1da',
        'surface' => '#d6d2c9',
        'foreground' => '#161615',
        'muted' => '#56534d',
        'accent' => '#161615',
        'accent_foreground' => '#ecebe7',
    ],

    /*
    | Stylesheets are paths under resources/fonts, served by the fonts route
    | with CORS headers so the fronts can load them cross-origin.
    */

    'fonts' => [
        'heading' => ['family' => 'Epilogue', 'stylesheet' => 'epilogue/epilogue.css'],
        'body' => ['family' => 'Epilogue', 'stylesheet' => 'epilogue/epilogue.css'],
    ],

    'radius' => '20px',

    /*
    | Cache lifetime (in seconds) sent with every font file.
    */

    'fonts_cache_seconds' => (int) env('BRAND_FONTS_CACHE_SECONDS', 31536000),

];
