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
        Schema::table('canchas', function (Blueprint $table) {
            // Actualizar las columnas de precios para permitir valores más grandes
            $table->decimal('precio_base', 10, 2)->change(); // Hasta 99,999,999.99
            $table->decimal('precio_pico', 10, 2)->nullable()->change(); // Hasta 99,999,999.99
            $table->decimal('precio_valle', 10, 2)->nullable()->change(); // Hasta 99,999,999.99
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            // Revertir a los valores originales
            $table->decimal('precio_base', 8, 2)->change();
            $table->decimal('precio_pico', 8, 2)->nullable()->change();
            $table->decimal('precio_valle', 8, 2)->nullable()->change();
        });
    }
};