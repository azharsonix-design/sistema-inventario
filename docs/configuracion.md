# Selección y configuración de herramientas

## Herramientas seleccionadas

| Necesidad | Herramienta |
|---|---|
| Editor / IDE | Visual Studio Code |
| Control de versiones | Git |
| Repositorio remoto | GitHub |
| Gestión del proyecto | Trello (tablero Kanban) |
| Lenguaje y dependencias | PHP 8.2 + Composer |
| Pruebas | PHPUnit |
| Base de datos | PostgreSQL |
| Contenedores | Docker y Docker Compose |
| CI/CD | GitHub Actions |
| Documentación | Markdown |

## Parámetros de configuración

| Herramienta | Versión | Configuración | Propósito |
|---|---|---|---|
| Git | 2.55.0 | `user.name`, `user.email` de cada integrante; rama principal `main`; `.gitignore` y `.gitattributes` (fin de línea LF) | Control de versiones |
| GitHub | Servicio web | Repositorio `sistema-inventario` público; ramas `main` y `develop` protegidas (PR obligatorio, 1 aprobación, CI en verde) | Almacenamiento remoto y revisión de código |
| Visual Studio Code | 1.139.1 | Extensiones recomendadas en `.vscode/extensions.json`: PHP Intelephense, PHPUnit, GitLens, Docker, GitHub Actions, Markdown All in One, EditorConfig | Desarrollo |
| PHP | 8.2.12 | Extensiones `mbstring`, `dom`, `xml`, `tokenizer`; `declare(strict_types=1)` | Lenguaje del backend |
| Composer | 2.10.3 | `composer.json` con autocarga PSR-4 (`App\` → `src/`, `Tests\` → `tests/`); script `composer test` | Dependencias |
| PHPUnit | 11.5 | `phpunit.xml`: suite `tests/`, cobertura sobre `src/` | Pruebas automatizadas |
| Docker | Imagen `php:8.2-cli-alpine` | `Dockerfile` en dos etapas; puerto 8000; `HEALTHCHECK` en `/salud` | Contenedorización |
| Docker Compose | Imagen `postgres:16-alpine` | Servicios `app` (8000) y `db` (5432); variables en `.env` (plantilla `.env.example`) | Ambiente local igual al de producción |
| GitHub Actions | `ubuntu-latest` | Workflow `.github/workflows/ci.yml`: en cada push y PR a `main`/`develop` ejecuta PHPUnit y construye la imagen Docker | Integración continua |
| Trello | Servicio web | Listas: Pendiente, En proceso, En revisión, Hecho; una tarjeta por funcionalidad, ligada a su rama | Gestión del proyecto |
| Markdown | GFM | `README.md` y carpeta `docs/` | Documentación |

## Configuración inicial de Git (cada integrante)

```bash
git config --global user.name "Nombre Apellido"
git config --global user.email "correo@ejemplo.com"
git config --global init.defaultBranch main
```

## Protección de ramas en GitHub

Settings → Branches → Add rule para `main` y `develop`:

- Require a pull request before merging (1 aprobación).
- Require status checks to pass: `Pruebas (PHPUnit)` y `Construir imagen Docker`.
- Do not allow bypassing the above settings.
