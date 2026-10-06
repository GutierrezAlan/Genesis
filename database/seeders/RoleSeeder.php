<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Resetear caché de roles y permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Panel admin
            'access-admin-panel',
            // Productos
            'create-product',
            'edit-product',
            'delete-product',
            'view-products',
            // Órdenes
            'view-all-orders',
            'approve-order',
            'cancel-order',
            'view-own-orders',
            // Otros
            'manage-users',
            'edit-content',
            'delete-content',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // Rol: user (usuario básico)
        $roleUser = Role::firstOrCreate(['name' => 'user']);
        $roleUser->syncPermissions([
            'view-products',
            'view-own-orders',
        ]);

        // Rol: admin
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());
    }
}