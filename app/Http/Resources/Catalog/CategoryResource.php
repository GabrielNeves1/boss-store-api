<?php

declare(strict_types=1);

namespace App\Http\Resources\Catalog;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
final class CategoryResource extends JsonResource
{
    public function __construct(private readonly Category $category)
    {
        parent::__construct($category);
    }

    private const int CENTS_PER_UNIT = 100;

    /**
     * @return array{slug: string, name: string, available_units: int, price_from: float|null}
     */
    public function toArray(Request $request): array
    {
        $count = $this->category->getAttribute('available_units_count');
        $priceFrom = $this->category->getAttribute('price_from_cents');

        return [
            'slug' => $this->category->slug,
            'name' => $this->category->name,
            'available_units' => is_numeric($count) ? (int) $count : 0,
            'price_from' => is_numeric($priceFrom) ? (int) $priceFrom / self::CENTS_PER_UNIT : null,
        ];
    }
}
