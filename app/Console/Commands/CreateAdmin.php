<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Creando usuario administrador...');

        try {
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

            // Asignar rol admin
            if (!$admin->hasRole('admin')) {
                $admin->assignRole('admin');
            }

            $this->info('✅ ¡Usuario administrador creado/actualizado exitosamente!');
            $this->newLine();
            $this->info('📧 Email: admin@genesis.com');
            $this->info('🔑 Contraseña: admin123');
            $this->newLine();
            $this->warn('⚠️  Por seguridad, cambia estas credenciales después del primer login.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
