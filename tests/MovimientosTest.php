<?php

declare(strict_types=1);

namespace Tests;

use App\Inventario;
use App\InventarioException;
use App\Movimiento;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Casos de prueba de entradas y salidas (docs/casos-prueba.md).
 */
class MovimientosTest extends TestCase
{
    private Inventario $inventario;

    protected function setUp(): void
    {
        $this->inventario = new Inventario();
        $this->inventario->registrarProducto('LAP-001', 'Laptop Lenovo', 'Cómputo', 12500.00, 10, 2);
    }

    #[TestDox('CP-07 Entrada de producto con cantidad válida incrementa la existencia')]
    public function testEntradaIncrementaExistencia(): void
    {
        $movimiento = $this->inventario->registrarEntrada('LAP-001', 5);

        $this->assertSame(Movimiento::ENTRADA, $movimiento->tipo);
        $this->assertSame(15, $this->inventario->consultarProducto('LAP-001')->existencia);
    }

    #[TestDox('CP-08 Salida de producto con cantidad disponible reduce la existencia')]
    public function testSalidaReduceExistencia(): void
    {
        $movimiento = $this->inventario->registrarSalida('LAP-001', 4);

        $this->assertSame(Movimiento::SALIDA, $movimiento->tipo);
        $this->assertSame(6, $this->inventario->consultarProducto('LAP-001')->existencia);
    }

    #[TestDox('CP-10 Salida mayor a la existencia muestra error y no cambia la existencia')]
    public function testSalidaMayorALaExistencia(): void
    {
        try {
            $this->inventario->registrarSalida('LAP-001', 50);
            $this->fail('Se esperaba un error por existencia insuficiente.');
        } catch (InventarioException $e) {
            $this->assertStringContainsString('Existencia insuficiente', $e->getMessage());
        }

        $this->assertSame(10, $this->inventario->consultarProducto('LAP-001')->existencia);
        $this->assertCount(0, $this->inventario->movimientos());
    }

    #[TestDox('CP-13 Entrada con cantidad cero muestra error de validación')]
    public function testEntradaConCantidadInvalida(): void
    {
        $this->expectException(InventarioException::class);
        $this->expectExceptionMessage('La cantidad debe ser un número entero mayor que cero.');

        $this->inventario->registrarEntrada('LAP-001', 0);
    }
}
