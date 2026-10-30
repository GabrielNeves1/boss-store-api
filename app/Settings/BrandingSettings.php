<?php

declare(strict_types=1);

namespace App\Settings;

use App\Data\Branding\BrandColorsData;
use App\Data\Branding\BrandFontsData;
use Spatie\LaravelSettings\Settings;

final class BrandingSettings extends Settings
{
    public string $store_name;

    public ?string $whatsapp;

    public string $locale;

    public string $currency;

    public ?string $logo_on_dark_path;

    public ?string $logo_on_light_path;

    public BrandColorsData $colors;

    public BrandFontsData $fonts;

    public string $radius;

    public static function group(): string
    {
        return 'branding';
    }
}
