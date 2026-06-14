# Sistema de Roles - Instrucciones de Implementación

## 📋 Resumen de cambios realizados

Se ha implementado un sistema completo de roles y permisos (Admin y Usuarios) con las siguientes funcionalidades:

### ✅ **Backend (PHP/Laravel)**

1. **ProductController** - Implementado CRUD completo:
   - `GET /productos` - Listar todos (público)
   - `GET /productos/{id}` - Ver un producto (público)
   - `POST /productos` - Crear (solo admin)
   - `PUT /productos/{id}` - Actualizar (solo admin)
   - `DELETE /productos/{id}` - Eliminar (solo admin)

2. **Modelo Order y OrderController** - Gestión de pedidos:
   - `GET /ordenes` - Ver órdenes (admin: todas, usuario: las suyas)
   - `POST /ordenes` - Crear pedido (usuario autenticado)
   - `PUT /ordenes/{id}/approve` - Aprobar (solo admin)
   - `PUT /ordenes/{id}/cancel` - Cancelar (admin o dueño)

3. **Tabla `orders`** - Migración creada:
   - Almacena items en JSON
   - Estados: pending, approved, cancelled
   - Relación con usuarios

4. **Permisos y Roles**:
   - **Admin**: acceso total a productos y órdenes
   - **User**: solo puede ver/crear sus órdenes
   - Permisos específicos: create-product, edit-product, delete-product, approve-order, etc.

### ✅ **Frontend (React)**

1. **AuthContext mejorado**:
   - Almacena `roles` y `permissions`
   - Métodos: `hasRole()`, `isAdmin()`, `hasPermission()`
   - Sincronización con localStorage

2. **Componentes nuevos**:
   - `ProtectedRoute` - Protege rutas por autenticación y roles
   - `pages/Admin.jsx` - Panel de admin para gestionar productos y órdenes
   - `pages/MyOrders.jsx` - Ver mis pedidos
   - `pages/Checkout.jsx` - Procesar compra

3. **Navbar mejorado**:
   - Muestra "Panel Admin" solo para admins
   - Muestra "Mis Órdenes" para usuarios autenticados
   - Muestra nombre del usuario
   - Botón "Cerrar sesión"

4. **Servicios API nuevos**:
   - `productService` - CRUD de productos
   - `orderService` - Gestión de órdenes

5. **Carrito mejorado**:
   - Botón "Proceder al Checkout"
   - Eliminación individual de items
   - Mejor diseño responsive

---

## 🚀 Pasos para implementar

### Backend (PHP)

1. **Ejecutar migraciones**:
   ```bash
   cd c:\Proyecto-PHP\mi-backend
   php artisan migrate
   ```

2. **Ejecutar seeders para crear roles y permisos**:
   ```bash
   php artisan db:seed --class=RoleSeeder
   ```

3. **Probar endpoints**:
   - Con Postman o similar
   - Asegúrate de que el servidor esté en `http://localhost:8000`

### Frontend (React)

1. **Instalar dependencias** (si no están ya):
   ```bash
   cd c:\Users\alan1\OneDrive\Escritorio\React\mi-app
   npm install
   ```

2. **Verificar las nuevas rutas** en `src/App.jsx`:
   - `/admin` - Panel de administración
   - `/mis-ordenes` - Mis pedidos
   - `/checkout` - Procesar compra

3. **Ejecutar la aplicación**:
   ```bash
   npm run dev
   ```

---

## 📝 Guía de uso

### Para ADMIN:
1. Registrarse/Login con credenciales admin
2. En el navbar verás "Panel Admin"
3. Puedes:
   - **Gestionar Productos**: Crear, editar, eliminar
   - **Gestionar Órdenes**: Ver todas, aprobar o cancelar

### Para USUARIOS:
1. Registrarse como usuario normal
2. Comprar productos:
   - Ver productos en home
   - Agregar al carrito
   - Ir a checkout
   - Confirmar compra
3. Ver mis pedidos:
   - Link "Mis Órdenes" en navbar
   - Ver estado de cada pedido
   - Cancelar si está pendiente

---

## 🔐 Permisos y Roles

| Rol | Permisos |
|-----|----------|
| **Admin** | Acceso total a todo |
| **User** | Ver productos, crear órdenes, ver sus órdenes |

### Permisos específicos:
- `view-products` - Ver productos
- `create-product` - Crear productos
- `edit-product` - Editar productos
- `delete-product` - Eliminar productos
- `view-all-orders` - Ver todas las órdenes
- `approve-order` - Aprobar órdenes
- `cancel-order` - Cancelar órdenes
- `view-own-orders` - Ver propias órdenes

---

## 📂 Archivos nuevos/modificados

### Nuevo Backend:
- `app/Models/Order.php` - Modelo de órdenes
- `app/Http/Controllers/OrderController.php` - Controlador de órdenes
- `database/migrations/2026_03_29_000000_create_orders_table.php` - Migración

### Backend modificado:
- `app/Http/Controllers/ProductController.php` - CRUD completo
- `routes/api.php` - Rutas actualizadas
- `database/seeders/RoleSeeder.php` - Permisos actualizados

### Nuevo Frontend:
- `src/pages/Admin.jsx` - Panel de admin
- `src/pages/MyOrders.jsx` - Mis órdenes
- `src/pages/Checkout.jsx` - Checkout
- `src/component/ProtectedRoute.jsx` - Rutas protegidas
- `src/styles/Admin.css` - Estilos admin
- `src/styles/MyOrders.css` - Estilos mis órdenes
- `src/styles/Checkout.css` - Estilos checkout
- `src/component/carrito/carrito.css` - Estilos carrito mejorado

### Frontend modificado:
- `src/Contexto/AuthContext.jsx` - Mejorado con roles
- `src/services/api.js` - Servicios de productos y órdenes
- `src/component/Navbar.jsx` - Links y lógica de roles
- `src/component/carrito/Carrito.jsx` - Mejorado con checkout
- `src/App.jsx` - Rutas nuevas
- `src/App.css` - Estilos de admin link

---

## ⚠️ Notas importantes

1. **Base de datos**: Ejecuta las migraciones y seeders para crear roles
2. **CORS**: Asegúrate que el backend permita llamadas del frontend
3. **Variables de entorno**: Verifica que `VITE_API_URL` esté correcta
4. **Permisos**: Por defecto, nuevos usuarios tienen rol "user"
5. **Admin**: Debes asignar manualmente el rol "admin" o crear un seeder

---

## 🔧 Crear usuario ADMIN

Si necesitas crear un usuario admin en la BD:

```php
$user = \App\Models\User::find(1); // o el ID que quieras
$user->assignRole('admin');
```

O via artisan tinker:
```bash
php artisan tinker
>>> $user = \App\Models\User::find(1);
>>> $user->assignRole('admin');
```

---

## 📞 Próximos pasos sugeridos

1. Agregar notificaciones por email
2. Sistema de pagos
3. Seguimiento de pedidos
4. Calificaciones y comentarios de productos
5. Carrito persistente con la BD

