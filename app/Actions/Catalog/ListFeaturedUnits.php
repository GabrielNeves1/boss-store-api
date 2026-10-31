<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Enums\UnitStatus;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

final class ListFeaturedUnits
{
    /**
     * Destaques do mais valioso para o mais acessível: o primeiro abre a vitrine.
     *
     * @return Collection<int, Unit>
     */
    public function handle(): Collection
    {
        return Unit::query()
            ->with(['category', 'media'])
            ->where('featured', true)
            ->where('status', '!=', UnitStatus::Sold)
            ->orderByDesc('price_pix_cents')
            ->get();
    }
}
