<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            
            // Información básica del partido
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_partido', ['f5', 'f7', 'f11', 'mixto'])->default('f5');
            $table->enum('nivel_juego', ['principiante', 'intermedio', 'avanzado', 'mixto'])->default('mixto');
            
              // Fecha y hora
              $table->date('fecha');
              $table->time('hora_inicio');
              $table->time('hora_fin');
              $table->timestamp('fecha_hora_inicio')->nullable();
              $table->timestamp('fecha_hora_fin')->nullable();
            
            // Participantes
            $table->integer('jugadores_requeridos');
            $table->integer('jugadores_confirmados')->default(0);
            $table->integer('jugadores_pendientes')->default(0);
            $table->json('posiciones_requeridas')->nullable(); // ['arquero', 'defensor', etc]
            
            // Costos
            $table->decimal('costo_total', 8, 2);
            $table->decimal('costo_por_jugador', 8, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'app', 'mixto'])->default('efectivo');
            $table->text('info_pago')->nullable(); // Información adicional sobre el pago
            
            // Estado del partido
            $table->enum('estado', [
                'borrador', 'publicado', 'completo', 'en_progreso', 
                'finalizado', 'cancelado', 'suspendido'
            ])->default('borrador');
            
            // Configuraciones
            $table->boolean('permite_invitados')->default(true);
            $table->boolean('requiere_confirmacion')->default(true);
            $table->integer('anticipacion_minima_horas')->default(2);
            $table->boolean('permite_cancelacion')->default(true);
            $table->integer('horas_cancelacion_gratuita')->default(12);
            
            // Geolocalización (puede ser diferente a la cancha)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('direccion_especifica')->nullable();
            
            // Chat y comunicación
            $table->boolean('chat_activo')->default(true);
            $table->timestamp('ultima_actividad_chat')->nullable();
            
            // Estadísticas
            $table->integer('vistas')->default(0);
            $table->integer('solicitudes')->default(0);
            $table->decimal('rating_promedio', 3, 2)->nullable();
            $table->integer('total_calificaciones')->default(0);
            
            // Configuraciones especiales
            $table->json('reglas_especiales')->nullable(); // Reglas específicas del partido
            $table->json('equipamiento_requerido')->nullable(); // ['pelota', 'conos', 'silbato']
            $table->boolean('requiere_experiencia')->default(false);
            $table->text('notas_organizador')->nullable();
            
            // Fechas importantes
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamp('fecha_cierre_inscripciones')->nullable();
            $table->timestamp('fecha_recordatorio')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['fecha', 'hora_inicio']);
            $table->index(['estado', 'fecha']);
            $table->index(['organizador_id', 'estado']);
            $table->index(['cancha_id', 'fecha']);
            $table->index(['latitude', 'longitude']);
            $table->index('nivel_juego');
            $table->index('tipo_partido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
