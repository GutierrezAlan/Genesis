# Implementación de Spatie Roles en mi-backend

## 🔎 Análisis del proyecto

El backend `mi-backend` ya incluye la biblioteca `spatie/laravel-permission` en `composer.json` y está configurada para gestionar roles y permisos.

### Archivos clave encontrados
- `composer.json` -> `spatie/laravel-permission` ya está en `require`
- `app/Models/User.php` -> usa el trait `Spatie\Permission\Traits\HasRoles`
- `config/permission.php` -> configuración del paquete Spatie
- `database/seeders/RoleSeeder.php` -> crea roles y permisos iniciales
- `database/seeders/AdminUserSeeder.php` -> crea un usuario admin y le asigna el rol `admin`
- `routes/api.php` -> utiliza middleware `role:admin` y `permission:access-admin-panel`

## ✅ Qué hace la integración

1. `User` está preparada para trabajar con roles y permisos.
2. Se generan tablas de Spatie mediante la migración existente `database/migrations/4_create_permission_tables.php`.
3. El seeder `RoleSeeder` crea permisos y roles básicos:
   - Roles: `user`, `admin`
   - Permisos: `access-admin-panel`, `manage-users`, `edit-content`, `create-product`, `edit-product`, `delete-product`, `view-products`, `view-all-orders`, `approve-order`, `cancel-order`, `view-own-orders`, `delete-content`, `view-reports`
4. El rol `admin` recibe todos los permisos disponibles.
5. En `AdminUserSeeder` se crea/actualiza un usuario admin con rol `admin`.
6. Las rutas protegidas en `routes/api.php` aplican control de acceso con middleware:
   - `role:admin` para acciones de productos y aprobación de órdenes
   - `permission:access-admin-panel` para acceder al panel de administración

## 🧩 Uso principal en el backend

- `hasRole('admin')` y `hasPermissionTo('permission-name')` están disponibles en el modelo `User`.
- Las rutas se aseguran con middleware Spatie, por ejemplo:
  - `Route::apiResource('productos', ProductController::class)->only(['store', 'update','destroy'])->middleware(['auth:sanctum', 'role:admin']);`
  - `Route::get('/admin/dashboard', ...)->middleware('permission:access-admin-panel');`

## 🚀 Cómo probarlo

Desde `c:\Proyecto-PHP\mi-backend` ejecuta:

```bash
composer install
php artisan migrate
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=AdminUserSeeder
```

Luego inicia el servidor y prueba con el usuario admin:

- Email: `admin@genesis.com`
- Contraseña: `admin123`

## 📂 Archivos relevantes

- `composer.json`
- `app/Models/User.php`
- `config/permission.php`
- `database/migrations/4_create_permission_tables.php`
- `database/seeders/RoleSeeder.php`
- `database/seeders/AdminUserSeeder.php`
- `routes/api.php`

## 💡 Comentario final

La integración de Spatie ya está implementada en el backend y permite gestionar roles y permisos de forma estándar en Laravel. Con los seeders actuales puedes crear rápidamente los roles y dar acceso administrativo con el rol `admin`.
