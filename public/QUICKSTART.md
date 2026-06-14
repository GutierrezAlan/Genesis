# 🚀 Quick Start - Inicio Rápido

## ⚡ En 5 minutos

### 1. Backend Corriendo
```bash
cd mi-backend
php artisan serve
```
✅ Debería estar en: `http://127.0.0.1:8000`

### 2. Abrir en Navegador
```
http://127.0.0.1:8000
```

### 3. Registrarse
- Click "Iniciar Sesión" → "Registrarse"
- Llenar datos
- Click "Registrarse"
- ✅ Automáticamente inicia sesión

### 4. Comprar Producto
1. Click en cualquier producto
2. Aumentar cantidad (+ / -)
3. Click "Agregar al Carrito"
4. Click 🛒 en navbar
5. Click "Proceder al Checkout"
6. Click "Confirmar Orden"
7. ✅ Orden creada

### 5. Ver Órdenes
- Click "Mis Órdenes" en navbar
- ✅ Verás tu orden reciente

### 6. Admin (Si tienes admin)
- Login como admin
- Click 👑 "Panel Admin"
- Crear, editar o eliminar productos
- ✅ Listo

---

## 🐛 Si Algo No Funciona

### "Blank Page" / "No carga nada"
```
→ F5 (Recargar página)
→ Limpiar caché: Ctrl+Shift+Delete
→ Abrir DevTools: F12 → Console
→ Ver qué error aparece
```

### "Error: Cannot connect to API"
```
→ Verificar backend: php artisan serve
→ Verificar URL sea http://127.0.0.1:8000
→ Verificar CORS en backend/config/cors.php
```

### "Error al registrar"
```
→ Email debe ser único
→ Contraseñas deben coincidir
→ Todos campos requeridos
```

### "Carrito no guarda"
```
→ localStorage debe estar habilitado
→ Browser no está en privado/incógnito
→ Verificar DevTools → Application → localStorage
```

### "No puedo comprar"
```
→ Debe estar registrado
→ Carrito debe tener productos
→ Backend corriendo
```

---

## 📱 Testing en Móvil

### Emular en PC
1. Abrir DevTools: F12
2. Click icono móvil (Ctrl+Shift+M)
3. Probar en 320px, 480px, 768px

### En teléfono real
1. Saber IP de tu PC: `ipconfig`
2. En teléfono: `http://[TU_IP]:8000`

---

## 👤 Credenciales de Prueba

Si el backend tiene seeders:
```
Email: admin@example.com
Password: password
Rol: admin
```

---

## 📊 Verificar en DevTools

Abre Console (F12) y pega:

```javascript
// ¿Está autenticado?
auth.isAuthenticated()

// ¿Quién es?
auth.getUser()

// ¿Cuánto hay en carrito?
cart.getTotalPrice()

// ¿Qué página estoy?
router.getCurrentPage()

// Ir a inicio
app.router.navigate('/')
```

---

## 📁 Archivos Importantes

- `index.html` - Página principal
- `css/style.css` - Estilos
- `js/app.js` - Lógica principal
- `js/api.js` - Conexión backend

---

## ✅ Checklist

- [ ] Backend corriendo
- [ ] Abre http://127.0.0.1:8000
- [ ] Se ve página
- [ ] Puedo registrarme
- [ ] Puedo ver productos
- [ ] Puedo agregar al carrito
- [ ] Puedo ver carrito
- [ ] Puedo hacer checkout
- [ ] Orden se crea
- [ ] Veo en "Mis Órdenes"

Si todo ✅ → **¡FUNCIONA!** 🎉

---

## 🔗 Enlaces Útiles

| Link | Uso |
|------|-----|
| `http://127.0.0.1:8000` | Tienda |
| `http://127.0.0.1:8000/#/login` | Login |
| `http://127.0.0.1:8000/#/register` | Registro |
| `http://127.0.0.1:8000/#/carrito` | Carrito |
| `http://127.0.0.1:8000/#/mis-ordenes` | Órdenes |
| `http://127.0.0.1:8000/#/admin` | Admin Panel |

---

## 📞 Soporte Rápido

**Problema**: Conexión API fallando
**Solución**: 
```bash
# Backend
cd mi-backend
php artisan serve

# Verificar: http://127.0.0.1:8000/api/productos
# Debería devolver JSON
```

**Problema**: Página en blanco
**Solución**:
```bash
# Verificar archivos están en public/
ls public/index.html
ls public/js/app.js
ls public/css/style.css
```

**Problema**: Carrito no persiste
**Solución**:
```javascript
// DevTools > Console
localStorage.clear()  // Limpiar
// Recargar página
```

**Problema**: Error "Only admin can view"
**Solución**: Login como usuario admin, no cliente normal

---

## 🎯 Casos de Uso Rápidos

### Caso 1: Cliente Compra
```
1. Registrarse
2. Ver producto
3. Agregar carrito
4. Checkout
5. Orden lista
```

### Caso 2: Admin Crea Producto
```
1. Login admin
2. Panel Admin
3. Nuevo Producto
4. Llenar datos
5. Guardar
6. Aparece en tienda
```

### Caso 3: Editar Producto
```
1. Panel Admin
2. Click "Editar"
3. Cambiar datos
4. Guardar
5. Cambios reflejados
```

---

## 💡 Tips

- Usar **Incógnito** para limpiar caché
- Usar **DevTools** para debugging
- Ver **Network** si API falla
- Ver **Console** si hay errores JS
- **Recargar** si algo no funciona
- **localStorage.clear()** si hay datos viejos

---

## ⏱️ Tiempos Esperados

| Acción | Tiempo |
|--------|--------|
| Cargar página | < 2s |
| Cargar productos | < 1s |
| Agregar carrito | < 500ms |
| Crear orden | < 1s |
| Login | < 1s |

---

**¡Listo para comenzar! 🚀**

*Para ayuda completa, ver README.md y TESTING.md*
