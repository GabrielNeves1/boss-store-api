<?php

declare(strict_types=1);

namespace App\Http\Resources\Catalog;

use App\Enums\MediaType;
use App\Http\Resources\EnumResource;
use App\Models\Unit;
use App\Models\UnitMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Unit
 */
final class UnitSummaryResource extends JsonResource
{
    public function __construct(private readonly Unit $unit)
    {
        parent::__construct($unit);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cover = $this->unit->media->first(fn (UnitMedia $media): bool => $media->type === MediaType::Photo);

        return [
            'code' => $this->unit->code,
            'model' => $this->unit->model,
            'color' => $this->unit->color,
            'storage_gb' => $this->unit->storage_gb,
            'battery_health' => $this->unit->battery_health,
            'condition' => new EnumResource($this->unit->condition),
            'status' => new EnumResource($this->unit->status),
            'category' => [
                'slug' => $this->unit->category->slug,
                'name' => $this->unit->category->name,
            ],
            'price' => new PriceResource($this->unit),
            /** @var MediaResource|null */
            'cover' => $cover === null ? null : new MediaResource($cover),
        ];
    }
}
