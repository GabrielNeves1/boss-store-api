<?php

declare(strict_types=1);

use App\Http\Resources\EnumResource;
use Illuminate\Support\Facades\Lang;
use Tests\Fixtures\Enums\SampleStatus;

it('exposes an enum as value and label', function (): void {
    Lang::addLines(['enums.sample_status.active' => 'Ativo'], 'pt_BR');

    expect((new EnumResource(SampleStatus::Active))->resolve())->toBe([
        'value' => 'active',
        'label' => 'Ativo',
    ]);
});
