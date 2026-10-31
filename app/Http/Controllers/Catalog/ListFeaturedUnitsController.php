<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Actions\Catalog\ListFeaturedUnits;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\UnitSummaryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListFeaturedUnitsController extends Controller
{
    public function __invoke(ListFeaturedUnits $listFeaturedUnits): AnonymousResourceCollection
    {
        return UnitSummaryResource::collection($listFeaturedUnits->handle());
    }
}
