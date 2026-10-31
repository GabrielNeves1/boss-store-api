<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

final class CalculateInstallment
{
    /**
     * Valor de cada parcela, em centavos, arredondado para cima para a soma
     * nunca ficar abaixo do preço no cartão.
     */
    public function handle(int $totalCents, int $installments): int
    {
        return (int) ceil($totalCents / max(1, $installments));
    }
}
