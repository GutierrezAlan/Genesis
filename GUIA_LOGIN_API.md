# 🔐 Guía de Implementación - Login API (Backend Laravel)

## ✅ Estado Actual

Tu API de autenticación está completamente lista para usar. Los cambios realizados incluyen:

### 1. **Modelo User actualizado** (`app/Models/User.php`)
- ✅ Agregados campos: `first_name`, `last_name`, `mobile`, `semantic_context`
- ✅ Traits configurados: `HasApiTokens`, `HasRoles`, `Notifiable`, `HasFactory`
- ✅ Casteos correctos: `email_verified_at`, `password`

### 2. **Configuración de Autenticación** (`config/auth.php`)
- ✅ Guard `sanctum` configurado y listo para usar
- ✅ Provider de usuarios correctamente configurado

### 3. **Controlador de API** (`app/Http/Controllers/Api/AuthController.php`)
Ya estructurado con:
- ✅ `POST /api/register` - Registro de nuevos usuarios
- ✅ `POST /api/login` - Login de usuarios (con token Sanctum)
- ✅ `POST /api/logout` - Logout (requiere token)
- ✅ `GET /api/me` - Obtener usuario actual (requiere token)

### 4. **Rutas API** (`routes/api.php`)
- ✅ Rutas públicas: `/register`, `/login`
- ✅ Rutas protegidas: `/logout`, `/me` (con middleware `auth:sanctum`)
- ✅ CORS configurado para aceptar requests desde el frontend

---

## 🚀 Cómo Usar

### **Endpoints de Autenticación**

#### 1️⃣ Registro de Usuario
```
POST /api/register
Content-Type: application/json

{
  "name": "Juan Pérez Gómez",
  "first_name": "Juan",
  "last_name": "Pérez",
  "email": "juan@example.com",
  "mobile": "+34 123 456 789",
  "semantic_context": "Cliente potencial",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta exitosa (201):**
```json
{
  "status": "success",
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez Gómez",
      "email": "juan@example.com",
      "roles": ["user"],
      "permissions": []
    },
    "token": "1|ABC123XYZ..."
  }
}
```

---

#### 2️⃣ Login de Usuario
```
POST /api/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123",
  "remember": false
}
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Autenticación exitosa",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez Gómez",
      "email": "juan@example.com",
      "roles": ["user"],
      "permissions": []
    },
    "token": "1|ABC123XYZ...",
    "remember": false
  }
}
```

**Respuesta error (422):**
```json
{
  "message": "Las credenciales proporcionadas son incorrectas.",
  "errors": {
    "email": ["Las credenciales proporcionadas son incorrectas."]
  }
}
```

---

#### 3️⃣ Obtener Usuario Actual (Protegido)
```
GET /api/me
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez Gómez",
      "email": "juan@example.com",
      "roles": ["user"],
      "permissions": []
    }
  }
}
```

---

#### 4️⃣ Logout (Protegido)
```
POST /api/logout
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "status": "success",
  "message": "Sesión cerrada exitosamente"
}
```

---

## 📋 Campos del Usuario

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `id` | integer | Sí | ID único del usuario |
| `name` | string | Sí | Nombre completo |
| `first_name` | string | Sí | Primer nombre |
| `last_name` | string | Sí | Apellido |
| `email` | string | Sí | Email único |
| `mobile` | string | No | Teléfono |
| `semantic_context` | string | No | Contexto semántico (ex: tipo de cliente) |
| `password` | string | Sí | Contraseña (mínimo 8 caracteres) |

---

## 🔄 Duración de Tokens

**Sin "remember":**
- Duración: 24 horas
- Token: `auth_token`

**Con "remember=true":**
- Duración: 30 días
- Token: `auth_token_remember`

---

## 🛡️ Seguridad Configurada

✅ Contraseñas hasheadas con bcrypt
✅ Tokens Sanctum con expiración
✅ CORS habilitado para solicitudes del frontend
✅ Validación de entrada en todos los endpoints
✅ Autenticación requerida para rutas sensibles

---

## 📝 Notas Importantes

1. **Base de datos:** Asegúrate de que la tabla `users` esté creada. Si no, ejecuta:
   ```bash
   php artisan migrate
   ```

2. **Spatie Permissions:** Asegúrate de que esté instalado:
   ```bash
   composer require spatie/laravel-permission
   php artisan migrate
   ```

3. **Sanctum:** Debe estar instalado (es parte de Laravel 11 por defecto)

4. **CORS:** Está configurado para aceptar requests desde cualquier origen en desarrollo.
   Para producción, actualiza `config/cors.php`:
   ```php
   'allowed_origins' => ['https://tu-frontend.com'],
   ```

---

## 🧪 Pruebas Rápidas

### Con Postman/cURL:

**1. Registrar usuario:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Test",
    "first_name": "Juan",
    "last_name": "Test",
    "email": "juan@test.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**2. Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@test.com",
    "password": "password123"
  }'
```

**3. Obtener usuario actual (con el token recibido):**
```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer {TOKEN_AQUI}"
```

---

## ❓ Troubleshooting

**Error: "CORS policy"**
- Verifica que el frontend está en la whitelist de CORS
- Comprueba que el servidor Laravel está ejecutándose

**Error: "Unauthorized" (401)**
- El token ha expirado o es inválido
- Verifica que la cabecera Authorization sea correcta

**Error: "Contraseña incorrecta"**
- Asegúrate de usar la contraseña correcta
- Verifica que el usuario existe en la BD

---

**¡Tu API está lista para ser consumida por el frontend React! 🎉**
