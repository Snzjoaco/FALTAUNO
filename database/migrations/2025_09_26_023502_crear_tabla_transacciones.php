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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->onDelete('cascade');
            $table->foreignId('cancha_id')->nullable()->constrained('canchas')->onDelete('cascade');
            
            // Información de la transacción
            $table->string('codigo_transaccion')->unique();
            $table->enum('tipo', ['pago_partido', 'pago_cancha', 'reembolso', 'comision_plataforma'])->default('pago_partido');
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'mercadopago', 'tarjeta_credito', 'tarjeta_debito'])->default('efectivo');
            
            // Montos
            $table->decimal('monto_total', 10, 2);
            $table->decimal('monto_comision', 10, 2)->default(0.00);
            $table->decimal('monto_neto', 10, 2);
            $table->string('moneda', 3)->default('ARS');
            
            // Estado de la transacción
            $table->enum('estado', [
                'pendiente', 'procesando', 'completada', 'fallida', 
                'cancelada', 'reembolsada', 'disputada'
            ])->default('pendiente');
            
            // Información del pago
            $table->string('referencia_externa')->nullable(); // ID de MercadoPago, etc.
            $table->json('datos_pago')->nullable(); // Datos adicionales del pago
            $table->text('descripcion')->nullable();
            
            // Fechas importantes
            $table->timestamp('fecha_solicitud');
            $table->timestamp('fecha_procesamiento')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            
            // Información adicional
            $table->text('notas')->nullable();
            $table->json('metadata')->nullable(); // Datos adicionales
            $table->string('ip_origen')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'estado']);
            $table->index(['partido_id', 'tipo']);
            $table->index(['cancha_id', 'tipo']);
            $table->index(['fecha_solicitud', 'estado']);
            $table->index('codigo_transaccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
