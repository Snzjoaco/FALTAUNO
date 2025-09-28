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
        Schema::create('participantes_partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained('partidos')->onDelete('cascade');
            $table->foreignId('jugador_id')->constrained('users')->onDelete('cascade');
            
            // Estado de participación
            $table->enum('estado', [
                'solicitado', 'aceptado', 'rechazado', 'confirmado', 
                'presente', 'ausente', 'cancelado'
            ])->default('solicitado');
            
            // Información del jugador en este partido
            $table->enum('posicion_solicitada', ['arquero', 'defensor', 'mediocampista', 'delantero'])->nullable();
            $table->enum('posicion_asignada', ['arquero', 'defensor', 'mediocampista', 'delantero'])->nullable();
            $table->enum('nivel_jugador', ['principiante', 'intermedio', 'avanzado'])->nullable();
            
            // Preferencias de camiseta
            $table->enum('preferencia_camiseta', ['blanca', 'oscura', 'ambas'])->nullable();
            $table->string('color_camiseta_asignada')->nullable();
            
            // Mensaje del jugador
            $table->text('mensaje_jugador')->nullable(); // Mensaje al organizador
            $table->text('mensaje_organizador')->nullable(); // Respuesta del organizador
            
            // Pago
            $table->decimal('monto_pagado', 8, 2)->default(0.00);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'app'])->nullable();
            $table->boolean('pago_confirmado')->default(false);
            $table->timestamp('fecha_pago')->nullable();
            
            // Asistencia
            $table->boolean('asistio')->nullable(); // null = no se sabe, true = asistió, false = no asistió
            $table->timestamp('hora_llegada')->nullable();
            $table->timestamp('hora_salida')->nullable();
            $table->text('notas_asistencia')->nullable();
            
            // Calificación
            $table->integer('calificacion_recibida')->nullable(); // 1-5 estrellas
            $table->text('comentario_calificacion')->nullable();
            $table->timestamp('fecha_calificacion')->nullable();
            
            // Configuraciones
            $table->boolean('recibe_notificaciones')->default(true);
            $table->boolean('comparte_whatsapp')->default(false);
            $table->string('telefono_whatsapp')->nullable();
            
              // Fechas importantes
              $table->timestamp('fecha_solicitud')->nullable();
              $table->timestamp('fecha_respuesta')->nullable();
              $table->timestamp('fecha_confirmacion')->nullable();
              $table->timestamp('fecha_cancelacion')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['partido_id', 'estado']);
            $table->index(['jugador_id', 'estado']);
            $table->index(['fecha_solicitud']);
            $table->unique(['partido_id', 'jugador_id']); // Un jugador no puede estar dos veces en el mismo partido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes_partidos');
    }
};
