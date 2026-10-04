# Plan de pruebas

Basado en ISO/IEC/IEEE 29119-3 (documentación de pruebas).

## Objetivo

Verificar que las funciones principales del Sistema de Control de Inventario operen de acuerdo con los requisitos establecidos, que los datos inválidos se rechacen y que ningún cambio nuevo rompa lo que ya funcionaba.

## Alcance

**Incluye:** registro, consulta, modificación y eliminación de productos; consulta de existencias; entradas y salidas; reporte básico de inventario.

**No incluye (esta versión):** interfaz gráfica, usuarios y permisos, pruebas de carga y conexión con PostgreSQL (se probará cuando se integre la capa de datos).

## Elementos que serán probados

| Elemento | Archivo |
|---|---|
| Clase `Producto` (validación de datos) | `src/Producto.php` |
| Clase `Inventario` (CRUD, existencias, entradas y salidas) | `src/Inventario.php` |
| Clase `ReporteInventario` | `src/ReporteInventario.php` |
| Pipeline de CI y contenedor | `.github/workflows/ci.yml`, `Dockerfile` |

## Tipos de pruebas

| Tipo | ¿Qué se prueba? | Casos |
|---|---|---|
| Funcionales | Que cada operación haga lo que dice el requisito | CP-01, CP-03, CP-05, CP-06, CP-07, CP-08, CP-11 |
| De validación de datos | Que se rechacen datos inválidos con un mensaje claro | CP-02, CP-04, CP-09, CP-10, CP-13 |
| De integración | Que registro, movimientos y reporte funcionen juntos | CP-12 |
| De regresión | Que todo lo anterior siga funcionando después de cada cambio: GitHub Actions ejecuta la suite completa en cada push y Pull Request | Todos |

## Criterios de aceptación

- Los 13 casos de prueba se ejecutan y se aprueban.
- El pipeline de CI termina en verde en `develop` y en `main`.
- La imagen Docker se construye y responde en `/salud`.
- La cliente revisa el reporte de inventario y lo aprueba.

## Criterios de aprobación / rechazo

- **Aprobada:** el resultado obtenido es igual al resultado esperado.
- **No aprobada:** el resultado obtenido es distinto, aparece un error no controlado o la existencia cambia cuando no debía.
- **Suspensión:** si falla la construcción del pipeline, se detienen las pruebas hasta corregirla.
- Un Pull Request **no se integra** si algún caso queda No aprobado.

## Ambiente de pruebas

| Ambiente | Detalle |
|---|---|
| Local | Windows 11, PHP 8.2.12, Composer 2.10.3, PHPUnit 11.5, VS Code |
| Integración continua | GitHub Actions, `ubuntu-latest`, PHP 8.2 |
| Contenedor | Docker, imagen `php:8.2-cli-alpine`; PostgreSQL 16 con Docker Compose |
| Datos de prueba | Productos de ejemplo: `LAP-001` Laptop Lenovo (10 piezas, $12,500) y `MOU-010` Mouse inalámbrico (30 piezas, $250) |

## Responsables

| Integrante | Responsabilidad |
|---|---|
| Chuina Guadalupe García Campos (Tester) | Diseña los casos, ejecuta las pruebas y registra resultados |
| Pablo Alejandro Magaña Hernández (Desarrollador) | Escribe las pruebas automatizadas y corrige defectos |
| Emily Yamilett Pinzón Sánchez (Gestora) | Revisa los Pull Requests y da seguimiento en Trello |
| Ana Edith Rodríguez Pérez (Cliente) | Pruebas de aceptación |

## Herramientas utilizadas

PHPUnit 11.5, Composer, GitHub Actions, Docker, VS Code (extensión PHPUnit) y Trello para el seguimiento de defectos.
