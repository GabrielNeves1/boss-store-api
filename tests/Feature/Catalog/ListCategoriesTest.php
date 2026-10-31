<?php

declare(strict_types=1);

use App\Enums\UnitStatus;
use App\Models\Category;
use App\Models\Unit;

it('lists categories in order with their available unit count and lowest available price', function (): void {
    $phones = Category::factory()->create(['slug' => 'phones', 'name' => 'Phones', 'position' => 2]);
    Category::factory()->create(['slug' => 'tablets', 'name' => 'Tablets', 'position' => 1]);

    Unit::factory()->for($phones)->create(['price_pix_cents' => 300_000]);
    Unit::factory()->for($phones)->create(['price_pix_cents' => 189_900]);
    Unit::factory()->for($phones)->status(UnitStatus::Sold)->create(['price_pix_cents' => 100_000]);

    $this->getJson(route('catalog.categories.index'))
        ->assertOk()
        ->assertExactJson(['data' => [
            ['slug' => 'tablets', 'name' => 'Tablets', 'available_units' => 0, 'price_from' => null],
            ['slug' => 'phones', 'name' => 'Phones', 'available_units' => 2, 'price_from' => 1899],
        ]]);
});
