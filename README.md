# Advantec

Aplicación de procesamiento asíncrono de órdenes de compra con:

- API en Laravel 12
- Worker de colas dedicado
- MySQL
- Microservicio Node.js de despacho simulado
- Panel Livewire para monitoreo en tiempo real

## Arquitectura

Flujo principal:

1. La API recibe una orden (`POST /api/v1/orders`).
2. La orden se guarda con estado inicial `pending`.
3. Se despacha un job a la cola (`ProcessOrderDispatch`).
4. El worker procesa la cola y llama al servicio Node (`/api/v1/dispatch`).
5. La orden cambia de estado (`processing`, `processed` o `failed`) y se refleja en el panel Livewire.

## Requisitos

- Docker Desktop
- Git

Opcional (solo si quieres ejecutar comandos fuera de contenedores):

- PHP 8.2+
- Composer

## Puertos y servicios

Según `docker-compose.yml`:

- App Laravel (host): `http://127.0.0.1:8080`
- MySQL (host): `127.0.0.1:3307`
- Node dispatch mock (host): `http://127.0.0.1:3000`

Contenedores esperados:

- `laravel_app`
- `laravel_queue_worker`
- `mysql_db`
- `node_dispatch_service`

## Setup rápido (recomendado para evaluador)

1. Clonar:

```bash
git clone https://github.com/sosaheri/advantec.git
cd advantec
```

2. Crear entorno:

```bash
cp .env.example .env
```

3. Ajustar DB para Docker en `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
QUEUE_CONNECTION=database
NODE_DISPATCH_SERVICE_URL=http://mock-dispatch-service:3000
```

4. Levantar servicios:

```bash
docker compose up -d --build
```

5. Inicializar app:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize:clear
```

## Verificación mínima

1. Ver contenedores:

```bash
docker compose ps
```

2. Ver worker activo:

```bash
docker compose logs -f queue-worker
```

3. Abrir panel:

```text
http://127.0.0.1:8080
```

## Pruebas funcionales

### 1) Prueba API 

```bash
curl -X POST http://127.0.0.1:8080/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{"customer_name":"Test User","customer_email":"test@example.com","total_amount":149.99}'
```

Resultado esperado: respuesta `201` y la orden aparece en el panel.

### 2) Prueba desde UI 

En el panel principal hay un botón `Despachar aleatorio` al lado de `Escuchando cambios...`.

- Al hacer clic, ejecuta internamente el comando `ecosystem:simulate` con una cantidad aleatoria entre `1` y `50` órdenes.
- El panel se refresca automáticamente cada 2 segundos y muestra los cambios de estado.

### 3) Simulación por consola

```bash
docker compose exec app php artisan ecosystem:simulate 30
```

## Solución rápida de problemas

Si no se reflejan cambios en la UI:

```bash
docker compose exec app php artisan view:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan optimize:clear
docker compose restart app queue-worker
```

Si el worker no procesa:

```bash
docker compose up -d queue-worker
docker compose logs -f queue-worker
```

