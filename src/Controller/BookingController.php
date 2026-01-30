<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\ActivityRepository;
use App\Repository\BookingRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bookings')]
class BookingController extends AbstractController
{
    #[Route('', name: 'app_bookings_create', methods: ['POST'])]
    public function create(
        Request $request, 
        ActivityRepository $activityRepository, 
        ClientRepository $clientRepository,
        BookingRepository $bookingRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['activity_id']) || !isset($data['client_id'])) {
            return $this->json(['code' => 400, 'description' => 'Datos inválidos'], 400);
        }

        // 1. Validar existencia
        $activityId = $data['activity_id'];
        $clientId = $data['client_id'];

        $activity = $activityRepository->find($activityId);
        $client = $clientRepository->find($clientId);

        if (!$activity) {
            return $this->json(['code' => 404, 'description' => 'La actividad no existe'], 400);
        }

        if (!$client) {
            return $this->json(['code' => 404, 'description' => 'El cliente no existe'], 400);
        }

        // 2. Validar que el cliente NO esté ya apuntado
        $existingBooking = $bookingRepository->findOneBy([
            'client' => $client,
            'activity' => $activity
        ]);

        if ($existingBooking) {
            return $this->json([
                'code' => 400, 
                'description' => 'Ya estás apuntado a esta actividad'
            ], 400);
        }

        // 3. Validar capacidad
        if ($activity->getBookings()->count() >= $activity->getMaxParticipants()) {
             return $this->json(['code' => 400, 'description' => 'La actividad está llena'], 400); // 400 as per common practice for business rules, could be 409
        }

        // 4. Validar tipo de usuario (Standard vs Premium)
        if ($client->getType() === 'standard') {
            // Contar reservas existentes para este cliente en la misma semana que la actividad
            $weekBookings = $bookingRepository->countBookingsForClientInWeek(
                $client->getId(), 
                $activity->getDateStart()
            );

            if ($weekBookings >= 2) {
                return $this->json([
                    'code' => 400, 
                    'description' => 'Los usuarios standard no pueden reservar más de 2 actividades por semana'
                ], 400);
            }
        }

        // 5. Crear reserva 
        $booking = new Booking();
        $booking->setActivity($activity);
        $booking->setClient($client);

        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->json([
            'id' => (int) $booking->getId(),
            'activity' => $activity,
            'client_id' => (int) $client->getId()
        ], 200, [], ['groups' => 'activity:read']);
    }
}
