# Flujo de trabajo para control de versiones

## Ramas

```
main                      Producción: solo versiones liberadas (v1.0.0, v1.1.0…)
  │
  ├── develop             Integración: aquí se juntan las funcionalidades terminadas
  │      │
  │      ├── feature/productos    Registrar, consultar, modificar y eliminar productos
  │      ├── feature/inventario   Entradas, salidas y existencias
  │      └── feature/reportes     Reporte básico de inventario
  │
  └── release/1.0         Preparación de la versión: pruebas finales antes de main
```

Convención de commits: `tipo: descripción` (`feat`, `fix`, `test`, `docs`, `ci`, `chore`).

## Pasos

1. **Creación del repositorio.** Se crea `sistema-inventario` en GitHub, se clona y se sube la estructura inicial a `main`.
   ```bash
   git clone https://github.com/<usuario>/sistema-inventario.git
   ```
2. **Creación de ramas.** Desde `main` se crea `develop`; desde `develop`, una rama `feature/*` por funcionalidad.
   ```bash
   git switch -c develop && git push -u origin develop
   git switch -c feature/productos develop
   ```
3. **Desarrollo de funcionalidades.** Cada integrante trabaja solo en su rama `feature/*` y mueve su tarjeta en Trello a «En proceso».
4. **Commit.** Cambios pequeños con mensaje claro.
   ```bash
   git add src/Inventario.php tests/ProductoTest.php
   git commit -m "feat: registrar, consultar, modificar y eliminar productos"
   ```
5. **Push.** Se sube la rama al repositorio remoto.
   ```bash
   git push -u origin feature/productos
   ```
6. **Pull Request.** En GitHub se abre un PR de `feature/productos` → `develop`, describiendo el cambio y los casos de prueba que cubre.
7. **Revisión del código.** Otro integrante (la Gestora o la Tester) revisa el PR; GitHub Actions ejecuta las pruebas. Si algo falla, se corrige en la misma rama.
8. **Integración a develop.** Con la aprobación y el CI en verde se hace *merge* a `develop` y se borra la rama `feature`.
9. **Liberación a main.** Cuando `develop` tiene todo lo de la versión se crea `release/1.0`, se hacen las pruebas finales, se abre un PR a `main`, se integra y se etiqueta la versión.
   ```bash
   git switch -c release/1.0 develop && git push -u origin release/1.0
   # PR release/1.0 → main, merge, y después:
   git tag -a v1.0.0 -m "Versión 1.0.0" && git push origin v1.0.0
   ```
