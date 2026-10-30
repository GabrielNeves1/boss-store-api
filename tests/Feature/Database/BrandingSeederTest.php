<?php

declare(strict_types=1);

use App\Settings\BrandingSettings;
use Database\Seeders\BrandingSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

it('applies the store name and copies the logos to the public disk', function (): void {
    config()->set('seeding.store', [
        'name' => 'Loja de Teste',
        'logo_on_dark_path' => base_path('tests/Fixtures/brand/logo-on-dark.svg'),
        'logo_on_light_path' => base_path('tests/Fixtures/brand/logo-on-light.svg'),
    ]);

    $this->seed(BrandingSeeder::class);

    $settings = app(BrandingSettings::class)->refresh();

    expect($settings->store_name)->toBe('Loja de Teste')
        ->and($settings->logo_on_dark_path)->toBe('branding/logo-on-dark.svg')
        ->and($settings->logo_on_light_path)->toBe('branding/logo-on-light.svg');

    Storage::disk('public')->assertExists('branding/logo-on-dark.svg');
    Storage::disk('public')->assertExists('branding/logo-on-light.svg');

    expect(Storage::disk('public')->get('branding/logo-on-dark.svg'))
        ->toBe(file_get_contents(base_path('tests/Fixtures/brand/logo-on-dark.svg')));
});

it('keeps the current settings when nothing is configured', function (): void {
    config()->set('seeding.store', ['name' => '', 'logo_on_dark_path' => null, 'logo_on_light_path' => '']);

    $this->seed(BrandingSeeder::class);

    $settings = app(BrandingSettings::class)->refresh();

    expect($settings->store_name)->toBe(config('branding.store_name'))
        ->and($settings->logo_on_dark_path)->toBeNull()
        ->and($settings->logo_on_light_path)->toBeNull();

    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('skips a logo whose file does not exist', function (): void {
    config()->set('seeding.store', [
        'name' => null,
        'logo_on_dark_path' => base_path('tests/Fixtures/brand/does-not-exist.svg'),
        'logo_on_light_path' => null,
    ]);

    $this->seed(BrandingSeeder::class);

    expect(app(BrandingSettings::class)->refresh()->logo_on_dark_path)->toBeNull();
});
