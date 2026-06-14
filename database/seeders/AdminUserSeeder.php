<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@genesis.com'],
            [
                'name' => 'Administrador',
                'first_name' => 'Admin',
                'last_name' => 'Genesis',
                'email' => 'admin@genesis.com',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asignar rol admin solo si no lo tiene
        try {
            if (!$admin->hasRole('admin')) {
                $admin->assignRole('admin');
            }
            $this->command->info('✅ Usuario Admin creado/actualizado con rol admin');
        } catch (\Exception $e) {
            $this->command->warn('⚠️  Usuario admin creado pero sin rol asignado (rol no existe)');
        }
        
        $this->command->line('   Email: admin@genesis.com');
        $this->command->line('   Contraseña: admin123');
    }
}
