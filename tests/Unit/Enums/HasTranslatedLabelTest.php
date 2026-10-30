<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Lang;
use Tests\Fixtures\Enums\SampleStatus;

beforeEach(function (): void {
    Lang::addLines([
        'enums.sample_status.active' => 'Ativo',
        'enums.sample_status.archived' => 'Arquivado',
    ], 'pt_BR');
});

it('reads the label from the enums language file', function (): void {
    expect(SampleStatus::Active->label())->toBe('Ativo')
        ->and(SampleStatus::Archived->label())->toBe('Arquivado');
});

it('falls back to the translation key when the label is missing', function (): void {
    app()->setLocale('en');

    expect(SampleStatus::Active->label())->toBe('enums.sample_status.active');
});

it('lists every case with value and label', function (): void {
    expect(SampleStatus::options())->toBe([
        ['value' => 'active', 'label' => 'Ativo'],
        ['value' => 'archived', 'label' => 'Arquivado'],
    ]);
});
