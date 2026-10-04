<?php

declare(strict_types=1);

namespace App;

/**
 * Reporte básico de inventario: existencias, valor y productos por surtir.
 */
class ReporteInventario
{
    public function __construct(private readonly Inventario $inventario)
    {
    }

    /**
     * @return array{
     *     totalProductos: int,
     *     totalUnidades: int,
     *     valorTotal: float,
     *     bajoStockMinimo: list<string>,
     *     detalle: list<array{codigo: string, nombre: string, existencia: int, valor: float}>
     * }
     */
    public function generar(): array
    {
        $productos = $this->inventario->listarProductos();

        return [
            'totalProductos' => count($productos),
            'totalUnidades' => array_sum(array_map(fn (Producto $p) => $p->existencia, $productos)),
            'valorTotal' => (float) array_sum(array_map(fn (Producto $p) => $p->valorTotal(), $productos)),
            'bajoStockMinimo' => array_values(array_map(
                fn (Producto $p) => $p->codigo,
                array_filter($productos, fn (Producto $p) => $p->bajoStockMinimo()),
            )),
            'detalle' => array_map(fn (Producto $p) => [
                'codigo' => $p->codigo,
                'nombre' => $p->nombre,
                'existencia' => $p->existencia,
                'valor' => $p->valorTotal(),
            ], $productos),
        ];
    }
}
