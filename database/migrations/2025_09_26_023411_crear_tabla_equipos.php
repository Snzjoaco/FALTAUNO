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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capitan_id')->constrained('users')->onDelete('cascade');
            
            // Información del equipo
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('logo')->nullable();
            $table->string('color_principal')->nullable();
            $table->string('color_secundario')->nullable();
            
            // Configuración del equipo
            $table->enum('tipo_equipo', ['f5', 'f7', 'f11', 'mixto'])->default('f5');
            $table->enum('nivel_equipo', ['principiante', 'intermedio', 'avanzado', 'mixto'])->default('mixto');
            $table->integer('capacidad_maxima')->default(15);
            $table->integer('jugadores_activos')->default(1);
            
            // Ubicación preferida
            $table->string('zona_preferida')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Estado del equipo
            $table->boolean('activo')->default(true);
            $table->boolean('publico')->default(true);
            $table->boolean('busca_jugadores')->default(true);
            
            // Estadísticas
            $table->integer('partidos_jugados')->default(0);
            $table->integer('partidos_ganados')->default(0);
            $table->integer('partidos_empatados')->default(0);
            $table->integer('partidos_perdidos')->default(0);
            $table->decimal('rating_equipo', 3, 2)->default(0.00);
            
            // Configuraciones
            $table->json('reglas_equipo')->nullable();
            $table->text('notas_capitan')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['capitan_id', 'activo']);
            $table->index(['tipo_equipo', 'nivel_equipo']);
            $table->index(['busca_jugadores', 'activo']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
