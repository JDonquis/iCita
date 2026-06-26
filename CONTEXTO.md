# CONTEXTO DEL PROYECTO: iCita

## 1. Estado Actual del Proyecto
- **Backend:** CRUD de personal completado, envío de notificaciones asíncronas para cambio de contraseña implementado, lógica de reseteo de contraseña con invalidación de tokens lista.
- **Frontend:** Login, Dashboard, Listado de Personal y Reset Password implementados. Configuración de Layout responsivo con `Navbar` condicional. Corrección de CORS para endpoints públicos de autenticación.
- **Integración:** Tailwind CSS v4 configurado manualmente.

## 2. Arquitectura y Tech Stack
- **Backend:** Laravel 13 (API pura).
- **Autenticación:** Laravel Sanctum (Stateful, basado en cookies).
- **Frontend:** Vue 3 (Composition API, Vite).
- **Estilos:** Tailwind CSS v4.
- **Base de Datos:** PostgreSQL.

## 3. Estructura de Base de Datos
- **users:** `id`, `email`, `email_verified_at`, `password`, `role`, `person_id` (FK a people, relación Many-to-One), `created_at`, `updated_at`.
- **people:** `id`, `full_name`, `ci`, `phone_number`, `photo`, `date_birth`, `address`, `created_at`, `updated_at`.

## 4. Últimos Cambios e Historial
- Refactorización de relación 1:1 a Many-to-One entre People y Users.
- Implementación de CRUD de Personal en Laravel y Vue.
- Implementación de vista de Reset Password independiente con validación de tokens.
- Corrección de Layout de navegación (condicional) y resolución de CORS para `reset-password`.

## 5. Próximos Pasos Inmediatos
1. **Verificación:** Ejecutar el flujo completo de "Agregar Personal" -> Recibir correo -> Hacer clic en enlace -> Configurar contraseña.
2. **Pruebas de sesión:** Validar que el login funciona correctamente con la nueva contraseña configurada.
3. **Producción:** Configurar el driver SMTP de Mailjet en el `.env` para el envío real de correos.
