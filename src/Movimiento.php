<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;

/**
 * Entrada o salida de unidades de un producto.
 */
class Movimiento
{
    public const ENTRADA = 'entrada';
    public const SALIDA = 'salida';

    public readonly DateTimeImmutable $fecha;

    public function __construct(
        public readonly string $tipo,
        public readonly string $codigo,
        public readonly int $cantidad,
        public readonly int $existenciaResultante,
        public readonly string $motivo,
    ) {
        $this->fecha = new DateTimeImmutable();
    }
}
