<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use Illuminate\Support\Str;

/**
 * Rótulo de um backed enum lido de lang/<locale>/enums.php, na chave
 * "<nome_do_enum_em_snake_case>.<valor do case>".
 */
trait HasTranslatedLabel
{
    public function label(): string
    {
        $key = sprintf('enums.%s.%s', Str::snake(class_basename(static::class)), $this->value);
        $label = __($key);

        return is_string($label) ? $label : $key;
    }

    /**
     * @return list<array{value: int|string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $case): array => ['value' => $case->value, 'label' => $case->label()],
            self::cases(),
        );
    }
}
