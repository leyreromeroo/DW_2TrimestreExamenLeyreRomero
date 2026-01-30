<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getStatistics(int $clientId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        // Native SQL might be easier for complex date diff aggregation if DQL is tricky with timestamps
        // But let's try DQL or a robust Builder
        // We need: Year, Type, Count, Sum(Duration).
        // Duration in minutes = (date_end - date_start) / 60 normally? Or just diff.
        // DQL doesn't have easy DateDiff in minutes standardly without extensions usually.
        // I will use Native SQL for safety in this exam context.
        
        $sql = '
            SELECT 
                YEAR(a.date_start) as yr,
                a.type as type,
                COUNT(b.id) as num_activities,
                SUM(TIMESTAMPDIFF(MINUTE, a.date_start, a.date_end)) as num_minutes
            FROM booking b
            JOIN activity a ON b.activity_id = a.id
            WHERE b.client_id = :id
            GROUP BY yr, type
            ORDER BY yr DESC, type ASC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $clientId]);
        $rows = $resultSet->fetchAllAssociative();

        // Structure the result
        // [ { year: 2025, statistics_by_type: [ { type: '...', statistics: { ... } } ] } ]
        
        $statsByYear = [];

        foreach ($rows as $row) {
            $year = $row['yr'];
            if (!isset($statsByYear[$year])) {
                $statsByYear[$year] = [
                    'year' => (int)$year,
                    'statistics_by_type' => []
                ];
            }

            $statsByYear[$year]['statistics_by_type'][] = [
                'type' => $row['type'],
                'statistics' => [
                    'num_activities' => (int)$row['num_activities'],
                    'num_minutes' => (int)$row['num_minutes'] ?? 0
                ]
            ];
        }

        return array_values($statsByYear);
    }
}
