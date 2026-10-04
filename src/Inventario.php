<?php

declare(strict_types=1);

namespace App;

/**
 * Operaciones del Sistema de Control de Inventario.
 *
 * Por ahora guarda los productos en memoria; en la siguiente fase se
 * conectará a PostgreSQL sin cambiar esta interfaz.
 */
class Inventario
{
    /** @var array<string, Producto> */
    private array $productos = [];

    /** @var list<Movimiento> */
    private array $movimientos = [];

    public function registrarProducto(
        string $codigo,
        string $nombre,
        string $categoria,
        float $precio,
        int $existencia = 0,
        int $stockMinimo = 0,
    ): Producto {
        $codigo = strtoupper(trim($codigo));
        if (isset($this->productos[$codigo])) {
            throw new InventarioException("Ya existe un producto con el código {$codigo}.");
        }

        $producto = new Producto($codigo, trim($nombre), trim($categoria), $precio, $existencia, $stockMinimo);
        $this->productos[$codigo] = $producto;

        return $producto;
    }

    public function consultarProducto(string $codigo): Producto
    {
        $codigo = strtoupper(trim($codigo));
        if (!isset($this->productos[$codigo])) {
            throw new InventarioException("No existe un producto con el código {$codigo}.");
        }

        return $this->productos[$codigo];
    }

    /**
     * @param array{nombre?: string, categoria?: string, precio?: float, stockMinimo?: int} $datos
     */
    public function modificarProducto(string $codigo, array $datos): Producto
    {
        $producto = $this->consultarProducto($codigo);

        $nombre = $datos['nombre'] ?? $producto->nombre;
        $precio = (float) ($datos['precio'] ?? $producto->precio);
        $stockMinimo = (int) ($datos['stockMinimo'] ?? $producto->stockMinimo);
        Producto::validar($producto->codigo, $nombre, $precio, $producto->existencia, $stockMinimo);

        $producto->nombre = trim($nombre);
        $producto->categoria = trim($datos['categoria'] ?? $producto->categoria);
        $producto->precio = $precio;
        $producto->stockMinimo = $stockMinimo;

        return $producto;
    }

    public function eliminarProducto(string $codigo): void
    {
        $producto = $this->consultarProducto($codigo);
        unset($this->productos[$producto->codigo]);
    }

    /**
     * @return list<Producto>
     */
    public function listarProductos(): array
    {
        return array_values($this->productos);
    }

    /**
     * Existencia actual de cada producto, indexada por código.
     *
     * @return array<string, int>
     */
    public function consultarExistencias(): array
    {
        return array_map(fn (Producto $p) => $p->existencia, $this->productos);
    }

    public function registrarEntrada(string $codigo, int $cantidad, string $motivo = 'Compra a proveedor'): Movimiento
    {
        $producto = $this->consultarProducto($codigo);
        self::validarCantidad($cantidad);

        $producto->existencia += $cantidad;

        return $this->movimientos[] = new Movimiento(
            Movimiento::ENTRADA,
            $producto->codigo,
            $cantidad,
            $producto->existencia,
            $motivo,
        );
    }

    public function registrarSalida(string $codigo, int $cantidad, string $motivo = 'Venta'): Movimiento
    {
        $producto = $this->consultarProducto($codigo);
        self::validarCantidad($cantidad);
        if ($cantidad > $producto->existencia) {
            throw new InventarioException(
                "Existencia insuficiente de {$producto->codigo}: hay {$producto->existencia} y se pidieron {$cantidad}."
            );
        }

        $producto->existencia -= $cantidad;

        return $this->movimientos[] = new Movimiento(
            Movimiento::SALIDA,
            $producto->codigo,
            $cantidad,
            $producto->existencia,
            $motivo,
        );
    }

    /**
     * @return list<Movimiento>
     */
    public function movimientos(): array
    {
        return $this->movimientos;
    }

    private static function validarCantidad(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InventarioException('La cantidad debe ser un número entero mayor que cero.');
        }
    }
}
