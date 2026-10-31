<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UnitCondition;
use App\Enums\UnitStatus;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property int $category_id
 * @property string $model
 * @property string $color
 * @property int|null $storage_gb
 * @property int|null $battery_health
 * @property UnitCondition $condition
 * @property int|null $warranty_months
 * @property list<string> $accessories
 * @property int $price_pix_cents
 * @property int $price_card_cents
 * @property int $max_installments
 * @property UnitStatus $status
 * @property bool $featured
 * @property-read Category $category
 */
#[Fillable([
    'code', 'category_id', 'model', 'color', 'storage_gb', 'battery_health', 'condition',
    'warranty_months', 'accessories', 'price_pix_cents', 'price_card_cents', 'max_installments',
    'status', 'featured',
])]
final class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<UnitMedia, $this>
     */
    public function media(): HasMany
    {
        return $this->hasMany(UnitMedia::class)->orderBy('position');
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'condition' => UnitCondition::class,
            'status' => UnitStatus::class,
            'accessories' => 'array',
            'featured' => 'boolean',
            'storage_gb' => 'integer',
            'battery_health' => 'integer',
            'warranty_months' => 'integer',
            'price_pix_cents' => 'integer',
            'price_card_cents' => 'integer',
            'max_installments' => 'integer',
        ];
    }
}
