<?php

declare(strict_types=1);

namespace Tests;

use App\Inventario;
use App\ReporteInventario;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Prueba de integración: registro + movimientos + reporte (docs/casos-prueba.md).
 */
class ReporteTest extends TestCase
{
    #[TestDox('CP-12 Generar reporte básico de inventario después de entradas y salidas')]
    public function testGenerarReporteBasico(): void
    {
        $inventario = new Inventario();
        $inventario->registrarProducto('LAP-001', 'Laptop Lenovo', 'Cómputo', 12500.00, 10, 2);
        $inventario->registrarProducto('MOU-010', 'Mouse inalámbrico', 'Accesorios', 250.00, 30, 5);
        $inventario->registrarSalida('LAP-001', 8);
        $inventario->registrarEntrada('MOU-010', 10);

        $reporte = (new ReporteInventario($inventario))->generar();

        $this->assertSame(2, $reporte['totalProductos']);
        $this->assertSame(42, $reporte['totalUnidades']);
        $this->assertSame(35000.00, $reporte['valorTotal']);
        $this->assertSame(['LAP-001'], $reporte['bajoStockMinimo']);
    }
}
