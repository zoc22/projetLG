<?php

namespace Modules\Commission\DTO;

class CommissionPeriodDTO
{
    public function __construct(
        public string $periodString, // ex: "2024-W01"
        public string $startDate,
        public string $endDate
    ) {}
}
