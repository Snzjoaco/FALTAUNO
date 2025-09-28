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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->onDelete('set null');
            
            // Información de la reserva
            $table->string('codigo_reserva')->unique();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_reserva', ['individual', 'partido', 'evento', 'recurrente'])->default('individual');
            
            // Fecha y hora
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamp('fecha_hora_inicio')->nullable();
            $table->timestamp('fecha_hora_fin')->nullable();
            $table->integer('duracion_horas')->default(1);
            
            // Participantes
            $table->integer('cantidad_personas')->default(1);
            $table->integer('cantidad_confirmados')->default(0);
            $table->json('participantes')->nullable(); // Array de IDs de usuarios
            
            // Costos
            $table->decimal('precio_por_hora', 10, 2);
            $table->decimal('precio_total', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0.00);
            $table->decimal('monto_final', 10, 2);
            $table->string('moneda', 3)->default('ARS');
            
            // Estado de la reserva
            $table->enum('estado', [
                'pendiente', 'confirmada', 'en_curso', 'completada', 
                'cancelada', 'no_show', 'reembolsada'
            ])->default('pendiente');
            
            // Información de pago
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'mercadopago', 'tarjeta'])->default('efectivo');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'reembolsado', 'disputado'])->default('pendiente');
            $table->string('referencia_pago')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            
            // Configuración de cancelación
            $table->boolean('permite_cancelacion')->default(true);
            $table->timestamp('fecha_limite_cancelacion')->nullable();
            $table->decimal('porcentaje_penalidad', 5, 2)->default(0.00);
            $table->text('motivo_cancelacion')->nullable();
            $table->timestamp('fecha_cancelacion')->nullable();
            
            // Notificaciones
            $table->boolean('notificacion_email')->default(true);
            $table->boolean('notificacion_sms')->default(false);
            $table->timestamp('ultima_notificacion')->nullable();
            
            // Datos adicionales
            $table->json('datos_adicionales')->nullable(); // Información extra
            $table->text('notas_internas')->nullable(); // Notas del propietario
            $table->text('comentarios_cliente')->nullable(); // Comentarios del cliente
            
            $table->timestamps();
            
            // Índices
            $table->index(['cancha_id', 'fecha']);
            $table->index(['usuario_id', 'estado']);
            $table->index(['fecha_hora_inicio', 'fecha_hora_fin']);
            $table->index('estado');
            $table->index('codigo_reserva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
