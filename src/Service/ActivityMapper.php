<?php

namespace App\Service;

use App\DTO\ActivityOutputDTO;
use App\DTO\SongDTO;
use App\Entity\Activity;

class ActivityMapper
{
    public function toDTO(Activity $activity): ActivityOutputDTO
    {
        // 1. Mapear Canciones (Playlist)
        $playListDTO = [];
        foreach ($activity->getPlayList() as $song) {
            $playListDTO[] = new SongDTO(
                $song->getId(),
                $song->getName(),
                $song->getDurationSeconds()
            );
        }

        // 2. Construir DTO final
        return new ActivityOutputDTO(
            $activity->getId(),
            $activity->getMaxParticipants(),
            $activity->getClientsSigned(),
            $activity->getType(),
            $playListDTO,
            $activity->getDateStart()->format(\DateTimeInterface::RFC3339),
            $activity->getDateEnd()->format(\DateTimeInterface::RFC3339)
        );
    }
}
