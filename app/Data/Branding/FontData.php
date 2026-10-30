<?php

declare(strict_types=1);

namespace App\Data\Branding;

use Spatie\LaravelData\Data;

final class FontData extends Data
{
    public function __construct(
        public readonly string $family,
        public readonly string $stylesheet,
    ) {}
}
