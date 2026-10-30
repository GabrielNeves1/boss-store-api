<?php

declare(strict_types=1);

namespace App\Data\Branding;

use Spatie\LaravelData\Data;

final class BrandFontsData extends Data
{
    public function __construct(
        public readonly FontData $heading,
        public readonly FontData $body,
        public readonly FontData $mono,
    ) {}
}
