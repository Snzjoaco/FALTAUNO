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
        Schema::table('users', function (Blueprint $table) {
            // Información personal
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            
            // Información de fútbol
            $table->enum('preferred_position', ['arquero', 'defensor', 'mediocampista', 'delantero'])->nullable();
            $table->enum('skill_level', ['principiante', 'intermedio', 'avanzado'])->nullable();
            $table->json('preferred_days')->nullable(); // ['lunes', 'martes', etc]
            $table->string('preferred_time_slot')->nullable(); // "19:00-22:00"
            $table->string('preferred_zone')->nullable(); // "Palermo, Chacarita"
            $table->json('jersey_preferences')->nullable(); // ['blanca', 'oscura', 'ambas']
            
            // Sistema de rating
            $table->decimal('rating', 3, 2)->default(0.00); // 0.00 a 5.00
            $table->integer('total_ratings')->default(0);
            $table->integer('games_played')->default(0);
            $table->integer('games_organized')->default(0);
            
            // Configuraciones
            $table->boolean('email_notifications')->default(true);
            $table->boolean('match_reminders')->default(true);
            $table->boolean('news_and_promos')->default(true);
            $table->boolean('share_whatsapp')->default(false);
            $table->boolean('is_verified')->default(false);
            
            // Geolocalización
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'city', 'bio', 'avatar',
                'preferred_position', 'skill_level', 'preferred_days', 'preferred_time_slot',
                'preferred_zone', 'jersey_preferences', 'rating', 'total_ratings',
                'games_played', 'games_organized', 'email_notifications', 'match_reminders',
                'news_and_promos', 'share_whatsapp', 'is_verified', 'latitude', 'longitude', 'address'
            ]);
        });
    }
};
