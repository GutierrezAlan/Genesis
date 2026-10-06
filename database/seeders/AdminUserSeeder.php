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
        $email = env('ADMIN_EMAIL', app()->isProduction() ? null : 'admin@genesis.com');
        $password = env('ADMIN_PASSWORD', app()->isProduction() ? null : 'admin123');

        if (blank($email) || blank($password)) {
            throw new \RuntimeException('Define ADMIN_EMAIL y ADMIN_PASSWORD antes de crear el administrador.');
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrador',
                'first_name' => 'Admin',
                'last_name' => 'Genesis',
                'password' => Hash::make($password),
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
        
        $this->command->line('   Email: '.$email);
    }
}
