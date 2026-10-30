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
        ->assertJsonPath('data.colors', config('branding.colors'))
        ->assertJsonPath('data.fonts.heading.family', config('branding.fonts.heading.family'))
        ->assertJsonPath('data.fonts.mono.stylesheet_url', route('fonts.show', ['path' => config('branding.fonts.mono.stylesheet')]))
        ->assertJsonPath('data.radius', config('branding.radius'));
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
