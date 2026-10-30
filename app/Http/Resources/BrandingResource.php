<?php

declare(strict_types=1);

namespace App\Http\Resources;

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
     * @return array{
     *     store_name: string,
     *     whatsapp: string|null,
     *     locale: string,
     *     currency: string,
     *     logos: array{on_dark: string|null, on_light: string|null},
     *     colors: array{background: string, surface: string, foreground: string, muted: string, signal: string},
     *     fonts: array{
     *         heading: array{family: string, stylesheet_url: string},
     *         body: array{family: string, stylesheet_url: string},
     *         mono: array{family: string, stylesheet_url: string},
     *     },
     *     radius: string,
     * }
     */
    public function toArray(Request $request): array
    {
        $colors = $this->settings->colors;
        $fonts = $this->settings->fonts;

        return [
            'store_name' => $this->settings->store_name,
            'whatsapp' => $this->settings->whatsapp,
            'locale' => $this->settings->locale,
            'currency' => $this->settings->currency,
            'logos' => [
                /** @var string|null */
                'on_dark' => $this->logoUrl($this->settings->logo_on_dark_path),
                /** @var string|null */
                'on_light' => $this->logoUrl($this->settings->logo_on_light_path),
            ],
            'colors' => [
                'background' => $colors->background,
                'surface' => $colors->surface,
                'foreground' => $colors->foreground,
                'muted' => $colors->muted,
                'signal' => $colors->signal,
            ],
            'fonts' => [
                'heading' => $this->font($fonts->heading),
                'body' => $this->font($fonts->body),
                'mono' => $this->font($fonts->mono),
            ],
            'radius' => $this->settings->radius,
        ];
    }

    private function logoUrl(?string $path): ?string
    {
        return $path === null ? null : Storage::disk('public')->url($path);
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
