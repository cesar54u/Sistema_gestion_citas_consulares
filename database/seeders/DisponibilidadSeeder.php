<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disponibilidad;

class DisponibilidadSeeder extends Seeder
{
    public function run(): void
    {
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        foreach ($dias as $dia) {
            Disponibilidad::create([
                'servicio_id' => null, // Aplica a todos los servicios
                'dia_semana'  => $dia,
                'hora_inicio' => '08:00:00',
                'hora_fin'    => '12:00:00',
                'max_citas'   => 8,
                'activo'      => true,
            ]);

            Disponibilidad::create([
                'servicio_id' => null,
                'dia_semana'  => $dia,
                'hora_inicio' => '13:00:00',
                'hora_fin'    => '17:00:00',
                'max_citas'   => 8,
                'activo'      => true,
            ]);
        }
    }
}
