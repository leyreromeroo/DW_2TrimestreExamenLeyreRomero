<?php

namespace App\DTO;

class ClientOutputDTO {
    public function __construct(
        public int $id,
        public string $type,
        public string $name,
        public string $email,
        public array $activities_booked,
        public array $activity_statistics
    ) {}
}

class StatisticsByYearDTO {
    public function __construct(
        public int $year,
        public array $statistics_by_type
    ) {}
}

class StatisticsByTypeDTO {
    public function __construct(
        public string $type,
        public array $statistics
    ) {}
}

class StatisticsDTO {
    public function __construct(
        public string $num_activities, // El YAML pide string para format int64
        public string $num_minutes
    ) {}
}