<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Booking;
use App\Entity\Client;
use App\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Crear Cliente
        $client = new Client();
        $client->setName("Miguel Goyena")->setEmail("miguel@cuatrovientos.org")->setType("premium");
        $manager->persist($client);

        // 2. Crear Actividad
        $activity = new Activity();
        $activity->setType("Spinning")
                ->setMaxParticipants(15)
             ->setDateStart(new \DateTime('2025-02-15 10:00:00'))
             ->setDateEnd(new \DateTime('2025-02-15 11:00:00'));
    
        // 3. Añadir canciones a la actividad
        $song = new Song();
        $song->setName("La morocha")->setDurationSeconds(245)->setActivity($activity);
        $manager->persist($song);
    
        $manager->persist($activity);

        // 4. Crear un Booking para que clients_signed sea > 0
        $booking = new Booking();
        $booking->setClient($client)->setActivity($activity);
        $manager->persist($booking);

        $manager->flush();
    }
}