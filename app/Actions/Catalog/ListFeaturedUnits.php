<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Enums\UnitStatus;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

final class ListFeaturedUnits
{
    /**
     * @return Collection<int, Unit>
     */
    public function handle(): Collection
    {
        return Unit::query()
            ->with(['category', 'media'])
            ->where('featured', true)
            ->where('status', '!=', UnitStatus::Sold)
            ->orderByDesc('id')
            ->get();
    }
}
