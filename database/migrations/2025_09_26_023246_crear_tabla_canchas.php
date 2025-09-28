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
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id')->constrained('users')->onDelete('cascade');
            
            // Información básica
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('sitio_web')->nullable();
            
            // Ubicación
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('barrio')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('codigo_postal')->nullable();
            
            // Características de la cancha
            $table->enum('tipo_superficie', ['cesped_natural', 'cesped_sintetico', 'cemento', 'parquet'])->default('cesped_sintetico');
            $table->enum('tipo_cancha', ['f5', 'f7', 'f11', 'mixto'])->default('f5');
            $table->integer('capacidad_maxima')->default(10);
            $table->boolean('tiene_vestuarios')->default(false);
            $table->boolean('tiene_estacionamiento')->default(false);
            $table->boolean('tiene_iluminacion')->default(true);
            $table->boolean('tiene_techado')->default(false);
            $table->json('amenities')->nullable(); // ['vestuarios', 'estacionamiento', 'bar', 'wifi']
            
            // Sistema de rating
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_resenas')->default(0);
            $table->integer('total_reservas')->default(0);
            
            // Precios
            $table->decimal('precio_base', 10, 2); // Precio por hora en pesos (hasta 99,999,999.99)
            $table->decimal('precio_pico', 10, 2)->nullable(); // Precio en horario pico
            $table->decimal('precio_valle', 10, 2)->nullable(); // Precio en horario valle
            $table->json('precios_especiales')->nullable(); // Precios por día/hora específica
            
            // Configuración de reservas
            $table->integer('anticipacion_minima_horas')->default(2); // Mínimo de horas de anticipación
            $table->integer('anticipacion_maxima_dias')->default(30); // Máximo de días de anticipación
            $table->boolean('permite_cancelacion')->default(true);
            $table->integer('horas_cancelacion_gratuita')->default(24);
            $table->decimal('porcentaje_penalidad_cancelacion', 5, 2)->default(0.00);
            
            // Estado y visibilidad
            $table->boolean('activa')->default(true);
            $table->boolean('verificada')->default(false);
            $table->boolean('destacada')->default(false); // Aparece en primera plana
            $table->timestamp('fecha_verificacion')->nullable();
            
            // Imágenes
            $table->json('imagenes')->nullable(); // Array de URLs de imágenes
            $table->string('imagen_principal')->nullable();
            
            // Horarios de funcionamiento
            $table->json('horarios_semana')->nullable(); // Horarios de lunes a viernes
            $table->json('horarios_fin_semana')->nullable(); // Horarios de sábado y domingo
            $table->json('dias_cerrado')->nullable(); // Días específicos cerrados
            
            $table->timestamps();
            
            // Índices
            $table->index(['latitude', 'longitude']);
            $table->index(['ciudad', 'barrio']);
            $table->index(['activa', 'verificada']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};
