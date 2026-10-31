<?php

declare(strict_types=1);

use App\Enums\UnitStatus;
use App\Models\Category;
use App\Models\Unit;

it('lists categories in order with their available unit count', function (): void {
    $phones = Category::factory()->create(['slug' => 'phones', 'name' => 'Phones', 'position' => 2]);
    Category::factory()->create(['slug' => 'tablets', 'name' => 'Tablets', 'position' => 1]);

    Unit::factory()->for($phones)->count(2)->create();
    Unit::factory()->for($phones)->status(UnitStatus::Sold)->create();

    $this->getJson(route('catalog.categories.index'))
        ->assertOk()
        ->assertExactJson(['data' => [
            ['slug' => 'tablets', 'name' => 'Tablets', 'available_units' => 0],
            ['slug' => 'phones', 'name' => 'Phones', 'available_units' => 2],
        ]]);
});
