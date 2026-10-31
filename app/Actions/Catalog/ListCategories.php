<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Enums\UnitStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class ListCategories
{
    /**
     * @return Collection<int, Category>
     */
    public function handle(): Collection
    {
        return Category::query()
            ->withCount(['units as available_units_count' => function (Builder $query): void {
                $query->where('status', UnitStatus::Available);
            }])
            ->withMin(['units as price_from_cents' => function (Builder $query): void {
                $query->where('status', UnitStatus::Available);
            }], 'price_pix_cents')
            ->orderBy('position')
            ->get();
    }
}
