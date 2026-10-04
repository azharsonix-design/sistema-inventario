# Casos de prueba

Cada caso está automatizado en `tests/` con su ID en el nombre (`#[TestDox('CP-xx ...')]`). Para ejecutarlos: `composer test`.

Datos base: producto `LAP-001` Laptop Lenovo, categoría Cómputo, precio $12,500, existencia 10, stock mínimo 2.

| ID | Funcionalidad | Entrada | Resultado esperado | Resultado obtenido | Estado |
|---|---|---|---|---|---|
| CP-01 | Registrar producto | Datos válidos: `MOU-010`, Mouse inalámbrico, $250, 30 pzas | Producto registrado | Producto `MOU-010` registrado; el inventario tiene 2 productos | Aprobada |
| CP-02 | Registrar producto | Código duplicado: `lap-001` | Mostrar error | «Ya existe un producto con el código LAP-001.» | Aprobada |
| CP-03 | Consultar producto | Código existente: `LAP-001` | Mostrar producto | Muestra Laptop Lenovo con existencia 10 | Aprobada |
| CP-04 | Consultar producto | Código inexistente: `XXX-999` | Mostrar mensaje | «No existe un producto con el código XXX-999.» | Aprobada |
| CP-05 | Modificar producto | Nombre «Laptop Lenovo ThinkPad», precio $13,000 | Información actualizada | Nombre y precio actualizados | Aprobada |
| CP-06 | Eliminar producto | Producto existente: `LAP-001` | Producto eliminado | El producto ya no aparece y consultarlo marca error | Aprobada |
| CP-07 | Entrada de producto | Cantidad válida: 5 | Incrementar existencia | Existencia pasa de 10 a 15 | Aprobada |
| CP-08 | Salida de producto | Cantidad disponible: 4 | Reducir existencia | Existencia pasa de 10 a 6 | Aprobada |
| CP-09 | Registrar producto | Precio negativo: −$100 | Mostrar error de validación | «El precio no puede ser negativo.» | Aprobada |
| CP-10 | Salida de producto | Cantidad mayor a la existencia: 50 | Mostrar error; la existencia no cambia | «Existencia insuficiente de LAP-001…»; existencia sigue en 10 | Aprobada |
| CP-11 | Consultar existencias | Dos productos registrados | Lista de código y existencia | `LAP-001: 10`, `MOU-010: 30` | Aprobada |
| CP-12 | Generar reporte | 2 productos, salida de 8 laptops, entrada de 10 mouses | Totales correctos y productos bajo stock mínimo | 2 productos, 42 unidades, $35,000; bajo mínimo: `LAP-001` | Aprobada |
| CP-13 | Entrada de producto | Cantidad 0 | Mostrar error de validación | «La cantidad debe ser un número entero mayor que cero.» | Aprobada |

**Resumen de la ejecución:** 13 casos, 13 aprobados, 0 no aprobados (PHPUnit 11.5, PHP 8.2.12, 28 aserciones).
