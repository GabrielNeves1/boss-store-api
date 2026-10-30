<?php

declare(strict_types=1);

it('serves a font stylesheet with cors and cache headers', function (): void {
    $this->withHeader('Origin', 'http://localhost:9000')
        ->get(route('fonts.show', ['path' => 'archivo/archivo.css']))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/css; charset=UTF-8')
        ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:9000')
        ->assertHeader('Cache-Control', sprintf('immutable, max-age=%d, public', config('branding.fonts_cache_seconds')));
});

it('serves a woff2 file with its mime type', function (): void {
    $this->get(route('fonts.show', ['path' => 'ibm-plex-mono/ibm-plex-mono-latin-400-normal.woff2']))
        ->assertOk()
        ->assertHeader('Content-Type', 'font/woff2');
});

it('returns 404 for unknown files', function (): void {
    $this->get(route('fonts.show', ['path' => 'archivo/missing.woff2']))->assertNotFound();
});

it('does not leave the fonts directory', function (): void {
    $this->get('/fonts/../../.env')->assertNotFound();
    $this->get('/fonts/..%2F..%2F.env')->assertNotFound();
});
