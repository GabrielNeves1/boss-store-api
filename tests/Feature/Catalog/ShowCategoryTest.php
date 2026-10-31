<?php

declare(strict_types=1);

use App\Enums\UnitCondition;
use App\Models\Category;
use App\Models\Unit;

it('describes only the filter options that exist in the category', function (): void {
    $category = Category::factory()->create(['slug' => 'phones']);

    Unit::factory()->for($category)->create(['storage_gb' => 256, 'color' => 'Azul', 'condition' => UnitCondition::Excellent, 'price_pix_cents' => 300_000, 'battery_health' => 90]);
    Unit::factory()->for($category)->create(['storage_gb' => 128, 'color' => 'Rosa', 'condition' => UnitCondition::Good, 'price_pix_cents' => 150_000, 'battery_health' => 80]);
    Unit::factory()->create(['storage_gb' => 1024]);

    $this->getJson(route('catalog.categories.show', 'phones'))
        ->assertOk()
        ->assertJsonPath('data.unit_count', 2)
        ->assertJsonPath('data.filters.storages', [128, 256])
        ->assertJsonPath('data.filters.colors', ['Azul', 'Rosa'])
        ->assertJsonPath('data.filters.conditions.0.value', 'excellent')
        ->assertJsonPath('data.filters.conditions.1.value', 'good')
        ->assertJsonPath('data.filters.price_min', 1500)
        ->assertJsonPath('data.filters.price_max', 3000)
        ->assertJsonPath('data.filters.battery_min', 80)
        ->assertJsonPath('data.filters.battery_max', 90)
        ->assertJsonCount(3, 'data.filters.statuses')
        ->assertJsonCount(4, 'data.filters.sorts');
});

it('returns 404 for an unknown category', function (): void {
    $this->getJson(route('catalog.categories.show', 'nope'))->assertNotFound();
});

it('lists sealed units first among the conditions', function (): void {
    $category = Category::factory()->create(['slug' => 'phones']);

    Unit::factory()->for($category)->create(['condition' => UnitCondition::Good]);
    Unit::factory()->for($category)->create(['condition' => UnitCondition::Sealed, 'battery_health' => null]);

    $this->getJson(route('catalog.categories.show', 'phones'))
        ->assertJsonPath('data.filters.conditions.0', ['value' => 'sealed', 'label' => 'Lacrado'])
        ->assertJsonPath('data.filters.conditions.1.value', 'good');
});
