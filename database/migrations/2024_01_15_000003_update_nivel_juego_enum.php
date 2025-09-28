<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Primero actualizar los datos existentes
        DB::table('partidos')->where('nivel_juego', 'principiante')->update(['nivel_juego' => 'casual']);
        DB::table('partidos')->where('nivel_juego', 'intermedio')->update(['nivel_juego' => 'casual']);
        DB::table('partidos')->where('nivel_juego', 'avanzado')->update(['nivel_juego' => 'serio']);
        DB::table('partidos')->where('nivel_juego', 'mixto')->update(['nivel_juego' => 'casual']);
        
        // Luego modificar la columna
        DB::statement("ALTER TABLE partidos MODIFY COLUMN nivel_juego ENUM('casual', 'serio') NOT NULL DEFAULT 'casual'");
    }

    public function down()
    {
        // Revertir los cambios
        DB::table('partidos')->where('nivel_juego', 'casual')->update(['nivel_juego' => 'principiante']);
        DB::table('partidos')->where('nivel_juego', 'serio')->update(['nivel_juego' => 'avanzado']);
        
        DB::statement("ALTER TABLE partidos MODIFY COLUMN nivel_juego ENUM('principiante', 'intermedio', 'avanzado', 'mixto') NOT NULL DEFAULT 'mixto'");
    }
};
