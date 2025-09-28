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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calificador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('calificado_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->onDelete('cascade');
            
            // Calificación
            $table->integer('puntuacion'); // 1-5 estrellas
            $table->text('comentario')->nullable();
            
            // Categorías de calificación
            $table->integer('habilidad_tecnica')->nullable(); // 1-5
            $table->integer('deportividad')->nullable(); // 1-5
            $table->integer('puntualidad')->nullable(); // 1-5
            $table->integer('comunicacion')->nullable(); // 1-5
            $table->integer('liderazgo')->nullable(); // 1-5
            
            // Estado de la calificación
            $table->boolean('publica')->default(true);
            $table->boolean('anonima')->default(false);
            $table->boolean('verificada')->default(false);
            
            // Respuesta del calificado
            $table->text('respuesta_calificado')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            
              // Fechas
              $table->timestamp('fecha_calificacion')->nullable();
              $table->timestamp('fecha_actualizacion')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['calificador_id', 'fecha_calificacion']);
            $table->index(['calificado_id', 'puntuacion']);
            $table->index(['partido_id', 'fecha_calificacion']);
            $table->unique(['calificador_id', 'calificado_id', 'partido_id']); // Una calificación por partido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
