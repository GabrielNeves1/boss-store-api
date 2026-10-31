<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Actions\Catalog\DescribeCategoryFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\CategoryFiltersResource;
use App\Models\Category;

final class ShowCategoryController extends Controller
{
    public function __invoke(Category $category, DescribeCategoryFilters $describeFilters): CategoryFiltersResource
    {
        return new CategoryFiltersResource($category, $describeFilters->handle($category));
    }
}
