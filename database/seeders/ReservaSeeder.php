<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\User;
use Carbon\Carbon;

class ReservaSeeder extends Seeder
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

        // Crear reservas de prueba
        $estados = ['pendiente', 'confirmada', 'en_curso', 'completada', 'cancelada'];
        $estadosPago = ['pendiente', 'pagado', 'reembolsado'];
        $metodosPago = ['efectivo', 'transferencia', 'mercadopago', 'tarjeta'];
        $tiposReserva = ['individual', 'partido', 'evento', 'recurrente'];

        foreach ($canchas as $cancha) {
            // Crear 5-10 reservas por cancha
            $cantidadReservas = rand(5, 10);
            
            for ($i = 0; $i < $cantidadReservas; $i++) {
                $fecha = Carbon::now()->addDays(rand(-30, 30));
                $horaInicio = rand(8, 20);
                $duracion = rand(1, 3);
                $horaFin = $horaInicio + $duracion;
                
                $precioPorHora = $cancha->precio_base;
                $precioTotal = $precioPorHora * $duracion;
                $descuento = rand(0, 1) ? rand(500, 2000) : 0;
                $montoFinal = $precioTotal - $descuento;
                
                $estado = $estados[array_rand($estados)];
                $estadoPago = $estadosPago[array_rand($estadosPago)];
                
                // Ajustar estado de pago según estado de reserva
                if ($estado === 'completada') {
                    $estadoPago = 'pagado';
                } elseif ($estado === 'cancelada') {
                    $estadoPago = rand(0, 1) ? 'reembolsado' : 'pendiente';
                }

                Reserva::create([
                    'cancha_id' => $cancha->id,
                    'usuario_id' => $cancha->propietario_id,
                    'codigo_reserva' => 'RES-' . strtoupper(uniqid()),
                    'titulo' => $this->generarTitulo($tiposReserva[array_rand($tiposReserva)]),
                    'descripcion' => $this->generarDescripcion(),
                    'tipo_reserva' => $tiposReserva[array_rand($tiposReserva)],
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora_inicio' => sprintf('%02d:00', $horaInicio),
                    'hora_fin' => sprintf('%02d:00', $horaFin),
                    'fecha_hora_inicio' => $fecha->setTime($horaInicio, 0),
                    'fecha_hora_fin' => $fecha->setTime($horaFin, 0),
                    'duracion_horas' => $duracion,
                    'cantidad_personas' => rand(1, $cancha->capacidad_maxima),
                    'cantidad_confirmados' => rand(0, 8),
                    'precio_por_hora' => $precioPorHora,
                    'precio_total' => $precioTotal,
                    'descuento' => $descuento,
                    'monto_final' => $montoFinal,
                    'metodo_pago' => $metodosPago[array_rand($metodosPago)],
                    'estado' => $estado,
                    'estado_pago' => $estadoPago,
                    'referencia_pago' => $estadoPago === 'pagado' ? 'PAY-' . strtoupper(uniqid()) : null,
                    'fecha_pago' => $estadoPago === 'pagado' ? $fecha->subDays(rand(1, 7)) : null,
                    'permite_cancelacion' => $cancha->permite_cancelacion,
                    'fecha_limite_cancelacion' => $fecha->subHours($cancha->horas_cancelacion_gratuita),
                    'porcentaje_penalidad' => $cancha->porcentaje_penalidad_cancelacion,
                    'notificacion_email' => true,
                    'notificacion_sms' => rand(0, 1),
                    'comentarios_cliente' => rand(0, 1) ? $this->generarComentario() : null,
                    'notas_internas' => rand(0, 1) ? $this->generarNotaInterna() : null,
                    'created_at' => $fecha->subDays(rand(1, 30)),
                    'updated_at' => $fecha->subDays(rand(0, 5))
                ]);
            }
        }

        $this->command->info('Reservas de prueba creadas exitosamente.');
    }

    private function generarTitulo($tipoReserva)
    {
        $titulos = [
            'individual' => [
                'Entrenamiento Personal',
                'Práctica Individual',
                'Sesión de Entrenamiento',
                'Tiempo Libre'
            ],
            'partido' => [
                'Partido de Fútbol 5',
                'Partido entre Amigos',
                'Torneo Interno',
                'Partido de Liga'
            ],
            'evento' => [
                'Cumpleaños',
                'Evento Corporativo',
                'Celebración',
                'Actividad Especial'
            ],
            'recurrente' => [
                'Entrenamiento Semanal',
                'Clase Regular',
                'Práctica Fija',
                'Horario Habitual'
            ]
        ];

        $opciones = $titulos[$tipoReserva] ?? $titulos['individual'];
        return $opciones[array_rand($opciones)];
    }

    private function generarDescripcion()
    {
        $descripciones = [
            'Partido entre amigos del barrio',
            'Entrenamiento del equipo local',
            'Actividad recreativa',
            'Práctica de fútbol',
            'Evento deportivo',
            'Tiempo libre para jugar',
            'Entrenamiento personalizado',
            'Actividad grupal'
        ];

        return $descripciones[array_rand($descripciones)];
    }

    private function generarComentario()
    {
        $comentarios = [
            'Excelente cancha, muy recomendable',
            'Buen estado, volveremos pronto',
            'Muy buena atención',
            'Cancha en perfectas condiciones',
            'Servicio impecable',
            'Muy satisfechos con el servicio',
            'Recomendamos esta cancha',
            'Excelente experiencia'
        ];

        return $comentarios[array_rand($comentarios)];
    }

    private function generarNotaInterna()
    {
        $notas = [
            'Cliente frecuente, dar descuento',
            'Verificar iluminación antes del partido',
            'Cliente nuevo, explicar reglas',
            'Grupo grande, preparar vestuarios',
            'Cliente VIP, atención especial',
            'Verificar estado de la cancha',
            'Grupo de niños, supervisión extra',
            'Evento importante, preparar todo'
        ];

        return $notas[array_rand($notas)];
    }
}