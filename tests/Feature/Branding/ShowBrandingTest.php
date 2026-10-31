<?php

declare(strict_types=1);

use App\Settings\BrandingSettings;

it('exposes the branding settings publicly', function (): void {
    $this->getJson(route('branding.show'))
        ->assertOk()
        ->assertJsonPath('data.store_name', config('branding.store_name'))
        ->assertJsonPath('data.whatsapp', null)
        ->assertJsonPath('data.locale', config('branding.locale'))
        ->assertJsonPath('data.currency', config('branding.currency'))
        ->assertJsonPath('data.logos', ['on_dark' => null, 'on_light' => null])
        ->assertJsonPath('data.colors.dark', config('branding.colors_dark'))
        ->assertJsonPath('data.colors.light', config('branding.colors_light'))
        ->assertJsonPath('data.fonts.heading.family', config('branding.fonts.heading.family'))
        ->assertJsonPath('data.fonts.body.stylesheet_url', route('fonts.show', ['path' => config('branding.fonts.body.stylesheet')]))
        ->assertJsonPath('data.radius', config('branding.radius'))
        ->assertJsonPath('data.opening', ['video_webm' => null, 'video_mp4' => null, 'poster' => null]);
});

it('turns stored logo paths into public urls', function (): void {
    BrandingSettings::fake([
        'logo_on_dark_path' => 'branding/logo-on-dark.svg',
        'logo_on_light_path' => 'branding/logo-on-light.svg',
    ]);

    $this->getJson(route('branding.show'))
        ->assertOk()
        ->assertJsonPath('data.logos.on_dark', fn (string $url): bool => str_ends_with($url, '/storage/branding/logo-on-dark.svg'))
        ->assertJsonPath('data.logos.on_light', fn (string $url): bool => str_ends_with($url, '/storage/branding/logo-on-light.svg'));
});

it('turns the opening video paths into public urls', function (): void {
    BrandingSettings::fake([
        'opening_video_webm_path' => 'branding/opening.webm',
        'opening_video_mp4_path' => 'branding/opening.mp4',
        'opening_poster_path' => 'branding/opening.webp',
    ]);

    $this->getJson(route('branding.show'))
        ->assertOk()
        ->assertJsonPath('data.opening.video_webm', fn (string $url): bool => str_ends_with($url, '/storage/branding/opening.webm'))
        ->assertJsonPath('data.opening.video_mp4', fn (string $url): bool => str_ends_with($url, '/storage/branding/opening.mp4'))
        ->assertJsonPath('data.opening.poster', fn (string $url): bool => str_ends_with($url, '/storage/branding/opening.webp'));
});
