<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Catalog\Media\ProcessUnitMedia;
use App\Enums\UnitCondition;
use App\Enums\UnitStatus;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Seeder;

/**
 * Categorias e unidades de exemplo do catálogo. A mídia só é gerada quando a
 * pasta de originais está montada (CATALOG_MEDIA_SOURCE_PATH).
 *
 * @phpstan-import-type MediaItem from ProcessUnitMedia
 *
 * @phpstan-type UnitSeed array{
 *     code: string, category: string, model: string, color: string, storage_gb: int|null,
 *     battery_health: int|null, condition: string, warranty_months: int|null, accessories: list<string>,
 *     price_pix_cents: int, price_card_cents: int, max_installments: int, status: string, featured: bool,
 *     media: list<MediaItem>,
 * }
 */
final class CatalogSeeder extends Seeder
{
    public function run(ProcessUnitMedia $processUnitMedia): void
    {
        /** @var list<array{slug: string, name: string, position: int}> $categories */
        $categories = require database_path('seeders/catalog/categories.php');

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }

        $sourceDirectory = config('catalog.media.source_path');
        $canRenderMedia = is_string($sourceDirectory) && is_dir($sourceDirectory);

        if (! $canRenderMedia) {
            $this->command?->warn(__('seeding.catalog_media_skipped'));
        }

        foreach (self::units() as $seed) {
            $unit = $this->upsertUnit($seed);

            if ($canRenderMedia) {
                $processUnitMedia->handle($unit, $seed['media'], $sourceDirectory);
            }
        }
    }

    /**
     * @return list<UnitSeed>
     */
    public static function units(): array
    {
        /** @var list<UnitSeed> $units */
        $units = require database_path('seeders/catalog/units.php');

        return $units;
    }

    /**
     * @param  UnitSeed  $seed
     */
    private function upsertUnit(array $seed): Unit
    {
        $category = Category::query()->where('slug', $seed['category'])->firstOrFail();

        return Unit::query()->updateOrCreate(['code' => $seed['code']], [
            'category_id' => $category->id,
            'model' => $seed['model'],
            'color' => $seed['color'],
            'storage_gb' => $seed['storage_gb'],
            'battery_health' => $seed['battery_health'],
            'condition' => UnitCondition::from($seed['condition']),
            'warranty_months' => $seed['warranty_months'],
            'accessories' => $seed['accessories'],
            'price_pix_cents' => $seed['price_pix_cents'],
            'price_card_cents' => $seed['price_card_cents'],
            'max_installments' => $seed['max_installments'],
            'status' => UnitStatus::from($seed['status']),
            'featured' => $seed['featured'],
        ]);
    }
}
