<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/buscar', [CategoryController::class, 'search']);

// Route::get('/productos/buscar', [ProductController::class, 'buscar']);
Route::resource('productos', ProductController::class)->only(['index', 'show', 'search']);


// Rutas protegidas con autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'actual']); // Para obtener el usuario actual 

    // Debug - Verificar permisos del usuario (eliminar después)
    Route::get('/debug/permissions', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'user_email' => $user->email,
            'roles' => $user->getRoleNames()->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'can_create_product' => $user->can('create-product'),
        ]);
    });

    // Perfil de usuario
    Route::get('/user/profile', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Perfil de usuario',
            'data' => [
                'profile' => [
                    'bio' => 'Usuario activo del sistema',
                    'posts_count' => 15,
                    'followers' => 42,
                ]
            ]
        ]);
    });

    // Órdenes
    Route::resource('ordenes', OrderController::class)->only(['index', 'store', 'show']);

    // Solo Admin
    Route::middleware(['role:admin'])->group(function () {
        // Productos - Solo admin puede crear, editar y eliminar
        Route::apiResource('productos', ProductController::class)->only(['store', 'update', 'destroy']);
        
        // Órdenes - Solo admin puede aprobar/cancelar/eliminar 
        Route::put('/ordenes/{order}/status', [OrderController::class, 'updateOrderStatus']);

        // Admin Dashboard
        Route::get('/admin/dashboard', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'Bienvenido al panel de administración',
                'data' => [
                    'stats' => [
                        'users' => 150,
                        'posts' => 320,
                        'comments' => 1240,
                    ]
                ]
            ]);
        });
    });
});

