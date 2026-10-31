<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Foto ou vídeo de uma unidade. `variants` guarda as larguras geradas da foto
 * (ou do poster do vídeo); `sources` guarda os arquivos do vídeo.
 *
 * @property int $id
 * @property int $unit_id
 * @property MediaType $type
 * @property int $position
 * @property string $alt
 * @property int $width
 * @property int $height
 * @property list<array{width: int, path: string}> $variants
 * @property list<array{type: string, path: string}>|null $sources
 */
#[Fillable(['unit_id', 'type', 'position', 'alt', 'width', 'height', 'variants', 'sources'])]
final class UnitMedia extends Model
{
    protected $table = 'unit_media';

    /**
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
            'variants' => 'array',
            'sources' => 'array',
            'position' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }
}
