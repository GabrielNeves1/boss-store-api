<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/*
 * Redesign do catálogo: duas paletas (clara e escura) com papéis novos,
 * Epilogue no lugar de Archivo e IBM Plex Mono, cantos retos e os campos do
 * vídeo de abertura (vazios até o vídeo existir).
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('branding.colors');
        $this->migrator->add('branding.colors_dark', config('branding.colors_dark'));
        $this->migrator->add('branding.colors_light', config('branding.colors_light'));
        $this->migrator->update('branding.fonts', fn (): mixed => config('branding.fonts'));
        $this->migrator->update('branding.radius', fn (): mixed => config('branding.radius'));
        $this->migrator->add('branding.opening_video_webm_path', null);
        $this->migrator->add('branding.opening_video_mp4_path', null);
        $this->migrator->add('branding.opening_poster_path', null);
    }
};
