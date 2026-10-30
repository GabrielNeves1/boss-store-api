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

    'colors' => [
        'background' => '#111111',
        'surface' => '#1a1a1a',
        'foreground' => '#f5f5f5',
        'muted' => '#8a8a8a',
        'signal' => '#bdbdbd',
    ],

    /*
    | Stylesheets are paths under resources/fonts, served by the fonts route
    | with CORS headers so the fronts can load them cross-origin.
    */

    'fonts' => [
        'heading' => ['family' => 'Archivo', 'stylesheet' => 'archivo/archivo.css'],
        'body' => ['family' => 'Archivo', 'stylesheet' => 'archivo/archivo.css'],
        'mono' => ['family' => 'IBM Plex Mono', 'stylesheet' => 'ibm-plex-mono/ibm-plex-mono.css'],
    ],

    'radius' => '4px',

    /*
    | Cache lifetime (in seconds) sent with every font file.
    */

    'fonts_cache_seconds' => (int) env('BRAND_FONTS_CACHE_SECONDS', 31536000),

];
