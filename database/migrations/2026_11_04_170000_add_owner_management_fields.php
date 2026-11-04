<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('owner_status')->nullable()->index();
            $table->string('billing_plan')->nullable();
            $table->decimal('billing_amount', 10, 2)->nullable();
            $table->string('billing_status')->nullable()->index();
            $table->timestamp('owner_registered_at')->nullable();
            $table->timestamp('trial_started_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('next_charge_at')->nullable();
            $table->timestamp('last_paid_at')->nullable();
        });

        Schema::table('canchas', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('horario_atencion')->nullable();
        });

        $trialStartedAt = now();
        $trialEndsAt = $trialStartedAt->copy()->addMonth();

        foreach ([false => 'activo', true => 'suspendido'] as $suspended => $ownerStatus) {
            DB::table('users')
                ->where('role', 'dueno_cancha')
                ->where('is_suspended', (bool) $suspended)
                ->update([
                    'owner_status' => $ownerStatus,
                    'billing_status' => 'al_dia',
                    'owner_registered_at' => DB::raw('created_at'),
                    'trial_started_at' => $trialStartedAt,
                    'trial_ends_at' => $trialEndsAt,
                    'next_charge_at' => $trialEndsAt,
                ]);
        }

        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activities');

        Schema::table('canchas', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'instagram_url', 'horario_atencion']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['owner_status']);
            $table->dropIndex(['billing_status']);
            $table->dropColumn([
                'owner_status',
                'billing_plan',
                'billing_amount',
                'billing_status',
                'owner_registered_at',
                'trial_started_at',
                'trial_ends_at',
                'next_charge_at',
                'last_paid_at',
            ]);
        });
    }
};
