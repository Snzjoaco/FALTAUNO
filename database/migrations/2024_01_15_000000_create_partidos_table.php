<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('cancha_id')->constrained()->onDelete('cascade');
            $table->foreignId('organizador_id')->constrained('users')->onDelete('cascade');
            $table->datetime('fecha');
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado']);
            $table->integer('jugadores_necesarios');
            $table->integer('jugadores_confirmados')->default(1);
            $table->decimal('precio_por_persona', 8, 2);
            $table->boolean('equipamiento_incluido')->default(false);
            $table->string('contacto_whatsapp')->nullable();
            $table->enum('estado', ['activo', 'completo', 'cancelado', 'finalizado'])->default('activo');
            $table->timestamps();
            
            $table->index(['fecha', 'estado']);
            $table->index(['nivel', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('partidos');
    }
};
