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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            
            // Información del turno
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('duracion_minutos')->default(60);
            
            // Configuración del turno
            $table->boolean('activo')->default(true);
            $table->boolean('recurrente')->default(true); // Si se repite semanalmente
            $table->integer('capacidad_maxima')->default(10);
            $table->integer('capacidad_minima')->default(1);
            
            // Precios
            $table->decimal('precio_base', 10, 2);
            $table->decimal('precio_pico', 10, 2)->nullable();
            $table->decimal('precio_valle', 10, 2)->nullable();
            $table->json('precios_especiales')->nullable(); // Precios por fecha específica
            
            // Configuración de reservas
            $table->integer('anticipacion_minima_horas')->default(2);
            $table->integer('anticipacion_maxima_dias')->default(30);
            $table->boolean('permite_cancelacion')->default(true);
            $table->integer('horas_cancelacion_gratuita')->default(24);
            $table->decimal('porcentaje_penalidad_cancelacion', 5, 2)->default(0.00);
            
            // Restricciones
            $table->boolean('solo_miembros')->default(false);
            $table->json('restricciones_edad')->nullable(); // ['min' => 18, 'max' => 65]
            $table->json('requisitos_especiales')->nullable(); // ['equipamiento', 'experiencia']
            
            // Estado y visibilidad
            $table->boolean('visible_publico')->default(true);
            $table->boolean('destacado')->default(false);
            $table->integer('prioridad')->default(0); // Para ordenar turnos
            
            // Fechas específicas
            $table->date('fecha_inicio')->nullable(); // Fecha de inicio del turno
            $table->date('fecha_fin')->nullable(); // Fecha de fin del turno
            $table->json('fechas_excluidas')->nullable(); // Fechas específicas donde no está disponible
            
            $table->timestamps();
            
            // Índices
            $table->index(['cancha_id', 'dia_semana']);
            $table->index(['cancha_id', 'activo']);
            $table->index(['dia_semana', 'hora_inicio']);
            $table->index(['activo', 'visible_publico']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
