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
