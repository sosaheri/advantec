# Advantec - Ecosistema Modular de Órdenes (Laravel 12 + Node.js)

Este repositorio contiene una arquitectura distribuida y desacoplada para el procesamiento asíncrono de órdenes de compra. Está diseñado siguiendo criterios de alta disponibilidad, tolerancia a fallos y monitoreo en tiempo real.

El ecosistema está contenerizado con Docker y consta de:

- Un backend principal en Laravel 12 (API REST).
- Un trabajador de colas (Queue Worker) para el procesamiento asíncrono.
- Una base de datos MySQL.
- Un microservicio satélite en Node.js que simula un sistema externo de logística.

## Arquitectura del sistema

El flujo de datos sigue un patrón asíncrono no bloqueante:

- **Capa HTTP (API REST)**: Un endpoint `POST /api/v1/orders` recibe el payload de la compra y valida los datos mediante un Form Request.
- **Capa de datos y dominio**: La orden se persiste en MySQL con estado `pending`. Se utiliza un Backed Enum de PHP 8+ para garantizar la integridad de los estados.
- **Capa asíncrona (colas)**: Se despacha un Job `ProcessOrderDispatch` a la cola de la base de datos, liberando la conexión HTTP y retornando una respuesta `201 Created` rápidamente.
- **Capa de integración (gateway)**: El Queue Worker procesa la tarea, cambia el estado a `processing` e invoca de forma resiliente (reintentos y backoff) al microservicio Node.js mediante el DNS interno de Docker.
- **Capa de monitoreo**: Un dashboard reactivo construido con Livewire consume los cambios de estado en segundo plano mediante polling asíncrono cada 2 segundos.

## Requisitos previos

- Git
- Docker Desktop (recomendado, con soporte WSL2 en Windows)
- Composer (para instalar dependencias PHP)

## Inicio rápido y despliegue

Sigue estos pasos para levantar la infraestructura:

1. Clonar el repositorio e ingresar al directorio:

```bash
git clone https://github.com/sosaheri/advantec.git
cd advantec
```

2. Instalar dependencias PHP (en el host o dentro de un contenedor según su preferencia):

```bash
composer install
composer require livewire/livewire
```

3. Configurar el entorno:

```bash
cp .env.example .env
# Asegúrese de que QUEUE_CONNECTION=database si desea usar colas de base de datos
```

4. Levantar servicios con Docker (opcional):

```bash
docker compose up -d
```

5. Inicializar la aplicación y la base de datos:

```bash
php artisan key:generate
php artisan migrate
```

> Si usa Docker, puede ejecutar `php artisan` dentro del contenedor `app`:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

## Servicios esperados

Tras levantar los contenedores debería encontrar los siguientes servicios en `docker ps`:

- `laravel_app` (puerto 8000): servidor web de la aplicación y API REST.
- `laravel_queue_worker`: trabajador en segundo plano que procesa colas.
- `mysql_db` (puerto 3306): base de datos relacional.
- `node_dispatch_service` (puerto 3000): microservicio Node.js de simulación de despacho.

## Pruebas y simulaciones

### Opción A — Prueba de humo (curl):

```bash
curl -X POST http://127.0.0.1:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{"customer_name": "Heriberto Sosa", "customer_email": "heriberto@test.com", "total_amount": 149.99}'
```

### Opción B — Simulador de carga (comando Artisan personalizado):

```bash
# Inserta 30 órdenes en paralelo (ejemplo)
docker compose exec app php artisan ecosystem:simulate 30
```

## Comprobaciones rápidas

- Ver versión de PHP:

```bash
php -v
```

- Listar rutas (verificar arranque de la app):

```bash
php artisan route:list
```

## Contribuciones

1. Cree una rama con formato `feat/descripcion` o `fix/descripcion`.
2. Haga commits claros y pequeños.
3. Abra un Pull Request hacia `main`.

## Licencia

No se añadió una licencia por defecto. Añada un archivo `LICENSE` (por ejemplo MIT) si desea publicar el proyecto con términos claros.

---

Si quiere, puedo añadir un archivo `.gitignore` básico y una `LICENSE` (MIT) y commitearlos.
