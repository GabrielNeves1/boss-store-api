<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum UnitCondition: string implements HasLabel
{
    use HasTranslatedLabel;

    case LikeNew = 'like_new';
    case Excellent = 'excellent';
    case Good = 'good';
}
