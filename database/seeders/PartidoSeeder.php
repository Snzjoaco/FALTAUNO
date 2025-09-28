<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partido;
use App\Models\Cancha;
use App\Models\User;
use Carbon\Carbon;

class PartidoSeeder extends Seeder
{
    public function run()
    {
        // Obtener canchas y usuarios existentes
        $canchas = Cancha::all();
        $usuarios = User::all();
        
        if ($canchas->isEmpty() || $usuarios->isEmpty()) {
            $this->command->info('No hay canchas o usuarios para crear partidos de ejemplo.');
            return;
        }
        
        // Crear usuarios adicionales
        $usuariosAdicionales = [
            [
                'name' => 'María González',
                'email' => 'maria@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'María',
                'last_name' => 'González'
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'Carlos',
                'last_name' => 'López'
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'Ana',
                'last_name' => 'Martínez'
            ],
            [
                'name' => 'Diego Rodríguez',
                'email' => 'diego@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'Diego',
                'last_name' => 'Rodríguez'
            ]
        ];
        
        foreach ($usuariosAdicionales as $usuarioData) {
            // Verificar si el usuario ya existe
            if (!User::where('email', $usuarioData['email'])->exists()) {
                User::create($usuarioData);
            }
        }
        
        // Recargar usuarios
        $usuarios = User::all();
        
        $partidos = [
            [
                'titulo' => 'Partido Casual F5 - Palermo',
                'descripcion' => 'Partido recreativo de fútbol 5, todos los niveles bienvenidos. Divertirse es lo más importante.',
                'cancha_id' => $canchas->first()->id,
                'organizador_id' => $usuarios->skip(1)->first()->id, // María González
                'fecha' => Carbon::tomorrow()->format('Y-m-d'),
                'hora_inicio' => '19:00:00',
                'hora_fin' => '21:00:00',
                'fecha_hora_inicio' => Carbon::tomorrow()->setTime(19, 0),
                'fecha_hora_fin' => Carbon::tomorrow()->setTime(21, 0),
                'nivel_juego' => 'casual',
                'jugadores_requeridos' => 10,
                'jugadores_confirmados' => 3,
                'costo_por_jugador' => 15.00,
                'costo_total' => 150.00,
                'estado' => 'publicado'
            ],
            [
                'titulo' => 'Partido Serio F7 - Nuñez',
                'descripcion' => 'Partido competitivo de fútbol 7. Se requiere experiencia y compromiso.',
                'cancha_id' => $canchas->skip(1)->first()->id ?? $canchas->first()->id,
                'organizador_id' => $usuarios->skip(2)->first()->id, // Carlos López
                'fecha' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
                'hora_inicio' => '20:00:00',
                'hora_fin' => '22:00:00',
                'fecha_hora_inicio' => Carbon::tomorrow()->addDay()->setTime(20, 0),
                'fecha_hora_fin' => Carbon::tomorrow()->addDay()->setTime(22, 0),
                'nivel_juego' => 'serio',
                'jugadores_requeridos' => 14,
                'jugadores_confirmados' => 8,
                'costo_por_jugador' => 20.00,
                'costo_total' => 280.00,
                'estado' => 'publicado'
            ],
            [
                'titulo' => 'Entrenamiento Individual - Villa Urquiza',
                'descripcion' => 'Sesión de práctica personal, ideal para mejorar técnica individual.',
                'cancha_id' => $canchas->last()->id,
                'organizador_id' => $usuarios->skip(3)->first()->id, // Ana Martínez
                'fecha' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'hora_inicio' => '18:00:00',
                'hora_fin' => '19:00:00',
                'fecha_hora_inicio' => Carbon::today()->addDays(3)->setTime(18, 0),
                'fecha_hora_fin' => Carbon::today()->addDays(3)->setTime(19, 0),
                'nivel_juego' => 'casual',
                'jugadores_requeridos' => 1,
                'jugadores_confirmados' => 1,
                'costo_por_jugador' => 25.00,
                'costo_total' => 25.00,
                'estado' => 'publicado'
            ],
            [
                'titulo' => 'Torneo F5 - Belgrano',
                'descripcion' => 'Torneo de fútbol 5 con premio para el ganador. Máxima competitividad.',
                'cancha_id' => $canchas->last()->id,
                'organizador_id' => $usuarios->skip(4)->first()->id, // Diego Rodríguez
                'fecha' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'hora_inicio' => '16:00:00',
                'hora_fin' => '20:00:00',
                'fecha_hora_inicio' => Carbon::today()->addDays(5)->setTime(16, 0),
                'fecha_hora_fin' => Carbon::today()->addDays(5)->setTime(20, 0),
                'nivel_juego' => 'serio',
                'jugadores_requeridos' => 20,
                'jugadores_confirmados' => 12,
                'costo_por_jugador' => 30.00,
                'costo_total' => 600.00,
                'estado' => 'publicado'
            ],
            [
                'titulo' => 'Partido Mixto F7 - San Telmo',
                'descripcion' => 'Partido mixto de fútbol 7, hombres y mujeres bienvenidos.',
                'cancha_id' => $canchas->first()->id,
                'organizador_id' => $usuarios->skip(1)->first()->id, // María González
                'fecha' => Carbon::today()->addDays(7)->format('Y-m-d'),
                'hora_inicio' => '17:00:00',
                'hora_fin' => '19:00:00',
                'fecha_hora_inicio' => Carbon::today()->addDays(7)->setTime(17, 0),
                'fecha_hora_fin' => Carbon::today()->addDays(7)->setTime(19, 0),
                'nivel_juego' => 'casual',
                'jugadores_requeridos' => 14,
                'jugadores_confirmados' => 6,
                'costo_por_jugador' => 18.00,
                'costo_total' => 252.00,
                'estado' => 'publicado'
            ]
        ];

        foreach ($partidos as $partido) {
            Partido::create($partido);
        }
        
        $this->command->info('Partidos de ejemplo creados exitosamente.');
    }
}
