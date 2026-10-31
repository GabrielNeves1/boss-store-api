<?php

declare(strict_types=1);

namespace App\Http\Resources\Catalog;

use App\Actions\Catalog\CalculateInstallment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Unit
 */
final class PriceResource extends JsonResource
{
    private const int CENTS_PER_UNIT = 100;

    public function __construct(private readonly Unit $unit)
    {
        parent::__construct($unit);
    }

    /**
     * @return array{pix: float, card: float, installments: array{count: int, amount: float}}
     */
    public function toArray(Request $request): array
    {
        $installment = app(CalculateInstallment::class)->handle($this->unit->price_card_cents, $this->unit->max_installments);

        return [
            'pix' => $this->unit->price_pix_cents / self::CENTS_PER_UNIT,
            'card' => $this->unit->price_card_cents / self::CENTS_PER_UNIT,
            'installments' => [
                'count' => $this->unit->max_installments,
                'amount' => $installment / self::CENTS_PER_UNIT,
            ],
        ];
    }
}
