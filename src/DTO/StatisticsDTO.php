<?php

namespace App\DTO;

class StatisticsDTO {
    public function __construct(
        public string $num_activities, // El YAML pide string para format int64
        public string $num_minutes
    ) {}
}
