<?php

namespace App\DTO;

class SongDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $duration_seconds
    ) {}
}
