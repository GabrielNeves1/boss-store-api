<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum MediaType: string implements HasLabel
{
    use HasTranslatedLabel;

    case Photo = 'photo';
    case Video = 'video';
}
