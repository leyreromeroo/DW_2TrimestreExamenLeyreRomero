<?php

namespace App\DTO;

class StatisticsByTypeDTO {
    public function __construct(
        public string $type,
        public array $statistics
    ) {}
}
