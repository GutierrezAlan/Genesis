<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'nullable|string|max:100',
            'semantic_context' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'semantic_context' => $request->semantic_context,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar rol por defecto
        $user->assignRole('user');

        // Crear token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Obtener permisos del usuario
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario registrado exitosamente',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $permissions,
                ],
                'token' => $token,
            ]
        ], 201);
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ], 401);
        }

        // Determinar el nombre del token según "remember"
        $tokenName = $request->remember ? 'auth_token_remember' : 'auth_token';
        
        // Crear token con expiración según "remember"
        if ($request->remember) {
            // Token con duración extendida (30 días)
            $token = $user->createToken($tokenName, ['*'], now()->addDays(30))->plainTextToken;
        } else {
            // Token con duración estándar (24 horas)
            $token = $user->createToken($tokenName, ['*'], now()->addDay())->plainTextToken;
        }

        // Obtener permisos del usuario usando Spatie
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $roles = $user->getRoleNames()->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'Autenticación exitosa',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'permissions' => $permissions,
                ],
                'token' => $token,
                'remember' => $request->remember ?? false,
            ]
        ]);
    }

    /**
     * Logout de usuario
     */
    public function logout(Request $request)
    {
        // Eliminar el token actual
        $token = $request->user()->currentAccessToken();
        $token?->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function actual(Request $request)
    {
        $user = $request->user();
        
        // Obtener permisos usando Spatie
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $roles = $user->getRoleNames()->toArray();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'permissions' => $permissions,
                ],
            ]
        ]);
    }
}