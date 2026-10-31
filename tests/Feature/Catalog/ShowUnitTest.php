<?php

declare(strict_types=1);

use App\Enums\MediaType;
use App\Models\Unit;
use App\Models\UnitMedia;

it('shows a unit with its report and media urls', function (): void {
    $unit = Unit::factory()->create(['code' => 'BAS-9000', 'battery_health' => 91, 'warranty_months' => 3, 'accessories' => ['Caixa original']]);

    UnitMedia::query()->create([
        'unit_id' => $unit->id, 'type' => MediaType::Video, 'position' => 1, 'alt' => 'Vídeo', 'width' => 720, 'height' => 960,
        'variants' => [['width' => 720, 'path' => 'catalog/BAS-9000/02-poster-720.webp']],
        'sources' => [['type' => 'video/mp4', 'path' => 'catalog/BAS-9000/02.mp4']],
    ]);
    UnitMedia::query()->create([
        'unit_id' => $unit->id, 'type' => MediaType::Photo, 'position' => 0, 'alt' => 'Foto', 'width' => 2048, 'height' => 2560,
        'variants' => [['width' => 480, 'path' => 'catalog/BAS-9000/01-480.webp']], 'sources' => null,
    ]);

    $this->getJson(route('catalog.units.show', 'BAS-9000'))
        ->assertOk()
        ->assertJsonPath('data.code', 'BAS-9000')
        ->assertJsonPath('data.battery_health', 91)
        ->assertJsonPath('data.warranty_months', 3)
        ->assertJsonPath('data.accessories', ['Caixa original'])
        ->assertJsonPath('data.condition.label', $unit->condition->label())
        ->assertJsonPath('data.media.0.type', 'photo')
        ->assertJsonPath('data.media.0.sources', [])
        ->assertJsonPath('data.media.0.variants.0.width', 480)
        ->assertJsonPath('data.media.0.variants.0.url', fn (string $url): bool => str_ends_with($url, '/storage/catalog/BAS-9000/01-480.webp'))
        ->assertJsonPath('data.media.1.type', 'video')
        ->assertJsonPath('data.media.1.sources.0.type', 'video/mp4');
});

it('returns 404 for an unknown unit', function (): void {
    $this->getJson(route('catalog.units.show', 'nope'))->assertNotFound();
});
