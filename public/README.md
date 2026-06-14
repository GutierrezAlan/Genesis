# Genesis - Ecommerce Frontend

Frontend completo del ecommerce **Genesis** desarrollado con **JavaScript Vanilla + HTML + CSS**.

## 📋 Estructura del Proyecto

```
public/
├── index.html              # HTML principal
├── css/
│   ├── style.css          # Estilos principales
│   └── responsive.css     # Diseño responsive
└── js/
    ├── app.js             # Aplicación principal
    ├── utils.js           # Utilidades generales
    ├── api.js             # Cliente API
    ├── auth.js            # Gestor de autenticación
    ├── cart.js            # Gestor de carrito
    ├── router.js          # Sistema de enrutamiento
    ├── components.js      # Componentes reutilizables
    └── pages.js           # Lógica de páginas
```

## ✨ Características

### 👤 Autenticación
- ✅ Login y Registro de usuarios
- ✅ Gestión de sesiones con JWT
- ✅ Roles y Permisos (Admin/Usuario)
- ✅ Opción "Recuérdame"

### 🛍️ Tienda
- ✅ Catálogo de productos responsive
- ✅ Búsqueda y filtrado de productos
- ✅ Página de detalle de producto
- ✅ Gestión de stock

### 🛒 Carrito
- ✅ Agregar/remover productos
- ✅ Actualizar cantidades
- ✅ Carrito persistente por usuario (localStorage)
- ✅ Cálculo automático de totales

### 💳 Checkout
- ✅ Resumen de compra
- ✅ Procesamiento de órdenes
- ✅ Confirmación de pedido

### 📦 Órdenes
- ✅ Ver mis órdenes
- ✅ Historial de compras
- ✅ Estado de órdenes

### 👑 Panel de Administración
- ✅ Gestión de productos
- ✅ Crear/Editar/Eliminar productos
- ✅ Gestión de inventario
- ✅ Vista de todas las órdenes

### 📱 Responsive
- ✅ Diseño mobile-first
- ✅ Optimizado para tablets y desktop
- ✅ Menú hamburguesa en móvil
- ✅ Imágenes adaptables

## 🚀 Instalación

### Requisitos
- Backend Laravel corriendo en `http://127.0.0.1:8000`
- Servidor web (Apache, Nginx, PHP Built-in)

### Pasos

1. **Copiar archivos al servidor**
   ```bash
   # Los archivos ya están en c:\Proyecto-PHP\mi-backend\public\
   ```

2. **Iniciar el servidor**

   Con PHP Built-in:
   ```bash
   cd mi-backend
   php artisan serve
   ```

   O acceder a través de tu servidor web configurado.

3. **Abrir en navegador**
   ```
   http://localhost:8000
   ```

## 🔧 Configuración API

La aplicación se conecta automáticamente a la API del backend en:
```
http://127.0.0.1:8000/api
```

Para cambiar la URL, editar en `js/api.js`:
```javascript
this.baseURL = 'http://127.0.0.1:8000/api';
```

## 📚 Endpoints API Utilizados

### Autenticación
- `POST /api/login` - Iniciar sesión
- `POST /api/register` - Registrarse
- `POST /api/logout` - Cerrar sesión
- `GET /api/me` - Usuario actual

### Productos
- `GET /api/productos` - Obtener todos
- `GET /api/productos/:id` - Obtener uno
- `POST /api/productos` - Crear (admin)
- `PUT /api/productos/:id` - Actualizar (admin)
- `DELETE /api/productos/:id` - Eliminar (admin)

### Órdenes
- `POST /api/ordenes` - Crear orden
- `GET /api/ordenes/mis-ordenes` - Mis órdenes
- `GET /api/ordenes` - Todas (admin)
- `PUT /api/ordenes/:id` - Actualizar (admin)

## 🎨 Personalización

### Colores
Editar en `css/style.css`:
```css
:root {
    --primary-color: #0094FF;
    --secondary-color: #00d4ff;
    --danger-color: #dc3545;
    /* ... más colores */
}
```

### Tipografía
```css
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
```

### Puntos de Quiebre Responsive
- Mobile: < 480px
- Tablet: 480px - 768px
- Desktop: > 768px

## 🔐 Seguridad

- 🔒 Tokens JWT almacenados en localStorage
- 🔐 Headers de autorización automáticos
- ✅ Validación de roles en frontend
- 🛡️ Protección de rutas autenticadas

## 📊 Estados de Órdenes

- `pending` - Pendiente
- `completed` - Completada
- `cancelled` - Cancelada

## 🐛 Troubleshooting

### "Error al conectar con API"
- Verificar que el backend esté corriendo
- Verificar URL en `js/api.js`
- Revisar CORS en backend

### "Carrito no se guarda"
- Verificar localStorage habilitado
- Limpiar caché del navegador
- Checkear permisos de almacenamiento

### "No puedo agregar productos"
- Verificar que esté autenticado
- Verificar roles de usuario
- Revisar stock disponible

## 📝 Uso

### Como Cliente

1. **Registrarse/Iniciar Sesión**
   - Click en "Iniciar Sesión" en navbar
   - Llenar formulario y enviar

2. **Comprar Productos**
   - Navegar por el catálogo
   - Click en producto para ver detalles
   - Agregar al carrito
   - Ir a carrito para proceder al checkout

3. **Ver Órdenes**
   - Click en "Mis Órdenes" en navbar
   - Ver historial de compras

### Como Administrador

1. **Acceder Panel Admin**
   - Login como usuario con rol admin
   - Click en "Panel Admin" (👑)

2. **Gestionar Productos**
   - Click "Nuevo Producto"
   - Llenar formulario
   - Click "Guardar"

3. **Editar/Eliminar**
   - En tabla de productos
   - Click "Editar" o "Eliminar"

## 💾 Almacenamiento Local

La aplicación usa localStorage para:
- `auth_token` - Token JWT
- `user` - Datos del usuario
- `roles` - Roles del usuario
- `permissions` - Permisos del usuario
- `carrito_anonimo` - Carrito sin auth (no se usa, requiere login)
- `carrito_user_[ID]` - Carrito por usuario

## 🎯 Flujo de Navegación

```
Inicio (/)
  ├─→ Login (/login)
  ├─→ Register (/register)
  ├─→ Detalle Producto (/item/:id)
  ├─→ Carrito (/carrito)
  │    └─→ Checkout (/checkout)
  ├─→ Mis Órdenes (/mis-ordenes)
  └─→ Panel Admin (/admin) [Solo Admin]
```

## 📦 Dependencias

- **Ninguna librería externa** - Todo en JavaScript vanilla
- **Compatibilidad**: Chrome, Firefox, Safari, Edge (últimas versiones)

## 🚀 Optimizaciones

- ✅ Carga asincrónica de datos
- ✅ Caché de localStorage
- ✅ Renderizado eficiente
- ✅ Compresión de CSS/JS
- ✅ Lazy loading de imágenes

## 📞 Soporte

Para reportar bugs o sugerencias, contactar al equipo de desarrollo.

---

**Desarrollado con ❤️ usando JavaScript Vanilla**
