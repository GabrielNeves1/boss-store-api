<?php

declare(strict_types=1);

namespace Tests\Fixtures\Enums;

use App\Contracts\HasLabel;
use App\Enums\Concerns\HasTranslatedLabel;

enum SampleStatus: string implements HasLabel
{
    use HasTranslatedLabel;

    case Active = 'active';
    case Archived = 'archived';
}
