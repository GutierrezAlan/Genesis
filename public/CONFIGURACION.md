# Configuración del Frontend Ecommerce

## Variables de Configuración

### URL de API
Por defecto la aplicación se conecta a:
```
http://127.0.0.1:8000/api
```

**Para cambiar**, editar en `js/api.js` línea 4:
```javascript
this.baseURL = 'http://localhost:3000/api'; // Cambiar aquí
```

### Configuración por Ambiente

#### Desarrollo Local
```javascript
// js/api.js
this.baseURL = 'http://127.0.0.1:8000/api';
```

#### Producción
```javascript
// js/api.js
this.baseURL = 'https://tudominio.com/api';
```

## Variables de Almacenamiento (localStorage)

La aplicación usa localStorage para persistir datos. Estos se guardan automáticamente:

```javascript
{
  // Autenticación
  'auth_token': 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
  'user': {
    'id': 1,
    'first_name': 'Juan',
    'email': 'juan@example.com',
    'roles': ['user']
  },
  'roles': ['user'],
  'permissions': ['purchase'],
  
  // Carrito
  'carrito_user_1': [
    {
      'id': 1,
      'titulo': 'Producto',
      'price': 100,
      'cantidad': 2
    }
  ]
}
```

## Debugging

### Habilitar logs en consola
Toda la aplicación usa `console.log()` para debugging. Abre DevTools (F12) para ver:

```javascript
// Ver estado de autenticación
console.log(auth.user);
console.log(auth.isAuthenticated());

// Ver carrito
console.log(cart.getItems());
console.log(cart.getTotalPrice());

// Ver página actual
console.log(router.getCurrentPage());

// Ver todas las rutas
console.log(router.routes);
```

## Requisitos Mínimos

### Backend
- ✅ Laravel 12.0 o superior
- ✅ PHP 8.2 o superior
- ✅ MySQL/PostgreSQL
- ✅ Sanctum para JWT
- ✅ CORS habilitado

### Frontend
- ✅ Navegador moderno (ES6+)
- ✅ JavaScript habilitado
- ✅ localStorage habilitado
- ✅ Conexión a Internet

### Servidor Web
- ✅ Apache/Nginx/IIS
- ✅ O PHP Built-in (`php artisan serve`)

## CORS - Configuración Importante

El backend debe permitir CORS desde el frontend. En `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:8000', 'http://127.0.0.1:8000'],
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

## Endpoints Esperados

El backend DEBE proporcionar estos endpoints:

### Auth
- `POST /api/login` - Response: `{status: 'success', data: {token, user}}`
- `POST /api/register` - Response: `{status: 'success', data: {token, user}}`
- `POST /api/logout` - Response: `{status: 'success'}`
- `GET /api/me` - Response: `{data: {user object}}`

### Productos
- `GET /api/productos` - Response: `{data: [...]}`
- `GET /api/productos/:id` - Response: `{data: {...}}`
- `POST /api/productos` - Response: `{status: 'success', data: {...}}`
- `PUT /api/productos/:id` - Response: `{status: 'success', data: {...}}`
- `DELETE /api/productos/:id` - Response: `{status: 'success'}`

### Órdenes
- `POST /api/ordenes` - Response: `{status: 'success', data: {...}}`
- `GET /api/ordenes/mis-ordenes` - Response: `{data: [...]}`
- `GET /api/ordenes` - Response: `{data: [...]}`
- `PUT /api/ordenes/:id` - Response: `{status: 'success', data: {...}}`

## Performance

### Optimizaciones Aplicadas
- ✅ Sin librerías externas (0 dependencias)
- ✅ Tamaño reducido (~50KB todo minificado)
- ✅ Caché inteligente con localStorage
- ✅ Lazy loading de contenido
- ✅ Eventos optimizados

### Tiempo de Carga
- HTML: ~5KB
- CSS: ~30KB
- JS: ~40KB
- **Total: ~75KB** (sin assets)

## Compatibilidad

| Navegador | Versión Mínima | Estado |
|-----------|----------------|--------|
| Chrome    | 90+            | ✅ Completo |
| Firefox   | 88+            | ✅ Completo |
| Safari    | 14+            | ✅ Completo |
| Edge      | 90+            | ✅ Completo |
| IE 11     | -              | ❌ No soportado |

## Mejoras Futuras

- [ ] Búsqueda y filtrado avanzado
- [ ] Carrito abandonado
- [ ] Wishlist/Favoritos
- [ ] Reseñas de productos
- [ ] Sistema de puntos
- [ ] Cupones/Descuentos
- [ ] Múltiples formas de pago
- [ ] Seguimiento en tiempo real
- [ ] Chat de soporte
- [ ] Notificaciones push

## FAQ

**P: ¿Dónde se guardan los datos del usuario?**
R: En localStorage del navegador. Los datos se sincronizan con el backend mediante JWT.

**P: ¿Funciona sin internet?**
R: Parcialmente. Las acciones básicas funcionan en caché, pero no sin conexión al backend.

**P: ¿Cómo borro mi historial de sesión?**
R: Abre DevTools y ejecuta: `localStorage.clear()`

**P: ¿Puedo usar una API diferente?**
R: Sí, cambia la URL en `js/api.js` y asegúrate que los endpoints coincidan.

**P: ¿Es seguro almacenar el token en localStorage?**
R: Para aplicaciones de bajo riesgo sí. Para máxima seguridad, usa httpOnly cookies en el backend.

---

**Última actualización: 2024**
