<?php

declare(strict_types=1);

namespace App\Http\Resources\Catalog;

use App\Enums\UnitCondition;
use App\Enums\UnitSort;
use App\Enums\UnitStatus;
use App\Http\Resources\EnumResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
final class CategoryFiltersResource extends JsonResource
{
    private const int CENTS_PER_UNIT = 100;

    /**
     * @param  array{
     *     unit_count: int,
     *     storages: list<int>,
     *     colors: list<string>,
     *     conditions: list<UnitCondition>,
     *     price_min_cents: int|null,
     *     price_max_cents: int|null,
     *     battery_min: int|null,
     *     battery_max: int|null,
     * }  $filters
     */
    public function __construct(private readonly Category $category, private readonly array $filters)
    {
        parent::__construct($category);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->category->slug,
            'name' => $this->category->name,
            'unit_count' => $this->filters['unit_count'],
            'filters' => [
                /** @var list<int> */
                'storages' => $this->filters['storages'],
                /** @var list<string> */
                'colors' => $this->filters['colors'],
                /** @var list<EnumResource> */
                'conditions' => array_map(fn (UnitCondition $condition): EnumResource => new EnumResource($condition), $this->filters['conditions']),
                /** @var list<EnumResource> */
                'statuses' => array_map(fn (UnitStatus $status): EnumResource => new EnumResource($status), UnitStatus::cases()),
                /** @var list<EnumResource> */
                'sorts' => array_map(fn (UnitSort $sort): EnumResource => new EnumResource($sort), UnitSort::cases()),
                /** @var float|null */
                'price_min' => $this->filters['price_min_cents'] === null ? null : $this->filters['price_min_cents'] / self::CENTS_PER_UNIT,
                /** @var float|null */
                'price_max' => $this->filters['price_max_cents'] === null ? null : $this->filters['price_max_cents'] / self::CENTS_PER_UNIT,
                'battery_min' => $this->filters['battery_min'],
                'battery_max' => $this->filters['battery_max'],
            ],
        ];
    }
}
