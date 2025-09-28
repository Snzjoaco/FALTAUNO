<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participantes_partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('fecha_inscripcion')->default(now());
            $table->timestamps();
            
            $table->unique(['partido_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes_partidos');
    }
};
