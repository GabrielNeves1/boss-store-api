<?php

declare(strict_types=1);

namespace App\Actions\Catalog\Media;

use App\Enums\MediaType;
use App\Models\Unit;
use App\Models\UnitMedia;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Recria a mídia de uma unidade a partir dos originais: fotos, quadros
 * extraídos de vídeos e loops de vídeo mudos com poster. Os originais são só
 * lidos; os derivados vão para o disco público.
 *
 * @phpstan-type MediaItem array{
 *     kind: 'photo'|'frame'|'video',
 *     source: string,
 *     alt: string,
 *     focal?: array{0: float, 1: float},
 *     at?: float,
 * }
 */
final class ProcessUnitMedia
{
    /** Quadro-chave frequente para o vídeo responder bem quando o front controla o tempo pelo scroll ou arraste. */
    private const int KEYFRAME_INTERVAL = 6;

    public function __construct(
        private readonly RenderPhotoVariants $renderPhotoVariants,
        private readonly RunFfmpeg $runFfmpeg,
    ) {}

    /**
     * @param  list<MediaItem>  $items
     */
    public function handle(Unit $unit, array $items, string $sourceDirectory): void
    {
        $disk = Storage::disk(Config::string('catalog.media.disk'));
        $directory = sprintf('%s/%s', Config::string('catalog.media.directory'), $unit->code);

        $disk->deleteDirectory($directory);

        $records = [];

        foreach ($items as $position => $item) {
            $source = rtrim($sourceDirectory, '/').'/'.$item['source'];

            if (! is_file($source)) {
                throw new InvalidArgumentException(sprintf('Original não encontrado: %s', $source));
            }

            $records[] = ['position' => $position, 'alt' => $item['alt'], ...$this->render($item, $source, $directory, $position, $disk)];
        }

        DB::transaction(function () use ($unit, $records): void {
            $unit->media()->delete();

            foreach ($records as $record) {
                UnitMedia::query()->create(['unit_id' => $unit->id, ...$record]);
            }
        });
    }

    /**
     * @param  MediaItem  $item
     * @return array{type: MediaType, width: int, height: int, variants: list<array{width: int, path: string}>, sources: list<array{type: string, path: string}>|null}
     */
    private function render(array $item, string $source, string $directory, int $position, Filesystem $disk): array
    {
        $name = sprintf('%02d', $position + 1);

        return match ($item['kind']) {
            'photo' => [
                'type' => MediaType::Photo,
                ...$this->renderPhotoVariants->handle($source, $directory, $name, $item['focal'] ?? [0.5, 0.5], $disk),
                'sources' => null,
            ],
            'frame' => [
                'type' => MediaType::Photo,
                ...$this->withFrame($source, $item['at'] ?? 0.0, fn (string $frame): array => $this->renderPhotoVariants->handle($frame, $directory, $name, $item['focal'] ?? [0.5, 0.5], $disk)),
                'sources' => null,
            ],
            'video' => $this->renderVideo($source, $item['at'] ?? 0.0, $directory, $name, $disk),
        };
    }

    /**
     * @return array{type: MediaType, width: int, height: int, variants: list<array{width: int, path: string}>, sources: list<array{type: string, path: string}>}
     */
    private function renderVideo(string $source, float $posterAt, string $directory, string $name, Filesystem $disk): array
    {
        $maxWidth = Config::integer('catalog.media.video_max_width');
        $scale = sprintf('scale=min(%d\,iw):-2', $maxWidth);
        $temporary = sys_get_temp_dir().'/'.'catalog-video-'.Str::uuid()->toString();
        $sources = [];

        try {
            $this->runFfmpeg->handle(['-i', $source, '-an', '-vf', $scale, '-c:v', 'libvpx-vp9', '-crf', '36', '-b:v', '0', '-row-mt', '1', '-g', (string) self::KEYFRAME_INTERVAL, $temporary.'.webm']);
            $this->runFfmpeg->handle(['-i', $source, '-an', '-vf', $scale, '-c:v', 'libx264', '-crf', '26', '-preset', 'slow', '-g', (string) self::KEYFRAME_INTERVAL, '-pix_fmt', 'yuv420p', '-movflags', '+faststart', $temporary.'.mp4']);

            foreach (['webm' => 'video/webm', 'mp4' => 'video/mp4'] as $extension => $type) {
                $path = sprintf('%s/%s.%s', $directory, $name, $extension);
                $disk->put($path, (string) file_get_contents($temporary.'.'.$extension));
                $sources[] = ['type' => $type, 'path' => $path];
            }
        } finally {
            @unlink($temporary.'.webm');
            @unlink($temporary.'.mp4');
        }

        $poster = $this->withFrame($source, $posterAt, fn (string $frame): array => $this->renderPhotoVariants->handle($frame, $directory, $name.'-poster', null, $disk));

        return ['type' => MediaType::Video, ...$poster, 'sources' => $sources];
    }

    /**
     * @template TResult
     *
     * @param  callable(string): TResult  $callback
     * @return TResult
     */
    private function withFrame(string $video, float $at, callable $callback): mixed
    {
        $frame = sys_get_temp_dir().'/'.'catalog-frame-'.Str::uuid()->toString().'.png';

        try {
            $this->runFfmpeg->handle(['-ss', (string) $at, '-i', $video, '-frames:v', '1', $frame]);

            return $callback($frame);
        } finally {
            @unlink($frame);
        }
    }
}
