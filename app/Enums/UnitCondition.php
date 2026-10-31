<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum UnitCondition: string implements HasLabel
{
    use HasTranslatedLabel;

    /** Lacrado de fábrica, nunca ativado: não há laudo de uso, só a caixa intacta. */
    case Sealed = 'sealed';
    case LikeNew = 'like_new';
    case Excellent = 'excellent';
    case Good = 'good';
}
