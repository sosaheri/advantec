# Advantec

Advantec es un repositorio inicial para comenzar el desarrollo del proyecto. Este documento explica cómo arrancar el repositorio y las tareas básicas para contribuir.

## Resumen

Repositorio con una arquitectura modular que utiliza Laravel 12 para la API principal y un servicio Node.js opcional para simulación de despachos. La intención es soportar procesamiento asíncrono de órdenes mediante colas y trabajadores.

## Requisitos

- Git
- Composer (para instalación de dependencias PHP)
- Docker Desktop (opcional, para levantar servicios aislados)

## Inicio rápido

1. Clonar el repositorio:

```bash
git clone https://github.com/sosaheri/advantec.git
cd advantec
```

2. Instalar dependencias PHP:

```bash
composer install
```

3. Copiar el archivo de entorno y ajustar variables según sea necesario:

```bash
cp .env.example .env
```

4. (Opcional) Levantar servicios con Docker:

```bash
docker compose up -d
```

5. Inicializar la aplicación (generar clave y migraciones):

```bash
php artisan key:generate
php artisan migrate
```

## Comprobaciones rápidas

- Para verificar que PHP está disponible:

```bash
php -v
```

- Para listar rutas (comprobar que la aplicación arranca):

```bash
php artisan route:list
```

## Contribuciones

1. Cree una rama: `git checkout -b feat/descripcion`
2. Realice cambios y haga commits claros
3. Abra un Pull Request hacia `main`

## Licencia

No se ha añadido una licencia. Añada un archivo `LICENSE` si desea publicar el proyecto con términos específicos.

---

Si desea, puedo añadir un archivo `.gitignore` básico y una `LICENSE` (MIT) y commitearlos.