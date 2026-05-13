<?php

namespace Modules\Commission\DTO;

use Modules\Commission\Enums\BonusTypeEnum;

class BonusCalculationDTO
{
    public function __construct(
        public string $userId,
        public float $amount,
        public BonusTypeEnum $bonusType,
        public string $periodString,
        public ?string $sourceId = null,
        public array $metadata = []
    ) {}
}
