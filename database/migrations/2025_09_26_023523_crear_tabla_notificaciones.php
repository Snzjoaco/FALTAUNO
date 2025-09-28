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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->onDelete('cascade');
            $table->foreignId('cancha_id')->nullable()->constrained('canchas')->onDelete('cascade');
            
            // Información de la notificación
            $table->string('titulo');
            $table->text('mensaje');
            $table->enum('tipo', [
                'solicitud_partido', 'aceptacion_partido', 'rechazo_partido',
                'recordatorio_partido', 'nuevo_partido_cerca', 'calificacion_recibida',
                'solicitud_amistad', 'aceptacion_amistad', 'solicitud_equipo',
                'pago_completado', 'pago_pendiente', 'partido_cancelado',
                'partido_completo', 'nuevo_mensaje', 'sistema'
            ])->default('sistema');
            
            // Estado de la notificación
            $table->boolean('leida')->default(false);
            $table->boolean('enviada_email')->default(false);
            $table->boolean('enviada_push')->default(false);
            $table->boolean('enviada_whatsapp')->default(false);
            
            // Datos adicionales
            $table->json('datos_adicionales')->nullable(); // Datos específicos de la notificación
            $table->string('url_accion')->nullable(); // URL para redirigir al hacer clic
            $table->string('icono')->nullable(); // Icono a mostrar
            
            // Fechas
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'leida']);
            $table->index(['tipo', 'fecha_envio']);
            $table->index(['partido_id', 'tipo']);
            $table->index(['cancha_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
