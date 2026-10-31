<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Data\Catalog\UnitFilters;
use App\Enums\UnitSort;
use App\Enums\UnitStatus;
use App\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

final class ListUnits
{
    /**
     * @return LengthAwarePaginator<int, Unit>
     */
    public function handle(UnitFilters $filters): LengthAwarePaginator
    {
        $query = Unit::query()->with(['category', 'media']);

        $this->applyFilters($query, $filters);
        $this->applySort($query, $filters->sort);

        return $query->paginate(Config::integer('catalog.per_page'))->withQueryString();
    }

    /**
     * @param  Builder<Unit>  $query
     */
    private function applyFilters(Builder $query, UnitFilters $filters): void
    {
        if ($filters->categorySlug !== null) {
            $query->whereHas('category', fn (Builder $category) => $category->where('slug', $filters->categorySlug));
        }

        if ($filters->storages !== []) {
            $query->whereIn('storage_gb', $filters->storages);
        }

        if ($filters->colors !== []) {
            $query->whereIn('color', $filters->colors);
        }

        if ($filters->conditions !== []) {
            $query->whereIn('condition', $filters->conditions);
        }

        if ($filters->statuses !== []) {
            $query->whereIn('status', $filters->statuses);
        }

        if ($filters->batteryMin !== null) {
            $query->where('battery_health', '>=', $filters->batteryMin);
        }

        if ($filters->priceMinCents !== null) {
            $query->where('price_pix_cents', '>=', $filters->priceMinCents);
        }

        if ($filters->priceMaxCents !== null) {
            $query->where('price_pix_cents', '<=', $filters->priceMaxCents);
        }
    }

    /**
     * @param  Builder<Unit>  $query
     */
    private function applySort(Builder $query, UnitSort $sort): void
    {
        $byDisplayOrder = UnitStatus::cases();
        usort($byDisplayOrder, fn (UnitStatus $a, UnitStatus $b): int => $a->displayOrder() <=> $b->displayOrder());

        $query->orderByRaw(
            'CASE status WHEN ? THEN 0 WHEN ? THEN 1 WHEN ? THEN 2 ELSE 3 END',
            array_map(fn (UnitStatus $status): string => $status->value, $byDisplayOrder),
        );

        match ($sort) {
            UnitSort::Relevance => $query->orderByDesc('featured')->orderByDesc('id'),
            UnitSort::PriceAsc => $query->orderBy('price_pix_cents'),
            UnitSort::PriceDesc => $query->orderByDesc('price_pix_cents'),
            UnitSort::BatteryDesc => $query->orderByDesc('battery_health'),
        };
    }
}
