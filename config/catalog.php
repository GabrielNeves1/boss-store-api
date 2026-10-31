<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */

    'per_page' => (int) env('CATALOG_PER_PAGE', 24),

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    |
    | Originals are read from a read-only folder mounted in the container and
    | never modified. Derivatives (WebP photos at several widths, muted video
    | loops and posters) are written to the public disk under "directory".
    |
    */

    'media' => [
        'source_path' => env('CATALOG_MEDIA_SOURCE_PATH'),
        'disk' => 'public',
        'directory' => 'catalog',
        'photo_widths' => [480, 768, 1080, 1440, 2048],
        'aspect_ratio' => ['width' => 4, 'height' => 5],
        'webp_quality' => (int) env('CATALOG_WEBP_QUALITY', 82),
        'video_max_width' => 720,
        'ffmpeg_binary' => env('FFMPEG_BINARY', 'ffmpeg'),
    ],

];
