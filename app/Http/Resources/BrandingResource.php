<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Data\Branding\BrandColorsData;
use App\Data\Branding\FontData;
use App\Settings\BrandingSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin BrandingSettings
 */
final class BrandingResource extends JsonResource
{
    public function __construct(private readonly BrandingSettings $settings)
    {
        parent::__construct($settings);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'store_name' => $this->settings->store_name,
            'whatsapp' => $this->settings->whatsapp,
            'locale' => $this->settings->locale,
            'currency' => $this->settings->currency,
            'logos' => [
                /** @var string|null */
                'on_dark' => $this->publicUrl($this->settings->logo_on_dark_path),
                /** @var string|null */
                'on_light' => $this->publicUrl($this->settings->logo_on_light_path),
            ],
            'colors' => [
                'dark' => $this->palette($this->settings->colors_dark),
                'light' => $this->palette($this->settings->colors_light),
            ],
            'fonts' => [
                'heading' => $this->font($this->settings->fonts->heading),
                'body' => $this->font($this->settings->fonts->body),
            ],
            'radius' => $this->settings->radius,
            'opening' => [
                /** @var string|null */
                'video_webm' => $this->publicUrl($this->settings->opening_video_webm_path),
                /** @var string|null */
                'video_mp4' => $this->publicUrl($this->settings->opening_video_mp4_path),
                /** @var string|null */
                'poster' => $this->publicUrl($this->settings->opening_poster_path),
            ],
        ];
    }

    private function publicUrl(?string $path): ?string
    {
        return $path === null ? null : Storage::disk('public')->url($path);
    }

    /**
     * @return array{background: string, surface: string, foreground: string, muted: string, accent: string, accent_foreground: string}
     */
    private function palette(BrandColorsData $colors): array
    {
        return [
            'background' => $colors->background,
            'surface' => $colors->surface,
            'foreground' => $colors->foreground,
            'muted' => $colors->muted,
            'accent' => $colors->accent,
            'accent_foreground' => $colors->accent_foreground,
        ];
    }

    /**
     * @return array{family: string, stylesheet_url: string}
     */
    private function font(FontData $font): array
    {
        return [
            'family' => $font->family,
            'stylesheet_url' => route('fonts.show', ['path' => $font->stylesheet]),
        ];
    }
}
