<?php

namespace App\Service;

use App\Entity\Client;
use App\DTO\ClientOutputDTO;
use App\DTO\StatisticsByYearDTO;
use App\DTO\StatisticsByTypeDTO;
use App\DTO\StatisticsDTO;

class ClientMapper
{
    public function toDTO(Client $client, bool $withBookings, bool $withStatistics): ClientOutputDTO
    {
        $bookingsData = [];
        if ($withBookings) {
            foreach ($client->getBookings() as $booking) {
                $bookingsData[] = [
                    'id' => $booking->getId(),
                    'activity' => [
                        'id' => $booking->getActivity()->getId(),
                        'type' => $booking->getActivity()->getType(),
                        'date_start' => $booking->getActivity()->getDateStart(),
                        'date_end' => $booking->getActivity()->getDateEnd(),
                    ],
                    'client_id' => $client->getId()
                ];
            }
        }

        $statsData = [];
        if ($withStatistics) {
            $statsData = $this->calculateStats($client);
        }

        return new ClientOutputDTO(
            $client->getId(),
            $client->getType(),
            $client->getName(),
            $client->getEmail(),
            $bookingsData,
            $statsData
        );
    }

    private function calculateStats(Client $client): array
    {
        $grouped = [];

        foreach ($client->getBookings() as $booking) {
            $activity = $booking->getActivity();
            $year = $activity->getDateStart()->format('Y');
            $type = $activity->getType();

            // Calcular duración en minutos
            $diff = $activity->getDateEnd()->getTimestamp() - $activity->getDateStart()->getTimestamp();
            $minutes = round($diff / 60);

            // Inicializar estructura si no existe
            if (!isset($grouped[$year])) $grouped[$year] = [];
            if (!isset($grouped[$year][$type])) {
                $grouped[$year][$type] = ['count' => 0, 'minutes' => 0];
            }

            $grouped[$year][$type]['count']++;
            $grouped[$year][$type]['minutes'] += $minutes;
        }

        // Convertir a la estructura de DTOs que pide el YAML
        $finalStats = [];
        foreach ($grouped as $year => $types) {
            $typesArray = [];
            foreach ($types as $typeName => $data) {
                $typesArray[] = new StatisticsByTypeDTO(
                    $typeName,
                    [new StatisticsDTO((string)$data['count'], (string)$data['minutes'])]
                );
            }
            $finalStats[] = new StatisticsByYearDTO((int)$year, $typesArray);
        }

        return $finalStats;
    }
}