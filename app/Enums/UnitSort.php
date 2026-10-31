<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum UnitSort: string implements HasLabel
{
    use HasTranslatedLabel;

    case Relevance = 'relevance';
    case PriceAsc = 'price_asc';
    case PriceDesc = 'price_desc';
    case BatteryDesc = 'battery_desc';
}
