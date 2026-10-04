<?php

declare(strict_types=1);

/*
 * Punto de entrada provisional. Muestra un reporte de ejemplo para comprobar
 * que el contenedor funciona; /salud lo usa el despliegue Blue/Green para
 * saber si el ambiente nuevo está listo.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Inventario;
use App\ReporteInventario;

header('Content-Type: application/json; charset=utf-8');

if (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === '/salud') {
    echo json_encode(['estado' => 'ok', 'version' => getenv('APP_VERSION') ?: 'dev']);
    return;
}

$inventario = new Inventario();
$inventario->registrarProducto('LAP-001', 'Laptop Lenovo', 'Cómputo', 12500.00, 10, 2);
$inventario->registrarProducto('MOU-010', 'Mouse inalámbrico', 'Accesorios', 250.00, 30, 5);
$inventario->registrarSalida('LAP-001', 8);

echo json_encode(
    (new ReporteInventario($inventario))->generar(),
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);
