<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'nombre'             => 'Administrador',
            'apellido'           => 'Consular',
            'cedula'             => 'V-00000001',
            'telefono'           => '0414-1234567',
            'correo_electronico' => 'admin@consulado.com',
            'usuario'            => 'admin',
            'password'           => Hash::make('Admin@1234'),
            'rol'                => 'admin',
        ]);

        // Usuario de prueba
        User::create([
            'nombre'             => 'Carlos',
            'apellido'           => 'Rodríguez',
            'cedula'             => 'V-12345678',
            'telefono'           => '0412-9876543',
            'correo_electronico' => 'carlos@ejemplo.com',
            'usuario'            => 'carlos123',
            'password'           => Hash::make('User@1234'),
            'rol'                => 'usuario',
        ]);
    }
}
