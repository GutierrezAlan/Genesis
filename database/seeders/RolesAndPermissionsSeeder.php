<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        Permission::create(['name' => 'access-admin-panel']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'edit-content']);
        
        // Permisos de productos
        Permission::create(['name' => 'create-product']);
        Permission::create(['name' => 'edit-product']);
        Permission::create(['name' => 'delete-product']);
        
        // Permisos de órdenes
        Permission::create(['name' => 'approve-order']);

        // Crear roles
        $roleUser = Role::create(['name' => 'user']);
        $roleUser->givePermissionTo(['edit-content']);

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}