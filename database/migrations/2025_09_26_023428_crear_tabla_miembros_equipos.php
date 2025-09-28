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
        Schema::create('miembros_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('jugador_id')->constrained('users')->onDelete('cascade');
            
            // Estado en el equipo
            $table->enum('estado', ['solicitado', 'aceptado', 'rechazado', 'expulsado', 'retirado'])->default('solicitado');
            $table->enum('rol', ['capitan', 'vice_capitan', 'jugador', 'suplente'])->default('jugador');
            
            // Información del jugador en el equipo
            $table->enum('posicion_preferida', ['arquero', 'defensor', 'mediocampista', 'delantero'])->nullable();
            $table->integer('numero_camiseta')->nullable();
            $table->string('color_camiseta')->nullable();
            
            // Estadísticas en el equipo
            $table->integer('partidos_jugados')->default(0);
            $table->integer('goles')->default(0);
            $table->integer('asistencias')->default(0);
            $table->integer('tarjetas_amarillas')->default(0);
            $table->integer('tarjetas_rojas')->default(0);
            $table->decimal('rating_promedio', 3, 2)->default(0.00);
            
            // Fechas importantes
            $table->timestamp('fecha_ingreso');
            $table->timestamp('fecha_salida')->nullable();
            $table->timestamp('ultima_actividad')->nullable();
            
            // Configuraciones
            $table->boolean('notificaciones_activas')->default(true);
            $table->text('notas')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['equipo_id', 'estado']);
            $table->index(['jugador_id', 'estado']);
            $table->unique(['equipo_id', 'jugador_id']); // Un jugador no puede estar dos veces en el mismo equipo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_equipos');
    }
};
