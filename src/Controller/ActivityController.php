<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Service\ActivityMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/activities')]
class ActivityController extends AbstractController
{
    #[Route('', name: 'app_activities_index', methods: ['GET'])]
    public function index(Request $request, ActivityRepository $activityRepository, ActivityMapper $mapper): Response
    {
        //Extraer filtros de los parámetros de la consulta
        $onlyFree = $request->query->getBoolean('onlyfree', true);
        $type = $request->query->get('type');
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('page_size', 10);
        $sort = $request->query->get('sort', 'date');
        $order = $request->query->get('order', 'desc');

        // Validaciones
        $validTypes = ['BodyPump', 'Spinning', 'Core'];
        if ($type && !in_array($type, $validTypes)) {
            return $this->json(['code' => 400, 'description' => 'Tipo de actividad inválido'], 400);
        }

        $validSorts = ['date'];
        if (!in_array($sort, $validSorts)) {
            return $this->json(['code' => 400, 'description' => 'Tipo de ordenación inválido'], 400);
        }

        $validOrders = ['asc', 'desc'];
        if (!in_array($order, $validOrders)) {
            return $this->json(['code' => 400, 'description' => 'Orden inválido'], 400);
        }

        $filters = [
            'onlyfree' => $onlyFree,
            'type' => $type,
            'page' => $page,
            'page_size' => $pageSize,
            'sort' => $sort,
            'order' => $order,
        ];

        //Buscar actividades
        $paginator = $activityRepository->search($filters);
        $totalItems = count($paginator);

        // Mapear entidades a DTOs
        $dataDTO = [];
        foreach ($paginator as $activity) {
            $dataDTO[] = $mapper->toDTO($activity);
        }

        //Preparar respuesta
        $response = [
            'data' => $dataDTO,
            'meta' => [
                [
                    'page' => $page,
                    'limit' => $pageSize,
                    'total-items' => $totalItems
                ]
            ]
        ];

        return $this->json($response, 200);
    }
}
