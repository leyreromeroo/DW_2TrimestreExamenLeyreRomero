<?php

namespace App\DTO;

class BookingInputDTO
{
    public function __construct(
        public int $activityId,
        public int $clientId
    ) {}
}
