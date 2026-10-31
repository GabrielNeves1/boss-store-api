<?php

declare(strict_types=1);

use App\Enums\UnitStatus;
use App\Models\Unit;

it('lists featured units that are not sold', function (): void {
    $featured = Unit::factory()->featured()->create();
    Unit::factory()->featured()->status(UnitStatus::Sold)->create();
    Unit::factory()->create();

    $this->getJson(route('catalog.units.featured'))
        ->assertOk()
        ->assertJsonPath('data.*.code', [$featured->code])
        ->assertJsonPath('data.0.media', []);
});

it('lists the most valuable featured unit first', function (): void {
    $cheap = Unit::factory()->featured()->create(['price_pix_cents' => 100_000]);
    $premium = Unit::factory()->featured()->create(['price_pix_cents' => 600_000]);

    $this->getJson(route('catalog.units.featured'))
        ->assertJsonPath('data.*.code', [$premium->code, $cheap->code]);
});
