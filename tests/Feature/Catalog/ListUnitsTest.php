<?php

declare(strict_types=1);

use App\Enums\UnitCondition;
use App\Enums\UnitStatus;
use App\Models\Category;
use App\Models\Unit;

beforeEach(function (): void {
    $this->phones = Category::factory()->create(['slug' => 'phones']);
});

it('lists available units first, then reserved, then sold', function (): void {
    $sold = Unit::factory()->for($this->phones)->status(UnitStatus::Sold)->create();
    $reserved = Unit::factory()->for($this->phones)->status(UnitStatus::Reserved)->create();
    $available = Unit::factory()->for($this->phones)->create();

    $this->getJson(route('catalog.units.index', ['category' => 'phones']))
        ->assertOk()
        ->assertJsonPath('data.*.code', [$available->code, $reserved->code, $sold->code])
        ->assertJsonPath('meta.total', 3);
});

it('filters by storage, color, condition, battery, price and status', function (): void {
    $match = Unit::factory()->for($this->phones)->create([
        'storage_gb' => 256, 'color' => 'Azul', 'condition' => UnitCondition::Excellent,
        'battery_health' => 92, 'price_pix_cents' => 400_000,
    ]);
    Unit::factory()->for($this->phones)->create(['storage_gb' => 128, 'color' => 'Azul', 'condition' => UnitCondition::Excellent, 'battery_health' => 92, 'price_pix_cents' => 400_000]);
    Unit::factory()->for($this->phones)->create(['storage_gb' => 256, 'color' => 'Azul', 'condition' => UnitCondition::Good, 'battery_health' => 92, 'price_pix_cents' => 400_000]);
    Unit::factory()->for($this->phones)->create(['storage_gb' => 256, 'color' => 'Azul', 'condition' => UnitCondition::Excellent, 'battery_health' => 70, 'price_pix_cents' => 400_000]);
    Unit::factory()->for($this->phones)->create(['storage_gb' => 256, 'color' => 'Azul', 'condition' => UnitCondition::Excellent, 'battery_health' => 92, 'price_pix_cents' => 900_000]);

    $this->getJson(route('catalog.units.index', [
        'category' => 'phones',
        'storage' => [256],
        'color' => ['Azul'],
        'condition' => ['excellent'],
        'status' => ['available'],
        'battery_min' => 85,
        'price_min' => 3000,
        'price_max' => 5000,
    ]))
        ->assertOk()
        ->assertJsonPath('data.*.code', [$match->code]);
});

it('sorts by price', function (): void {
    $cheap = Unit::factory()->for($this->phones)->create(['price_pix_cents' => 100_000]);
    $expensive = Unit::factory()->for($this->phones)->create(['price_pix_cents' => 500_000]);

    $this->getJson(route('catalog.units.index', ['sort' => 'price_desc']))
        ->assertJsonPath('data.*.code', [$expensive->code, $cheap->code]);
});

it('exposes price in pix and installments with the rounded-up installment', function (): void {
    Unit::factory()->for($this->phones)->create(['price_pix_cents' => 459_900, 'price_card_cents' => 489_900, 'max_installments' => 12]);

    $this->getJson(route('catalog.units.index'))
        ->assertJsonPath('data.0.price', ['pix' => 4599, 'card' => 4899, 'installments' => ['count' => 12, 'amount' => 408.25]]);
});

it('validates the filters', function (): void {
    $this->getJson(route('catalog.units.index', [
        'category' => 'unknown', 'condition' => ['broken'], 'battery_min' => 120, 'sort' => 'random',
        'price_min' => 50, 'price_max' => 10,
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category', 'condition.0', 'battery_min', 'sort', 'price_max']);
});
