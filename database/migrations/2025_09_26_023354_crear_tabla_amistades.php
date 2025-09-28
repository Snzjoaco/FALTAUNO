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
        Schema::create('amistades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('amigo_id')->constrained('users')->onDelete('cascade');
            
            // Estado de la amistad
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada', 'bloqueada'])->default('pendiente');
            
            // Fechas
            $table->timestamp('fecha_solicitud');
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamp('fecha_ultima_interaccion')->nullable();
            
            // Configuraciones
            $table->boolean('notificaciones_activas')->default(true);
            $table->text('notas')->nullable(); // Notas sobre la amistad
            
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'estado']);
            $table->index(['amigo_id', 'estado']);
            $table->unique(['usuario_id', 'amigo_id']); // No puede haber duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amistades');
    }
};
