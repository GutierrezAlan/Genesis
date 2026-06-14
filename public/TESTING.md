# 🧪 Guía de Testing - Genesis Ecommerce

## ✅ Checklist de Testing Funcional

### 1️⃣ Autenticación

- [ ] **Página de Login**
  - [ ] Formulario visible y accesible
  - [ ] Validación de campos vacíos
  - [ ] Error correcto con credenciales inválidas
  - [ ] Login exitoso redirige a inicio
  - [ ] Token se guarda en localStorage

- [ ] **Página de Registro**
  - [ ] Formulario carga correctamente
  - [ ] Validación de contraseñas coincidentes
  - [ ] Email válido requerido
  - [ ] Registro exitoso crea usuario
  - [ ] Auto-login después de registrarse

- [ ] **Recuérdame**
  - [ ] Checkbox funciona
  - [ ] Token persiste después de cerrar navegador

- [ ] **Sesión**
  - [ ] Usuario aparece en navbar
  - [ ] Logout limpia sesión
  - [ ] Redirección a login después de logout

---

### 2️⃣ Navegación

- [ ] **Navbar Desktop**
  - [ ] Logo lleva a inicio
  - [ ] Links funcionan correctamente
  - [ ] Usuario autenticado ve sus opciones
  - [ ] Admin ve "Panel Admin"

- [ ] **Navbar Mobile**
  - [ ] Hamburguesa se abre/cierra
  - [ ] Links funcionan en menú móvil
  - [ ] Carrito muestra cantidad correcta

- [ ] **Footer**
  - [ ] Visible en todas las páginas
  - [ ] Copyright se muestra correctamente

- [ ] **Rutas**
  - [ ] `/` → Página de inicio
  - [ ] `/item/1` → Detalle de producto
  - [ ] `/carrito` → Carrito
  - [ ] `/checkout` → Checkout
  - [ ] `/mis-ordenes` → Mis órdenes
  - [ ] `/login` → Iniciar sesión
  - [ ] `/register` → Registrarse
  - [ ] `/admin` → Panel admin (solo admin)

---

### 3️⃣ Productos

- [ ] **Listado**
  - [ ] Se cargan todos los productos
  - [ ] Se muestran correctamente en grid responsive
  - [ ] Precios se formatean con $ correctamente
  - [ ] Stock se muestra correctamente
  - [ ] Botón "Agregar" funciona

- [ ] **Detalle**
  - [ ] Página carga sin errores
  - [ ] Muestra información completa del producto
  - [ ] Cantidad se puede seleccionar (+ / -)
  - [ ] Imágenes se cargan (si existen)
  - [ ] Botón "Volver" funciona
  - [ ] No disponible si stock = 0

- [ ] **Búsqueda**
  - [ ] Campo de búsqueda funciona (si implementado)
  - [ ] Filtra productos correctamente

---

### 4️⃣ Carrito

- [ ] **Agregar Productos**
  - [ ] Se agrega desde listado
  - [ ] Se agrega desde detalle
  - [ ] Cantidad se suma si producto ya existe
  - [ ] Notificación de éxito aparece
  - [ ] Contador en navbar se actualiza

- [ ] **Ver Carrito**
  - [ ] Lista items correctamente
  - [ ] Muestra precios unitarios
  - [ ] Muestra cantidades
  - [ ] Calcula total correcto
  - [ ] Puede aumentar/disminuir cantidades

- [ ] **Operaciones**
  - [ ] Remover item funciona
  - [ ] Vaciar carrito limpia todo
  - [ ] Total se recalcula automáticamente
  - [ ] Cambiar cantidad actualiza precio total

- [ ] **Persistencia**
  - [ ] Carrito se mantiene al recargar
  - [ ] Carrito se mantiene por usuario
  - [ ] Carrito se limpia al logout

- [ ] **Carrito Vacío**
  - [ ] Mensaje "Carrito vacío" si no hay items
  - [ ] Botón para volver a comprar

---

### 5️⃣ Checkout

- [ ] **Acceso**
  - [ ] Solo usuarios autenticados pueden acceder
  - [ ] Redirección a login si no está autenticado
  - [ ] Redirección a carrito si está vacío

- [ ] **Resumen**
  - [ ] Lista todos los items
  - [ ] Muestra total correcto
  - [ ] Información del usuario visible
  - [ ] Nombre del usuario correcto

- [ ] **Procesamiento**
  - [ ] Botón "Confirmar Orden" funciona
  - [ ] Orden se crea en backend
  - [ ] Carrito se limpia después
  - [ ] Redirige a "Mis Órdenes"
  - [ ] Notificación de éxito aparece

- [ ] **Errores**
  - [ ] Error se maneja correctamente
  - [ ] Mensajes descriptivos aparecen

---

### 6️⃣ Órdenes

- [ ] **Acceso**
  - [ ] Solo autenticados pueden ver
  - [ ] Se redirecciona a login si no está autenticado

- [ ] **Listado**
  - [ ] Se cargan todas las órdenes
  - [ ] Se muestran correctamente

- [ ] **Card de Orden**
  - [ ] ID de orden visible
  - [ ] Estado visible con color correcto
  - [ ] Items listados con cantidades
  - [ ] Total correcto
  - [ ] Fecha formateada correctamente

- [ ] **Sin Órdenes**
  - [ ] Mensaje si usuario no tiene órdenes

---

### 7️⃣ Panel Admin

- [ ] **Acceso**
  - [ ] Solo admin puede ver
  - [ ] Redirección si no es admin
  - [ ] Navbar muestra 👑 para admin

- [ ] **Tabla de Productos**
  - [ ] Se cargan todos los productos
  - [ ] Columnas correctas
  - [ ] Botones Editar/Eliminar visibles

- [ ] **Crear Producto**
  - [ ] Formulario carga
  - [ ] Todos los campos requeridos
  - [ ] Validaciones funcionan
  - [ ] Producto se crea en backend

- [ ] **Editar Producto**
  - [ ] Formulario precargado
  - [ ] Cambios se guardan
  - [ ] Tabla se actualiza

- [ ] **Eliminar Producto**
  - [ ] Confirmación aparece
  - [ ] Producto se elimina del backend
  - [ ] Tabla se actualiza

---

### 8️⃣ Responsive

- [ ] **Desktop (> 1200px)**
  - [ ] Layout correcto
  - [ ] Grid de productos a 4+ columnas
  - [ ] Navbar completo visible

- [ ] **Tablet (768px - 1200px)**
  - [ ] Layout adaptado
  - [ ] Grid de productos a 2-3 columnas
  - [ ] Menú sigue visible

- [ ] **Móvil (< 768px)**
  - [ ] Hamburguesa visible
  - [ ] Grid 1-2 columnas
  - [ ] Texto legible
  - [ ] Botones accesibles
  - [ ] Menú funciona

- [ ] **Extra pequeño (< 480px)**
  - [ ] Funciona correctamente
  - [ ] Sin overflow horizontal
  - [ ] Elementos accesibles

---

### 9️⃣ Notificaciones

- [ ] **Éxito**
  - [ ] Aparecen en verde
  - [ ] Se ocultan automáticamente

- [ ] **Errores**
  - [ ] Aparecen en rojo
  - [ ] Mensajes descriptivos

- [ ] **Advertencias**
  - [ ] Aparecen en amarillo
  - [ ] Contexto claro

---

### 🔟 Performance

- [ ] **Carga**
  - [ ] Página inicial carga en < 2s
  - [ ] Sin errores en consola

- [ ] **Interacción**
  - [ ] Clicks responden inmediatamente
  - [ ] Formularios no se "cuelgan"
  - [ ] API requests se ven en Network

- [ ] **Almacenamiento**
  - [ ] localStorage no sobrecargado
  - [ ] Datos persisten correctamente

---

## 🔍 Testing Manual

### Caso 1: Cliente Nuevo

```
1. Visitar http://localhost:8000
2. Click "Registrarse"
3. Llenar formulario
4. Debe redirigirse a inicio autenticado
5. Carrito debe estar vacío
6. Debe poder ver "Mis Órdenes"
```

### Caso 2: Comprar Producto

```
1. Navegar a inicio
2. Click en un producto
3. Aumentar cantidad a 3
4. Click "Agregar al Carrito"
5. Click en carrito (navbar)
6. Verificar items y total
7. Click "Proceder al Checkout"
8. Verificar resumen
9. Click "Confirmar Orden"
10. Debe redirigirse a "Mis Órdenes"
11. Nueva orden debe aparecer
```

### Caso 3: Panel Admin

```
1. Login como admin
2. Click "Panel Admin" (con 👑)
3. Ver tabla de productos
4. Click "Nuevo Producto"
5. Llenar datos
6. Guardar
7. Debe aparecer en tabla
8. Click "Editar"
9. Cambiar datos
10. Guardar
11. Cambios deben verse
12. Click "Eliminar"
13. Confirmar
14. Producto debe desaparecer
```

### Caso 4: Responsive

```
Abrir DevTools (F12)
- Presionar Ctrl+Shift+M (Toggle Device Toolbar)
- Probar en: 320px, 480px, 768px, 1024px, 1920px
- Verificar que todo se adapte correctamente
```

---

## 🐛 Errores Comunes

### "Error al conectar con API"
```
✓ Verificar que backend esté corriendo
✓ URL correcta en js/api.js
✓ CORS habilitado en backend
```

### "Carrito vacío después de logout"
```
✓ Comportamiento correcto (seguridad)
✓ Se mantiene en localStorage pero no accesible
```

### "Producto no aparece en admin"
```
✓ Verificar que sea admin
✓ Recargar página
✓ Revisar errores en console (F12)
```

### "Cantidad no cambia en carrito"
```
✓ Verificar valor numérico
✓ No puede ser 0 o negativo
✓ Recargar si error persiste
```

---

## 📊 Checklist de Deploy

- [ ] Backend corriendo
- [ ] CORS configurado
- [ ] Archivos en `public/`
- [ ] URLs correctas en API
- [ ] localStorage funciona
- [ ] No hay errores en console
- [ ] Responsive testado
- [ ] Notificaciones funcionan
- [ ] Admin puede crear productos
- [ ] Cliente puede comprar
- [ ] Órdenes se guardan

---

## 🎯 Criterios de Aceptación

✅ **DEBE**
- Cargar sin errores
- Usuarios pueden registrarse y login
- Agregar productos al carrito
- Crear órdenes
- Admin gestiona productos
- Responsive en móvil
- Notificaciones funcionan

⚠️ **DEBERÍA**
- Búsqueda de productos
- Filtros avanzados
- Recomendaciones

💡 **PODRÍA**
- Wishlist
- Reseñas
- Chat soporte

---

**Última actualización: 2024**
