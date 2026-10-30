<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('branding.store_name', config('branding.store_name'));
        $this->migrator->add('branding.whatsapp', config('branding.whatsapp'));
        $this->migrator->add('branding.locale', config('branding.locale'));
        $this->migrator->add('branding.currency', config('branding.currency'));
        $this->migrator->add('branding.logo_on_dark_path', null);
        $this->migrator->add('branding.logo_on_light_path', null);
        $this->migrator->add('branding.colors', config('branding.colors'));
        $this->migrator->add('branding.fonts', config('branding.fonts'));
        $this->migrator->add('branding.radius', config('branding.radius'));
    }
};
