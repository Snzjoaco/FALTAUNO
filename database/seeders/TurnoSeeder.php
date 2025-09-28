<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Turno;
use App\Models\Cancha;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener canchas existentes
        $canchas = Cancha::all();
        
        if ($canchas->isEmpty()) {
            $this->command->info('No hay canchas disponibles. Ejecuta primero el seeder de canchas.');
            return;
        }

        // Crear turnos de prueba
        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        $horarios = [
            ['08:00', '10:00', 'Matutino'],
            ['10:00', '12:00', 'Mañana'],
            ['14:00', '16:00', 'Tarde'],
            ['16:00', '18:00', 'Tarde-Noche'],
            ['18:00', '20:00', 'Noche'],
            ['20:00', '22:00', 'Noche Tardía']
        ];

        foreach ($canchas as $cancha) {
            // Crear 3-5 turnos por cancha
            $cantidadTurnos = rand(3, 5);
            $diasSeleccionados = array_rand(array_flip($diasSemana), $cantidadTurnos);
            
            foreach ($diasSeleccionados as $index => $dia) {
                $horario = $horarios[array_rand($horarios)];
                $precioBase = $cancha->precio_base;
                $precioPico = $precioBase * 1.2; // 20% más caro
                $precioValle = $precioBase * 0.8; // 20% más barato
                
                $turno = Turno::create([
                    'cancha_id' => $cancha->id,
                    'nombre' => $horario[2] . ' - ' . ucfirst($dia),
                    'descripcion' => $this->generarDescripcion($horario[2]),
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario[0],
                    'hora_fin' => $horario[1],
                    'duracion_minutos' => $this->calcularDuracion($horario[0], $horario[1]),
                    'activo' => rand(0, 1) ? true : false,
                    'recurrente' => true,
                    'capacidad_maxima' => $cancha->capacidad_maxima,
                    'capacidad_minima' => 1,
                    'precio_base' => $precioBase,
                    'precio_pico' => $precioPico,
                    'precio_valle' => $precioValle,
                    'anticipacion_minima_horas' => $cancha->anticipacion_minima_horas,
                    'anticipacion_maxima_dias' => $cancha->anticipacion_maxima_dias,
                    'permite_cancelacion' => $cancha->permite_cancelacion,
                    'horas_cancelacion_gratuita' => $cancha->horas_cancelacion_gratuita,
                    'porcentaje_penalidad_cancelacion' => $cancha->porcentaje_penalidad_cancelacion,
                    'solo_miembros' => rand(0, 1) ? true : false,
                    'visible_publico' => true,
                    'destacado' => rand(0, 4) === 0, // 20% de probabilidad
                    'prioridad' => rand(0, 10),
                    'fecha_inicio' => now()->subDays(rand(0, 30)),
                    'fecha_fin' => now()->addDays(rand(30, 90)),
                    'restricciones_edad' => rand(0, 1) ? [
                        'min' => rand(16, 18),
                        'max' => rand(60, 70)
                    ] : null,
                    'requisitos_especiales' => rand(0, 1) ? [
                        'equipamiento_basico',
                        'experiencia_intermedia',
                        'certificado_medico'
                    ] : null
                ]);
            }
        }

        $this->command->info('Turnos de prueba creados exitosamente.');
    }

    private function generarDescripcion($tipoHorario)
    {
        $descripciones = [
            'Matutino' => 'Turno ideal para empezar el día con energía',
            'Mañana' => 'Perfecto para actividades matutinas',
            'Tarde' => 'Horario cómodo para después del almuerzo',
            'Tarde-Noche' => 'Ideal para después del trabajo',
            'Noche' => 'Horario popular para encuentros sociales',
            'Noche Tardía' => 'Para los que prefieren horarios nocturnos'
        ];

        return $descripciones[$tipoHorario] ?? 'Turno disponible para reservas';
    }

    private function calcularDuracion($horaInicio, $horaFin)
    {
        $inicio = \Carbon\Carbon::parse($horaInicio);
        $fin = \Carbon\Carbon::parse($horaFin);
        return $inicio->diffInMinutes($fin);
    }
}