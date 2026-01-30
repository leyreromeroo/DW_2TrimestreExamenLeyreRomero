<?php
require __DIR__.'/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__.'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();

$activities = $em->getRepository(\App\Entity\Activity::class)->findAll();

echo "=== ACTIVIDADES EN LA BASE DE DATOS ===\n\n";
foreach ($activities as $activity) {
    echo sprintf(
        "ID: %d | Tipo: %s | Fecha: %s\n",
        $activity->getId(),
        $activity->getType(),
        $activity->getDateStart()->format('Y-m-d')
    );
}

$clients = $em->getRepository(\App\Entity\Client::class)->findAll();
echo "\n=== CLIENTES ===\n\n";
foreach ($clients as $client) {
    echo sprintf(
        "ID: %d | Nombre: %s | Tipo: %s\n",
        $client->getId(),
        $client->getName(),
        $client->getType()
    );
}
