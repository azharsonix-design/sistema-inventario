<?php

declare(strict_types=1);

/*
 * Punto de entrada provisional. /salud lo usa el despliegue Blue/Green para
 * saber si el ambiente nuevo está listo.
 */

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === '/salud') {
    echo json_encode(['estado' => 'ok', 'version' => getenv('APP_VERSION') ?: 'dev']);
    return;
}

echo json_encode(['sistema' => 'Sistema de Control de Inventario', 'estado' => 'en construcción']);
