1 # CONTEXTO DEL PROYECTO: iCita
2
3 ## 1. Estado Actual del Proyecto
4 - **Backend:** Completado. Estructura de base de datos, modelos, seeders de administración, controlador de autenticación y rutas API listas.
5 - **Frontend:** Completado. Estructura Vue 3, Pinia (Auth Store), Login y Dashboard con Tailwind CSS, y Router con autenticación protegida.
6 - **Integración:** Tailwind CSS configurado manualmente.
7 - **Pendiente:** Ejecutar migraciones y seeders en el entorno de desarrollo, verificar el flujo completo de autenticación (CORS/Cookies).
8
9 ## 2. Arquitectura y Tech Stack
10 - **Backend:** Laravel 13 (API pura).
11 - **Autenticación:** Laravel Sanctum (Stateful, basado en cookies de sesión).
12 - **Frontend:** Vue 3 (Composition API, Vite).
13 - **Estilos:** Tailwind CSS v4.
14 - **Base de Datos:** PostgreSQL.
15
16 ## 3. Estructura de Base de Datos
17 - **users:** `id`, `email`, `email_verified_at`, `password`, `role` ('admin', 'patient', 'doctor', 'secretary'), `created_at`, `updated_at`.
18 - **people:** `id`, `user_id` (1:1 con users, cascade), `full_name`, `ci`, `phone_number`, `photo`, `date_birth`, `address`, `created_at`, `updated_at`.
19
20 ## 4. Últimos Cambios e Historial
21 - Implementación de controladores de Auth y rutas de API (Sanctum).
22 - Creación de componentes Vue (Login/Dashboard) y configuración de Pinia para persistencia de usuario.
23 - Instalación y configuración manual de Tailwind CSS (debido a incompatibilidad de versión de Node).
24 - Corrección de `App.vue` para renderizado del Router.
25
