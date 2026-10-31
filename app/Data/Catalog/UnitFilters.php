<?php

declare(strict_types=1);

namespace App\Data\Catalog;

use App\Enums\UnitCondition;
use App\Enums\UnitSort;
use App\Enums\UnitStatus;

final readonly class UnitFilters
{
    /**
     * @param  list<int>  $storages
     * @param  list<string>  $colors
     * @param  list<UnitCondition>  $conditions
     * @param  list<UnitStatus>  $statuses
     */
    public function __construct(
        public ?string $categorySlug = null,
        public array $storages = [],
        public array $colors = [],
        public array $conditions = [],
        public array $statuses = [],
        public ?int $batteryMin = null,
        public ?int $priceMinCents = null,
        public ?int $priceMaxCents = null,
        public UnitSort $sort = UnitSort::Relevance,
    ) {}
}
