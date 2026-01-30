<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function search(array $filters): Paginator
    {
        $qb = $this->createQueryBuilder('a');

        // Hacemos el join para que clients_signed se calcule bien
        // y para poder filtrar por plazas libres.
        $qb->leftJoin('a.bookings', 'b')
           ->groupBy('a.id');

        // Filtrado por tipo
        if (!empty($filters['type'])) {
            $qb->andWhere('a.type = :type')
               ->setParameter('type', $filters['type']);
        }

        // Filtrado por disponibilidad
        if (isset($filters['onlyfree']) && $filters['onlyfree'] === true) {
            $qb->andHaving('COUNT(b.id) < a.max_participants');
        }

        // Ordenación
        $sort = $filters['sort'] ?? 'date';
        $order = $filters['order'] ?? 'desc';
        
        if ($sort === 'date') {
            $qb->orderBy('a.date_start', $order);
        } else {
            $qb->orderBy('a.id', $order);
        }

        // Paginación
        $page = $filters['page'] ?? 1;
        $pageSize = $filters['page_size'] ?? 10;
        
        $qb->setFirstResult(($page - 1) * $pageSize)
           ->setMaxResults($pageSize);

        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        /*Sirve para que el contador de páginas no se líe y cuente 
        actividades reales en lugar de las filas repetidas que generan 
        los JOINs en la base de datos. Como un distinct.*/
        $paginator->setUseOutputWalkers(false);

        return $paginator;
    }
}
