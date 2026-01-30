<?php

namespace App\DTO;

class StatisticsByYearDTO {
    public function __construct(
        public int $year,
        public array $statistics_by_type
    ) {}
}
