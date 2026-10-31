<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Enums\UnitCondition;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

/**
 * Opções de filtro que existem de fato nas unidades de uma categoria, para o
 * front nunca oferecer um filtro que não devolve nada.
 */
final class DescribeCategoryFilters
{
    /**
     * @return array{
     *     unit_count: int,
     *     storages: list<int>,
     *     colors: list<string>,
     *     conditions: list<UnitCondition>,
     *     price_min_cents: int|null,
     *     price_max_cents: int|null,
     *     battery_min: int|null,
     *     battery_max: int|null,
     * }
     */
    public function handle(Category $category): array
    {
        $units = fn (): Builder => Unit::query()->where('category_id', $category->id);

        $storages = array_values(array_map(
            fn (int|float|string $value): int => (int) $value,
            array_filter($units()->whereNotNull('storage_gb')->distinct()->orderBy('storage_gb')->pluck('storage_gb')->all(), 'is_numeric'),
        ));

        $colors = array_values(array_filter(
            $units()->distinct()->orderBy('color')->pluck('color')->all(),
            'is_string',
        ));

        $presentConditions = $units()->distinct()->pluck('condition')
            ->map(fn (mixed $value): ?string => $value instanceof UnitCondition ? $value->value : (is_string($value) ? $value : null))
            ->all();

        $conditions = array_values(array_filter(
            UnitCondition::cases(),
            fn (UnitCondition $condition): bool => in_array($condition->value, $presentConditions, true),
        ));

        return [
            'unit_count' => $units()->count(),
            'storages' => $storages,
            'colors' => $colors,
            'conditions' => $conditions,
            'price_min_cents' => $this->intOrNull($units()->min('price_pix_cents')),
            'price_max_cents' => $this->intOrNull($units()->max('price_pix_cents')),
            'battery_min' => $this->intOrNull($units()->min('battery_health')),
            'battery_max' => $this->intOrNull($units()->max('battery_health')),
        ];
    }

    private function intOrNull(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
