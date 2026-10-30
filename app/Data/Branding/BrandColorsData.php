<?php

declare(strict_types=1);

namespace App\Data\Branding;

use Spatie\LaravelData\Data;

final class BrandColorsData extends Data
{
    public function __construct(
        public readonly string $background,
        public readonly string $surface,
        public readonly string $foreground,
        public readonly string $muted,
        public readonly string $signal,
    ) {}
}
