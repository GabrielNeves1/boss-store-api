<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/*
 * Pedido do dono: layout de e-commerce menos chapado, com cantos arredondados.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('branding.radius', fn (): mixed => config('branding.radius'));
    }
};
