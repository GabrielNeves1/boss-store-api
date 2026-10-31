<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum UnitStatus: string implements HasLabel
{
    use HasTranslatedLabel;

    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';

    /** Ordem de exibição: o que dá para comprar vem primeiro. */
    public function displayOrder(): int
    {
        return match ($this) {
            self::Available => 0,
            self::Reserved => 1,
            self::Sold => 2,
        };
    }
}
