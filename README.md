# Sistema de Control de Inventario

Caso de estudio de la actividad **"Implementación de un flujo DevOps"** — Universidad Tecnológica de Campeche, Ingeniería en Desarrollo y Gestión de Software.

El sistema permitirá registrar, consultar, modificar y eliminar productos, consultar existencias, registrar entradas y salidas, y generar un reporte básico de inventario. Este repositorio prepara el entorno de trabajo (herramientas, pruebas, flujo de ramas y despliegue) para recibir el código fuente.

## Equipo

| Integrante | Rol |
|---|---|
| Emily Yamilett Pinzón Sánchez | Gestora de proyectos |
| Chuina Guadalupe García Campos | Analista y Tester |
| Pablo Alejandro Magaña Hernández | Desarrollador / DevOps |
| Emmanuel Hernández Barbis | Diseñador |
| Ana Edith Rodríguez Pérez | Cliente (aceptación) |

## Estructura

```
sistema-inventario/
├── README.md
├── .gitignore
├── docs/
│   ├── configuracion.md     Herramientas y parámetros de configuración
│   ├── plan-pruebas.md      Plan de pruebas
│   ├── casos-prueba.md      Casos de prueba CP-01 a CP-13
│   ├── flujo-git.md         Flujo de trabajo con Git
│   └── despliegue.md        Estrategia de despliegue (Blue/Green)
├── src/                     Código fuente (estructura inicial)
├── tests/                   Pruebas automatizadas (PHPUnit)
├── public/                  Punto de entrada web
├── .github/workflows/       Pipeline de CI (GitHub Actions)
├── Dockerfile
└── docker-compose.yml
```

## Requisitos

- PHP 8.2 y Composer 2
- Docker (para contenedores)
- Git

## Uso

```bash
composer install            # instala dependencias
composer test               # ejecuta los casos de prueba
php -S localhost:8000 -t public   # levanta el sistema en local
```

Con Docker:

```bash
cp .env.example .env
docker compose up --build   # app en http://localhost:8000 y PostgreSQL en 5432
```

## Flujo de trabajo

`main` (producción) ← `release/x.y` ← `develop` (integración) ← `feature/*` (funcionalidades).
Todo cambio entra por Pull Request con revisión y con el pipeline de CI en verde. Ver [docs/flujo-git.md](docs/flujo-git.md).
