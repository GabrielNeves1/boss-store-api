<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Settings\BrandingSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

final class BrandingSeeder extends Seeder
{
    public function __construct(private readonly BrandingSettings $settings) {}

    public function run(): void
    {
        $storeName = config('seeding.store.name');

        if (is_string($storeName) && $storeName !== '') {
            $this->settings->store_name = $storeName;
        }

        $this->settings->logo_on_dark_path = $this->storeLogo(config('seeding.store.logo_on_dark_path'), 'logo-on-dark.svg')
            ?? $this->settings->logo_on_dark_path;

        $this->settings->logo_on_light_path = $this->storeLogo(config('seeding.store.logo_on_light_path'), 'logo-on-light.svg')
            ?? $this->settings->logo_on_light_path;

        $this->settings->save();
    }

    /**
     * Copies a logo from the configured source path to the public disk and
     * returns its path there, or null when there is nothing to copy.
     */
    private function storeLogo(mixed $sourcePath, string $fileName): ?string
    {
        if (! is_string($sourcePath) || $sourcePath === '') {
            return null;
        }

        $contents = is_file($sourcePath) ? file_get_contents($sourcePath) : false;

        if ($contents === false) {
            $this->command?->warn(__('seeding.logo_missing', ['path' => $sourcePath]));

            return null;
        }

        $path = 'branding/'.$fileName;

        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
