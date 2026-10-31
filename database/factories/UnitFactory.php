<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UnitCondition;
use App\Enums\UnitStatus;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
final class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pix = fake()->numberBetween(150_000, 900_000);

        return [
            'code' => fake()->unique()->bothify('UN-####'),
            'category_id' => Category::factory(),
            'model' => fake()->words(2, true),
            'color' => fake()->colorName(),
            'storage_gb' => fake()->randomElement([64, 128, 256, 512]),
            'battery_health' => fake()->numberBetween(78, 100),
            'condition' => fake()->randomElement(UnitCondition::cases()),
            'warranty_months' => fake()->randomElement([null, 3, 6]),
            'accessories' => [],
            'price_pix_cents' => $pix,
            'price_card_cents' => (int) round($pix * 1.07),
            'max_installments' => 12,
            'status' => UnitStatus::Available,
            'featured' => false,
        ];
    }

    public function status(UnitStatus $status): self
    {
        return $this->state(['status' => $status]);
    }

    public function featured(): self
    {
        return $this->state(['featured' => true]);
    }
}
