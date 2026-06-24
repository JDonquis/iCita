# iCita

Sistema de gestión de citas médicas desarrollado con una arquitectura desacoplada.

## Stack Tecnológico

- **Backend:** Laravel 13 (API pura).
- **Frontend:** Vue 3 (Vite + Composition API + Tailwind CSS v4).
- **Base de Datos:** PostgreSQL.
- **Autenticación:** Laravel Sanctum (Stateful basado en cookies).

## Requisitos Previos

- PHP 8.3+
- Node.js v22.16.0+
- Composer
- PostgreSQL

## Instalación y Configuración

### 1. Backend (Server)

1. Entra en la carpeta `server/`:
   ```bash
   cd server
   ```
2. Instala las dependencias:
   ```bash
   composer install
   npm install
   ```
3. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Asegúrate de configurar los datos de tu base de datos PostgreSQL.*

4. Ejecuta las migraciones y los seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Inicia el servidor:
   ```bash
   php artisan serve
   ```

### 2. Frontend (Client)

1. Entra en la carpeta `client/`:
   ```bash
   cd client
   ```
2. Instala las dependencias:
   ```bash
   npm install
   ```
3. Inicia el servidor de desarrollo:
   ```bash
   npm run dev
   ```

## Autenticación

El sistema utiliza autenticación basada en sesiones (cookies). Asegúrate de que las variables `SESSION_DOMAIN` y `SANCTUM_STATEFUL_DOMAINS` en el `.env` de Laravel coincidan con la URL de tu entorno de desarrollo.
