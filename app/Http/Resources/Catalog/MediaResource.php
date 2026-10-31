<?php

declare(strict_types=1);

namespace App\Http\Resources\Catalog;

use App\Models\UnitMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin UnitMedia
 */
final class MediaResource extends JsonResource
{
    public function __construct(private readonly UnitMedia $media)
    {
        parent::__construct($media);
    }

    /**
     * @return array{
     *     type: string,
     *     alt: string,
     *     width: int,
     *     height: int,
     *     variants: list<array{width: int, url: string}>,
     *     sources: list<array{type: string, url: string}>,
     * }
     */
    public function toArray(Request $request): array
    {
        $disk = Storage::disk(Config::string('catalog.media.disk'));

        return [
            'type' => $this->media->type->value,
            'alt' => $this->media->alt,
            'width' => $this->media->width,
            'height' => $this->media->height,
            /** @var list<array{width: int, url: string}> */
            'variants' => array_map(
                fn (array $variant): array => ['width' => $variant['width'], 'url' => $disk->url($variant['path'])],
                $this->media->variants,
            ),
            /** @var list<array{type: string, url: string}> */
            'sources' => array_map(
                fn (array $source): array => ['type' => $source['type'], 'url' => $disk->url($source['path'])],
                $this->media->sources ?? [],
            ),
        ];
    }
}
