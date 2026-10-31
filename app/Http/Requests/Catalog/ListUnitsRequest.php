<?php

declare(strict_types=1);

namespace App\Http\Requests\Catalog;

use App\Data\Catalog\UnitFilters;
use App\Enums\UnitCondition;
use App\Enums\UnitSort;
use App\Enums\UnitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ListUnitsRequest extends FormRequest
{
    private const int CENTS_PER_UNIT = 100;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category' => ['sometimes', 'string', Rule::exists('categories', 'slug')],
            'storage' => ['sometimes', 'array'],
            'storage.*' => ['integer', 'min:1'],
            'color' => ['sometimes', 'array'],
            'color.*' => ['string', 'max:100'],
            'condition' => ['sometimes', 'array'],
            'condition.*' => [Rule::enum(UnitCondition::class)],
            'status' => ['sometimes', 'array'],
            'status.*' => [Rule::enum(UnitStatus::class)],
            'battery_min' => ['sometimes', 'integer', 'between:0,100'],
            'price_min' => ['sometimes', 'numeric', 'min:0'],
            'price_max' => ['sometimes', 'numeric', 'min:0', 'gte:price_min'],
            'sort' => ['sometimes', Rule::enum(UnitSort::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function filters(): UnitFilters
    {
        return new UnitFilters(
            categorySlug: $this->filled('category') ? $this->string('category')->toString() : null,
            storages: array_map('intval', $this->listOf('storage')),
            colors: array_map('strval', $this->listOf('color')),
            conditions: array_map(fn (mixed $value): UnitCondition => UnitCondition::from((string) $value), $this->listOf('condition')),
            statuses: array_map(fn (mixed $value): UnitStatus => UnitStatus::from((string) $value), $this->listOf('status')),
            batteryMin: $this->filled('battery_min') ? $this->integer('battery_min') : null,
            priceMinCents: $this->filled('price_min') ? (int) round($this->float('price_min') * self::CENTS_PER_UNIT) : null,
            priceMaxCents: $this->filled('price_max') ? (int) round($this->float('price_max') * self::CENTS_PER_UNIT) : null,
            sort: $this->enum('sort', UnitSort::class) ?? UnitSort::Relevance,
        );
    }

    /**
     * @return list<int|string>
     */
    private function listOf(string $key): array
    {
        $value = $this->input($key, []);

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, fn (mixed $item): bool => is_int($item) || is_string($item)));
    }
}
