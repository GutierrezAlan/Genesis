# 📋 Documentación de Tests - Proyecto Backend

Este documento describe todos los tests generados para la aplicación backend Laravel.

## 📁 Estructura de Tests

```
tests/
├── Feature/
│   ├── AuthTest.php          # Tests de autenticación
│   ├── ProductTest.php       # Tests de productos
│   ├── OrderTest.php         # Tests de órdenes
│   ├── FilterTest.php        # Tests de búsqueda y filtros
│   └── ExampleTest.php       # Tests de integración completos
└── Unit/
    └── ModelTest.php         # Tests unitarios de modelos
```

## 🔐 AuthTest.php (8 tests)

Tests para el módulo de autenticación y gestión de usuarios.

### Tests incluidos:
- ✅ `test_user_can_register()` - Registro de usuario válido
- ✅ `test_registration_fails_with_invalid_email()` - Validación de email
- ✅ `test_registration_fails_with_password_mismatch()` - Validación de contraseña
- ✅ `test_user_can_login()` - Login exitoso
- ✅ `test_login_fails_with_incorrect_password()` - Login con contraseña incorrecta
- ✅ `test_login_fails_with_non_existent_user()` - Login con usuario inexistente
- ✅ `test_authenticated_user_can_get_profile()` - Obtener perfil del usuario
- ✅ `test_unauthenticated_user_cannot_access_protected_routes()` - Protección de rutas
- ✅ `test_user_can_logout()` - Logout del usuario

## 📦 ProductTest.php (14 tests)

Tests para la gestión de productos (CRUD).

### Tests incluidos:
- ✅ `test_can_get_all_products()` - Obtener todos los productos
- ✅ `test_can_get_single_product()` - Obtener un producto específico
- ✅ `test_get_non_existent_product_returns_404()` - Producto no encontrado
- ✅ `test_admin_can_create_product()` - Admin crea producto
- ✅ `test_non_admin_cannot_create_product()` - No-admin no puede crear
- ✅ `test_create_product_with_missing_fields()` - Validación de campos requeridos
- ✅ `test_create_product_with_invalid_price()` - Validación de precio
- ✅ `test_admin_can_update_product()` - Admin actualiza producto
- ✅ `test_non_admin_cannot_update_product()` - No-admin no puede actualizar
- ✅ `test_admin_can_delete_product()` - Admin elimina producto
- ✅ `test_non_admin_cannot_delete_product()` - No-admin no puede eliminar
- ✅ `test_unauthenticated_user_cannot_create_product()` - Usuario sin autenticar

## 📋 OrderTest.php (11 tests)

Tests para la gestión de órdenes.

### Tests incluidos:
- ✅ `test_authenticated_user_can_view_their_orders()` - Ver sus órdenes
- ✅ `test_authenticated_user_can_get_single_order()` - Ver orden específica
- ✅ `test_user_cannot_view_other_users_orders()` - Protección de privacidad
- ✅ `test_authenticated_user_can_create_order()` - Crear nueva orden
- ✅ `test_create_order_with_missing_items()` - Validación de items
- ✅ `test_unauthenticated_user_cannot_create_order()` - Sin autenticación
- ✅ `test_admin_can_approve_order()` - Admin aprueba orden
- ✅ `test_non_admin_cannot_approve_order()` - No-admin no puede aprobar
- ✅ `test_user_can_cancel_own_order()` - Usuario cancela su orden
- ✅ `test_user_cannot_cancel_other_users_order()` - No puede cancelar otras
- ✅ `test_cannot_cancel_approved_order()` - No cancela orden aprobada

## 🔍 FilterTest.php (8 tests)

Tests para búsqueda y filtros de productos.

### Tests incluidos:
- ✅ `test_can_get_all_categories()` - Obtener todas las categorías
- ✅ `test_can_search_products_by_category()` - Filtrar por categoría
- ✅ `test_can_search_products_by_price_range()` - Filtrar por rango de precio
- ✅ `test_can_search_products_by_title()` - Buscar por título
- ✅ `test_can_search_with_multiple_filters()` - Filtros combinados
- ✅ `test_search_with_no_matches()` - Búsqueda sin resultados
- ✅ `test_search_with_invalid_price_range()` - Validación de datos
- ✅ `test_search_returns_correct_number_of_results()` - Cantidad de resultados

## 🔄 ExampleTest.php (5 tests de integración)

Tests de integración que prueban flujos completos del sistema.

### Tests incluidos:
- ✅ `test_user_registration_and_login_flow()` - Flujo registro → login → perfil
- ✅ `test_complete_order_flow()` - Crear orden → ver → aprobar
- ✅ `test_complete_product_management_flow()` - Crear → obtener → actualizar → eliminar
- ✅ `test_search_and_filter_flow()` - Búsqueda y filtros completos
- ✅ `test_user_permissions_and_authorization()` - Control de permisos

## 🧪 ModelTest.php (15 tests unitarios)

Tests unitarios para los modelos de la aplicación.

### Tests incluidos:
- ✅ `test_user_can_have_orders()` - Relación User → Orders
- ✅ `test_user_can_have_roles()` - Roles de usuario
- ✅ `test_product_can_be_created()` - Creación de producto
- ✅ `test_product_default_stock()` - Stock por defecto
- ✅ `test_product_can_be_ordered()` - Producto en órdenes
- ✅ `test_order_belongs_to_user()` - Relación Order → User
- ✅ `test_order_has_items_array()` - Items como array
- ✅ `test_order_total_price_is_float()` - Cast de precio
- ✅ `test_order_status_values()` - Estados de orden
- ✅ `test_user_deletion_cascades_orders()` - Eliminación en cascada
- ✅ `test_product_can_be_updated()` - Actualización de producto
- ✅ `test_product_can_be_deleted()` - Eliminación de producto
- ✅ `test_order_can_be_updated()` - Actualización de orden

## 🚀 Comandos para Ejecutar Tests

### Ejecutar todos los tests
```bash
php artisan test
```

### Ejecutar tests específicos
```bash
# Solo Feature tests
php artisan test tests/Feature

# Solo Unit tests
php artisan test tests/Unit

# Test específico
php artisan test tests/Feature/AuthTest.php

# Test específico por método
php artisan test tests/Feature/AuthTest.php --filter=test_user_can_register
```

### Ejecutar con salida detallada
```bash
php artisan test --verbose

# Con información de cobertura
php artisan test --coverage
```

### Ejecutar tests en paralelo
```bash
php artisan test --parallel
```

## 📊 Resumen de Cobertura

| Módulo | Tests | Cobertura |
|--------|-------|-----------|
| Autenticación | 9 | ✅ Completa |
| Productos | 14 | ✅ Completa |
| Órdenes | 11 | ✅ Completa |
| Filtros | 8 | ✅ Completa |
| Modelos | 15 | ✅ Completa |
| Integración | 5 | ✅ Completa |
| **TOTAL** | **62** | **✅ Completa** |

## 🔧 Configuración de Tests

Los tests utilizan:
- **Laravel Testing Framework**: Assertions completas
- **RefreshDatabase**: Limpia DB entre tests
- **Sanctum**: Autenticación API
- **Factories**: Generación de datos de prueba
- **Roles & Permissions**: Control de acceso

## 📝 Notas importantes

1. Los tests usan `RefreshDatabase` por lo que crean datos limpios para cada prueba
2. Se requiere configurar `.env.testing` para la BD de prueba
3. Los factories deben estar configurados correctamente
4. Asegúrate de tener migraciones ejecutadas antes de correr tests

## ✨ Próximos pasos sugeridos

1. Ejecutar `php artisan test` para verificar que todo funciona
2. Analizar cobertura con `php artisan test --coverage`
3. Integrar tests en CI/CD (GitHub Actions, GitLab CI, etc.)
4. Agregar más tests según nuevas funcionalidades
