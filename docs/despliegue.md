# Estrategia de despliegue

## ¿Qué estrategia utilizaríamos y por qué?

**Blue/Green.** Se mantienen dos ambientes de producción iguales: *Blue* (la versión que usan hoy) y *Green* (la versión nueva). La versión nueva se instala y se prueba en Green sin afectar a nadie; cuando pasa las pruebas, el tráfico se cambia de Blue a Green en un solo paso. Si algo falla, se regresa a Blue en segundos.

La elegimos porque el inventario es el registro de existencias de la empresa: si una versión nueva calcula mal una salida, el error afecta compras y ventas. Con Blue/Green no hay tiempo fuera de servicio, la versión anterior queda lista para volver a ella y, como el sistema ya corre en contenedores Docker, levantar un segundo ambiente idéntico es sencillo.

Descartamos *Canary* y *A/B Testing* porque necesitan muchos usuarios y medición de tráfico, y el inventario lo usan pocas personas. *Production Cutover* deja el sistema detenido durante el cambio y sin vuelta atrás rápida.

## Tabla de análisis

| Elemento | Descripción |
|---|---|
| Estrategia seleccionada | Blue/Green |
| Ambiente | **Desarrollo:** equipo de cada integrante con Docker Compose. **Pruebas:** GitHub Actions ejecuta PHPUnit y construye la imagen en cada PR; la rama `release/*` se instala en el ambiente Green para las pruebas de aceptación. **Producción:** servidor con dos contenedores (Blue y Green) detrás de un proxy (Nginx) que decide cuál recibe el tráfico. |
| Proceso | 1) Merge de `release/x.y` a `main`. 2) GitHub Actions construye la imagen `sistema-inventario:vX.Y`. 3) Se levanta en el ambiente inactivo (Green). 4) Se revisa `/salud` y se hacen pruebas de humo (CP-03, CP-07, CP-12). 5) La cliente aprueba. 6) El proxy cambia el tráfico a Green. 7) Blue se conserva sin tráfico hasta la siguiente versión. |
| Riesgos | Cambios en la base de datos que la versión anterior no entienda (una sola base para los dos ambientes); doble consumo de recursos del servidor; diferencias de configuración entre Blue y Green. |
| Ventajas | Sin tiempo fuera de servicio; reversión inmediata; se prueba en un ambiente igual al real antes de abrirlo a usuarios; el proceso es repetible gracias a Docker. |
| Plan de recuperación | Si `/salud` falla o aparece un error crítico: 1) el proxy regresa el tráfico a Blue (menos de 1 minuto). 2) Se registra el incidente en Trello. 3) Se corrige en una rama `fix/*` desde `develop` y se repite el flujo. Antes de cada liberación se respalda la base de datos (`pg_dump`) y los cambios de esquema se hacen compatibles hacia atrás (primero se agregan columnas, después se eliminan las viejas). |
