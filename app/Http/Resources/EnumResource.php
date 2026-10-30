<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Contracts\HasLabel;
use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class EnumResource extends JsonResource
{
    public function __construct(private readonly BackedEnum&HasLabel $enum)
    {
        parent::__construct($enum);
    }

    /**
     * @return array{value: int|string, label: string}
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->enum->value,
            'label' => $this->enum->label(),
        ];
    }
}
