<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Actions\Catalog\ListCategories;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListCategoriesController extends Controller
{
    public function __invoke(ListCategories $listCategories): AnonymousResourceCollection
    {
        return CategoryResource::collection($listCategories->handle());
    }
}
