<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;

class CanchaSeeder extends Seeder
{
    public function run()
    {
        Cancha::query()->delete();

        $canchas = [
            [
                'propietario_id' => 1,
                'nombre' => 'Complejo La Candelaria',
                'direccion' => 'Avenida Rivadavia 1910, Río Cuarto, Córdoba',
                'ciudad' => 'Río Cuarto',
                'barrio' => 'Centro',
                'latitude' => -33.1236,
                'longitude' => -64.3496,
                'precio_base' => 18.00,
                'tipo_superficie' => 'cesped_sintetico',
                'tipo_cancha' => 'f5',
                'capacidad_maxima' => 10,
                'tiene_vestuarios' => true,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 5 con césped sintético, vestuarios y estacionamiento para partidos amateurs y recreativos.',
                'telefono' => '+54 358 412-3456',
                'horarios_semana' => json_encode(['08:00', '23:00']),
                'horarios_fin_semana' => json_encode(['08:00', '23:00'])
            ],
            [
                'propietario_id' => 1,
                'nombre' => 'Club Deportivo Río',
                'direccion' => 'Ruta 36 km 418, Río Cuarto, Córdoba',
                'ciudad' => 'Río Cuarto',
                'barrio' => 'Sur',
                'latitude' => -33.1337,
                'longitude' => -64.3511,
                'precio_base' => 20.00,
                'tipo_superficie' => 'cesped_natural',
                'tipo_cancha' => 'f7',
                'capacidad_maxima' => 14,
                'tiene_vestuarios' => true,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 7 con césped natural, ideal para partidos semanales y entrenamientos.',
                'telefono' => '+54 358 423-6789',
                'horarios_semana' => json_encode(['07:00', '22:00']),
                'horarios_fin_semana' => json_encode(['07:00', '22:00'])
            ],
            [
                'propietario_id' => 1,
                'nombre' => 'Cancha Norte 7',
                'direccion' => 'Calle San Martín 2450, Río Cuarto, Córdoba',
                'ciudad' => 'Río Cuarto',
                'barrio' => 'Norte',
                'latitude' => -33.1172,
                'longitude' => -64.3648,
                'precio_base' => 22.00,
                'tipo_superficie' => 'cesped_sintetico',
                'tipo_cancha' => 'f11',
                'capacidad_maxima' => 22,
                'tiene_vestuarios' => true,
                'tiene_estacionamiento' => true,
                'tiene_iluminacion' => true,
                'activa' => true,
                'verificada' => true,
                'descripcion' => 'Cancha de fútbol 11 con iluminación, vestuarios y espacio para partidos de mayor intensidad.',
                'telefono' => '+54 358 434-7890',
                'horarios_semana' => json_encode(['06:00', '23:00']),
                'horarios_fin_semana' => json_encode(['06:00', '23:00'])
            ]
        ];

        foreach ($canchas as $cancha) {
            Cancha::create($cancha);
        }
    }
}
