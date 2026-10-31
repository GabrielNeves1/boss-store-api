<?php

declare(strict_types=1);

namespace App\Actions\Catalog\Media;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * Gera as variantes WebP de uma imagem: recorte opcional na proporção do
 * catálogo em torno do ponto focal, e uma cópia por largura configurada que
 * caiba na imagem.
 */
final class RenderPhotoVariants
{
    /**
     * @param  array{0: float, 1: float}|null  $focalPoint  x e y entre 0 e 1; null mantém a proporção original
     * @return array{width: int, height: int, variants: list<array{width: int, path: string}>}
     */
    public function handle(string $sourcePath, string $directory, string $name, ?array $focalPoint, Filesystem $disk): array
    {
        $manager = ImageManager::usingDriver(Driver::class);
        $master = $this->crop($manager->decodePath($sourcePath)->orient(), $focalPoint);
        $masterWidth = $master->width();
        $masterHeight = $master->height();

        $widths = $this->widthsFor($masterWidth);
        $variants = [];

        foreach ($widths as $width) {
            $image = $this->crop($manager->decodePath($sourcePath)->orient(), $focalPoint)->scale(width: $width);
            $path = sprintf('%s/%s-%d.webp', $directory, $name, $width);

            $disk->put($path, (string) $image->encode(new WebpEncoder(quality: Config::integer('catalog.media.webp_quality'), strip: true)));
            $variants[] = ['width' => $width, 'path' => $path];
        }

        $largest = end($widths) ?: $masterWidth;

        return [
            'width' => $largest,
            'height' => (int) round($masterHeight * $largest / $masterWidth),
            'variants' => $variants,
        ];
    }

    /**
     * @param  array{0: float, 1: float}|null  $focalPoint
     */
    private function crop(ImageInterface $image, ?array $focalPoint): ImageInterface
    {
        if ($focalPoint === null) {
            return $image;
        }

        $ratio = Config::integer('catalog.media.aspect_ratio.width') / Config::integer('catalog.media.aspect_ratio.height');
        $sourceWidth = $image->width();
        $sourceHeight = $image->height();

        if ($sourceWidth / $sourceHeight > $ratio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $ratio);
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $ratio);
        }

        $x = (int) min(max(0, round($focalPoint[0] * $sourceWidth - $cropWidth / 2)), $sourceWidth - $cropWidth);
        $y = (int) min(max(0, round($focalPoint[1] * $sourceHeight - $cropHeight / 2)), $sourceHeight - $cropHeight);

        return $image->crop($cropWidth, $cropHeight, $x, $y);
    }

    /**
     * @return list<int>
     */
    private function widthsFor(int $masterWidth): array
    {
        /** @var list<int> $configured */
        $configured = Config::array('catalog.media.photo_widths');
        if ($configured === []) {
            return [$masterWidth];
        }

        $widths = array_values(array_filter($configured, fn (int $width): bool => $width < $masterWidth));
        $widths[] = min($masterWidth, max($configured));

        return array_values(array_unique($widths));
    }
}
