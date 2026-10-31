<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Actions\Catalog\ListUnits;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ListUnitsRequest;
use App\Http\Resources\Catalog\UnitSummaryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListUnitsController extends Controller
{
    public function __invoke(ListUnitsRequest $request, ListUnits $listUnits): AnonymousResourceCollection
    {
        return UnitSummaryResource::collection($listUnits->handle($request->filters()));
    }
}
