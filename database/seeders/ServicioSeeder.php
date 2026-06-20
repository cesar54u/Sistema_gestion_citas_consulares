<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre_producto' => 'Pasaporte',
                'tipo'            => 'Documentación',
                'duracion'        => 30,
                'precio'          => 50.00,
                'descripcion'     => 'Tramitación y renovación de pasaportes españoles para ciudadanos residentes en Venezuela.',
                'estado'          => true,
            ],
            [
                'nombre_producto' => 'Registro Civil',
                'tipo'            => 'Registro',
                'duracion'        => 20,
                'precio'          => 25.00,
                'descripcion'     => 'Inscripción de nacimientos, matrimonios, defunciones y otros actos civiles en el registro consular.',
                'estado'          => true,
            ],
            [
                'nombre_producto' => 'LMD (Ley de Memoria Democrática)',
                'tipo'            => 'Ciudadanía',
                'duracion'        => 45,
                'precio'          => 0.00,
                'descripcion'     => 'Solicitud de nacionalidad española conforme a la Ley de Memoria Democrática.',
                'estado'          => true,
            ],
            [
                'nombre_producto' => 'Fe de Vida',
                'tipo'            => 'Certificación',
                'duracion'        => 15,
                'precio'          => 10.00,
                'descripcion'     => 'Certificado que acredita que el solicitante se encuentra con vida, requerido para trámites de pensiones.',
                'estado'          => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
