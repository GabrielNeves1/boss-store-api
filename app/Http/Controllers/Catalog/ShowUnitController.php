<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\UnitResource;
use App\Models\Unit;

final class ShowUnitController extends Controller
{
    public function __invoke(Unit $unit): UnitResource
    {
        return new UnitResource($unit->load(['category', 'media']));
    }
}
