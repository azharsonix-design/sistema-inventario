<?php

declare(strict_types=1);

namespace Tests;

use App\Inventario;
use App\InventarioException;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Casos de prueba de productos (docs/casos-prueba.md).
 */
class ProductoTest extends TestCase
{
    private Inventario $inventario;

    protected function setUp(): void
    {
        $this->inventario = new Inventario();
        $this->inventario->registrarProducto('LAP-001', 'Laptop Lenovo', 'Cómputo', 12500.00, 10, 2);
    }

    #[TestDox('CP-01 Registrar producto con datos válidos')]
    public function testRegistrarProductoConDatosValidos(): void
    {
        $producto = $this->inventario->registrarProducto('MOU-010', 'Mouse inalámbrico', 'Accesorios', 250.00, 30, 5);

        $this->assertSame('MOU-010', $producto->codigo);
        $this->assertCount(2, $this->inventario->listarProductos());
    }

    #[TestDox('CP-02 Registrar producto con código duplicado muestra error')]
    public function testRegistrarProductoConCodigoDuplicado(): void
    {
        $this->expectException(InventarioException::class);
        $this->expectExceptionMessage('Ya existe un producto con el código LAP-001.');

        $this->inventario->registrarProducto('lap-001', 'Otra laptop', 'Cómputo', 9000.00);
    }

    #[TestDox('CP-03 Consultar producto con código existente')]
    public function testConsultarProductoExistente(): void
    {
        $producto = $this->inventario->consultarProducto('LAP-001');

        $this->assertSame('Laptop Lenovo', $producto->nombre);
        $this->assertSame(10, $producto->existencia);
    }

    #[TestDox('CP-04 Consultar producto con código inexistente muestra mensaje')]
    public function testConsultarProductoInexistente(): void
    {
        $this->expectException(InventarioException::class);
        $this->expectExceptionMessage('No existe un producto con el código XXX-999.');

        $this->inventario->consultarProducto('XXX-999');
    }

    #[TestDox('CP-05 Modificar producto con datos válidos')]
    public function testModificarProductoConDatosValidos(): void
    {
        $this->inventario->modificarProducto('LAP-001', ['nombre' => 'Laptop Lenovo ThinkPad', 'precio' => 13000.00]);

        $producto = $this->inventario->consultarProducto('LAP-001');
        $this->assertSame('Laptop Lenovo ThinkPad', $producto->nombre);
        $this->assertSame(13000.00, $producto->precio);
    }

    #[TestDox('CP-06 Eliminar producto existente')]
    public function testEliminarProductoExistente(): void
    {
        $this->inventario->eliminarProducto('LAP-001');

        $this->assertCount(0, $this->inventario->listarProductos());
        $this->expectException(InventarioException::class);
        $this->inventario->consultarProducto('LAP-001');
    }

    #[TestDox('CP-09 Registrar producto con precio negativo muestra error de validación')]
    public function testRegistrarProductoConPrecioNegativo(): void
    {
        $this->expectException(InventarioException::class);
        $this->expectExceptionMessage('El precio no puede ser negativo.');

        $this->inventario->registrarProducto('TEC-005', 'Teclado', 'Accesorios', -100.00);
    }

    #[TestDox('CP-11 Consultar existencias de todos los productos')]
    public function testConsultarExistencias(): void
    {
        $this->inventario->registrarProducto('MOU-010', 'Mouse inalámbrico', 'Accesorios', 250.00, 30, 5);

        $this->assertSame(['LAP-001' => 10, 'MOU-010' => 30], $this->inventario->consultarExistencias());
    }
}
