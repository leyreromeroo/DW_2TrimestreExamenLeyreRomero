<?php
$d1 = new DateTime('2025-02-15'); // Sábado
$d2 = new DateTime('2025-02-10'); // Lunes
$d3 = new DateTime('2025-02-16'); // Domingo

$s1 = (clone $d1)->modify('monday this week');
$s2 = (clone $d2)->modify('monday this week');
$s3 = (clone $d3)->modify('monday this week');

echo "Feb 15 (Sábado) -> Lunes de esa semana: " . $s1->format('Y-m-d') . PHP_EOL;
echo "Feb 10 (Lunes) -> Lunes de esa semana: " . $s2->format('Y-m-d') . PHP_EOL;
echo "Feb 16 (Domingo) -> Lunes de esa semana: " . $s3->format('Y-m-d') . PHP_EOL;

echo PHP_EOL . "¿Están en la misma semana? " . ($s1->format('Y-m-d') === $s2->format('Y-m-d') ? 'SÍ' : 'NO') . PHP_EOL;
