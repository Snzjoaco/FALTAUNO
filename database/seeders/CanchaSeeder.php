<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;

class CanchaSeeder extends Seeder
{
    public function run()
    {
        $canchas = [
            [
                'propietario_id' => 1, // Asumiendo que el usuario test es el propietario
                'nombre' => 'F5 Arena Palermo',
                'direccion' => 'Av. Santa Fe 3250, Palermo, CABA',
                'ciudad' => 'Buenos Aires',
                'barrio' => 'Palermo',
                'latitude' => -34.5889,
                'longitude' => -58.4056,
                'precio_base' => 20.00,
                'tipo_superficie' => 'cesped_sintetico',
                'tipo_cancha' => 'f5',
                'capacidad_maxima' => 10,
                'tiene_vestuarios' => true,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 5 con césped sintético de última generación. Vestuarios y estacionamiento incluidos.',
                'telefono' => '+54 11 1234-5678',
                'horarios_semana' => json_encode(['08:00', '24:00']),
                'horarios_fin_semana' => json_encode(['08:00', '24:00'])
            ],
            [
                'propietario_id' => 1,
                'nombre' => 'Parque Norte 7',
                'direccion' => 'Av. Costanera Norte 2000, Nuñez, CABA',
                'ciudad' => 'Buenos Aires',
                'barrio' => 'Nuñez',
                'latitude' => -34.5600,
                'longitude' => -58.4500,
                'precio_base' => 18.00,
                'tipo_superficie' => 'cesped_natural',
                'tipo_cancha' => 'f7',
                'capacidad_maxima' => 14,
                'tiene_vestuarios' => false,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 7 con césped natural. Ideal para partidos recreativos.',
                'telefono' => '+54 11 2345-6789',
                'horarios_semana' => json_encode(['07:00', '23:00']),
                'horarios_fin_semana' => json_encode(['07:00', '23:00'])
            ],
            [
                'propietario_id' => 1,
                'nombre' => 'Villa Urquiza Club',
                'direccion' => 'Av. Triunvirato 4500, Villa Urquiza, CABA',
                'ciudad' => 'Buenos Aires',
                'barrio' => 'Villa Urquiza',
                'latitude' => -34.5800,
                'longitude' => -58.4800,
                'precio_base' => 22.00,
                'tipo_superficie' => 'cesped_sintetico',
                'tipo_cancha' => 'f11',
                'capacidad_maxima' => 22,
                'tiene_vestuarios' => true,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 11 profesional con césped sintético. Vestuarios, duchas y estacionamiento.',
                'telefono' => '+54 11 3456-7890',
                'horarios_semana' => json_encode(['06:00', '24:00']),
                'horarios_fin_semana' => json_encode(['06:00', '24:00'])
            ]
        ];

        foreach ($canchas as $cancha) {
            Cancha::create($cancha);
        }
    }
}
