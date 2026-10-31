<?php

declare(strict_types=1);

use App\Actions\Catalog\Media\ProcessUnitMedia;
use App\Enums\MediaType;
use App\Models\Unit;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
    $this->source = sys_get_temp_dir().'/catalog-test-'.uniqid();
    mkdir($this->source);

    $image = new Imagick;
    $image->newImage(1200, 1600, 'navy');
    $image->setImageFormat('jpeg');
    $image->writeImage($this->source.'/photo.jpg');

    Process::run(['ffmpeg', '-v', 'error', '-y', '-f', 'lavfi', '-i', 'testsrc=size=240x320:rate=10', '-t', '1', '-pix_fmt', 'yuv420p', $this->source.'/clip.mp4'])->throw();
});

afterEach(function (): void {
    array_map('unlink', glob($this->source.'/*') ?: []);
    rmdir($this->source);
});

it('renders cropped webp variants, frames and video loops without touching the originals', function (): void {
    $unit = Unit::factory()->create(['code' => 'BAS-TEST']);
    $before = md5_file($this->source.'/photo.jpg');

    app(ProcessUnitMedia::class)->handle($unit, [
        ['kind' => 'photo', 'source' => 'photo.jpg', 'focal' => [0.5, 0.5], 'alt' => 'Foto'],
        ['kind' => 'frame', 'source' => 'clip.mp4', 'at' => 0.5, 'alt' => 'Quadro'],
        ['kind' => 'video', 'source' => 'clip.mp4', 'at' => 0.5, 'alt' => 'Vídeo'],
    ], $this->source);

    $media = $unit->media()->get();

    expect($media)->toHaveCount(3)
        ->and($media[0]->type)->toBe(MediaType::Photo)
        ->and(array_column($media[0]->variants, 'width'))->toBe([480, 768, 1080, 1200])
        ->and($media[0]->width / $media[0]->height)->toBe(0.8)
        ->and($media[1]->type)->toBe(MediaType::Photo)
        ->and(array_column($media[1]->variants, 'width'))->toBe([240])
        ->and($media[2]->type)->toBe(MediaType::Video)
        ->and(array_column($media[2]->sources ?? [], 'type'))->toBe(['video/webm', 'video/mp4'])
        ->and(md5_file($this->source.'/photo.jpg'))->toBe($before);

    Storage::disk('public')->assertExists('catalog/BAS-TEST/01-1200.webp');
    Storage::disk('public')->assertExists('catalog/BAS-TEST/03.webm');
    Storage::disk('public')->assertExists('catalog/BAS-TEST/03.mp4');
    Storage::disk('public')->assertExists('catalog/BAS-TEST/03-poster-240.webp');
});

it('fails clearly when an original is missing', function (): void {
    $unit = Unit::factory()->create();

    app(ProcessUnitMedia::class)->handle($unit, [
        ['kind' => 'photo', 'source' => 'missing.jpg', 'alt' => 'Foto'],
    ], $this->source);
})->throws(InvalidArgumentException::class);
