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
        // --- CLIENTES ---
    $c1 = new Client();
    $c1->setName("Miguel Goyena")->setEmail("miguel@cuatrovientos.org")->setType("premium");
    $manager->persist($c1);

    $c2 = new Client();
    $c2->setName("Alumno DAM")->setEmail("alumno@cuatrovientos.org")->setType("standard");
    $manager->persist($c2);

    // --- ACTIVIDADES 2025 ---
    $a1 = new Activity();
    $a1->setType("Spinning")->setMaxParticipants(2) // Casi llena
       ->setDateStart(new \DateTime('2025-02-15 10:00:00'))
       ->setDateEnd(new \DateTime('2025-02-15 11:00:00'));
    $manager->persist($a1);

    $a2 = new Activity();
    $a2->setType("BodyPump")->setMaxParticipants(20)
       ->setDateStart(new \DateTime('2025-02-16 12:00:00'))
       ->setDateEnd(new \DateTime('2025-02-16 13:30:00')); // 90 min
    $manager->persist($a2);

    // --- ACTIVIDAD LLENA (Para probar error 400) ---
    $aFull = new Activity();
    $aFull->setType("Core")->setMaxParticipants(1)
          ->setDateStart(new \DateTime('2025-03-01 09:00:00'))
          ->setDateEnd(new \DateTime('2025-03-01 10:00:00'));
    $manager->persist($aFull);

    // --- ACTIVIDAD AÑO PASADO (Para estadísticas) ---
    $aOld = new Activity();
    $aOld->setType("Spinning")->setMaxParticipants(10)
         ->setDateStart(new \DateTime('2024-11-10 18:00:00'))
         ->setDateEnd(new \DateTime('2024-11-10 19:00:00'));
    $manager->persist($aOld);

    // --- CANCIONES ---
    $s1 = new Song();
    $s1->setName("La morocha")->setDurationSeconds(245)->setActivity($a1);
    $manager->persist($s1);

    // --- RESERVAS (Bookings) ---
    // Miguel (Premium) tiene 2 reservas en 2025 y 1 en 2024
    $b1 = new Booking(); $b1->setClient($c1)->setActivity($a1); $manager->persist($b1);
    // $b2 = new Booking(); $b2->setClient($c1)->setActivity($a2); $manager->persist($b2);
    $bOld = new Booking(); $bOld->setClient($c1)->setActivity($aOld); $manager->persist($bOld);

    // El Alumno (Standard) ocupa la única plaza de la actividad llena
    $bFull = new Booking(); $bFull->setClient($c2)->setActivity($aFull); $manager->persist($bFull);
    
    //Probar fallo apuntado estandar 3 veces a actividades en una semana
    // Actividad 5: Lunes (misma semana que A1 y A2)
    $a5 = new Activity();
    $a5->setType("Spinning")->setMaxParticipants(10)
    ->setDateStart(new \DateTime('2025-02-10 10:00:00'))
    ->setDateEnd(new \DateTime('2025-02-10 11:00:00'));
    $manager->persist($a5);

    // Actividad 6: Miércoles
    $a6 = new Activity();
    $a6->setType("BodyPump")->setMaxParticipants(10)
    ->setDateStart(new \DateTime('2025-02-12 12:00:00'))
    ->setDateEnd(new \DateTime('2025-02-12 13:00:00'));
    $manager->persist($a6);

    // Actividad 7: Viernes
    $a7 = new Activity();
    $a7->setType("Core")->setMaxParticipants(10)
    ->setDateStart(new \DateTime('2025-02-14 09:00:00'))
    ->setDateEnd(new \DateTime('2025-02-14 10:00:00'));
    $manager->persist($a7);

    // Cliente Standard adicional (para pruebas si es necesario)
    $c3 = new Client();
    $c3->setName("Alumno Standard")->setEmail("std@test.com")->setType("standard");
    $manager->persist($c3);

    $manager->flush();
    }
}