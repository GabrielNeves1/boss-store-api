<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Catalog\Media\ProcessUnitMedia;
use App\Models\Unit;
use Database\Seeders\CatalogSeeder;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'catalog:media', description: 'Regera a mídia das unidades de exemplo a partir dos originais')]
final class RenderCatalogMediaCommand extends Command
{
    protected $signature = 'catalog:media {code? : Código de uma unidade; sem ele, todas}';

    public function handle(ProcessUnitMedia $processUnitMedia): int
    {
        $sourceDirectory = config('catalog.media.source_path');

        if (! is_string($sourceDirectory) || ! is_dir($sourceDirectory)) {
            $this->error(__('seeding.catalog_media_skipped'));

            return self::FAILURE;
        }

        $code = $this->argument('code');

        foreach (CatalogSeeder::units() as $seed) {
            if (is_string($code) && $code !== $seed['code']) {
                continue;
            }

            $unit = Unit::query()->where('code', $seed['code'])->firstOrFail();
            $processUnitMedia->handle($unit, $seed['media'], $sourceDirectory);
            $this->info($seed['code']);
        }

        return self::SUCCESS;
    }
}
