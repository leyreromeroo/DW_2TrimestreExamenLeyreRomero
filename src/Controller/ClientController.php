<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clients')]
class ClientController extends AbstractController
{
    #[Route('/{id}', name: 'app_clients_show', methods: ['GET'])]
    public function show(
        Client $client, 
        Request $request, 
        ClientMapper $mapper
    ): Response
    {
        // Validar que existe el cliente
        if (!$client) {
            return $this->json(['code' => 404, 'description' => 'El cliente no existe'], 400);
        }
        
        // 1.Extraer parámetros de la consulta
        $withBookings = $request->query->getBoolean('with_bookings', false);
        $withStatistics = $request->query->getBoolean('with_statistics', false);

        // 2. Usar el mapper para transformar la entidad en el DTO complejo
        $clientDTO = $mapper->toDTO($client, $withBookings, $withStatistics);

        return $this->json($clientDTO, 200);
    }
}
