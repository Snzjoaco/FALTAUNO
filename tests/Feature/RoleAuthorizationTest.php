<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\OwnerInvitationNotification;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('remember_token')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('role')->default('jugador');
            $table->boolean('is_suspended')->default(false);
            $table->string('owner_status')->nullable();
            $table->string('billing_plan')->nullable();
            $table->decimal('billing_amount', 10, 2)->nullable();
            $table->string('billing_status')->nullable();
            $table->timestamp('owner_registered_at')->nullable();
            $table->timestamp('trial_started_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('next_charge_at')->nullable();
            $table->timestamp('last_paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id');
            $table->string('nombre')->default('Cancha de prueba');
            $table->text('descripcion')->nullable();
            $table->string('direccion')->default('Calle 123');
            $table->string('ciudad')->default('Buenos Aires');
            $table->decimal('latitude', 10, 8)->default(-34.6037);
            $table->decimal('longitude', 11, 8)->default(-58.3816);
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('tipo_superficie')->default('cesped_sintetico');
            $table->string('tipo_cancha')->default('f5');
            $table->string('codigo_postal')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->json('imagenes')->nullable();
            $table->json('horarios_semana')->nullable();
            $table->json('horarios_fin_semana')->nullable();
            $table->integer('capacidad_maxima')->default(10);
            $table->boolean('activa')->default(true);
            $table->boolean('verificada')->default(false);
            $table->boolean('suspendida_admin')->default(false);
            $table->boolean('destacada')->default(false);
            $table->string('logo_path')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('horario_atencion')->nullable();
            $table->decimal('precio_base', 10, 2)->default(25000);
            $table->integer('anticipacion_minima_horas')->default(2);
            $table->integer('anticipacion_maxima_dias')->default(30);
            $table->boolean('permite_cancelacion')->default(true);
            $table->integer('horas_cancelacion_gratuita')->default(24);
            $table->decimal('porcentaje_penalidad_cancelacion', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id');
            $table->foreignId('usuario_id');
            $table->string('codigo_reserva')->unique();
            $table->string('titulo');
            $table->string('tipo_reserva')->default('individual');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamp('fecha_hora_inicio')->nullable();
            $table->timestamp('fecha_hora_fin')->nullable();
            $table->integer('duracion_horas');
            $table->integer('cantidad_personas');
            $table->decimal('precio_por_hora', 10, 2);
            $table->decimal('precio_total', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('monto_final', 10, 2);
            $table->string('estado');
            $table->string('metodo_pago');
            $table->string('estado_pago');
            $table->boolean('permite_cancelacion')->default(true);
            $table->timestamp('fecha_limite_cancelacion')->nullable();
            $table->decimal('porcentaje_penalidad', 5, 2)->default(0);
            $table->text('comentarios_cliente')->nullable();
            $table->timestamps();
        });

        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id');
            $table->timestamps();
        });

        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable();
            $table->string('action');
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function test_player_cannot_access_owner_dashboard_or_platform_moderation(): void
    {
        $player = $this->createUser(User::ROLE_PLAYER);

        $this->actingAs($player)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($player)->get('/admin/moderacion/usuarios')->assertForbidden();
    }

    public function test_owner_cannot_manage_another_owners_court_reservation_or_slot(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $otherOwner = $this->createUser(User::ROLE_COURT_OWNER);
        $otherCourtId = DB::table('canchas')->insertGetId([
            'propietario_id' => $otherOwner->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $reservationId = DB::table('reservas')->insertGetId([
            'cancha_id' => $otherCourtId,
            'usuario_id' => $otherOwner->id,
            'codigo_reserva' => uniqid('RES-'),
            'titulo' => 'Reserva de prueba',
            'fecha' => now()->addDays(2)->toDateString(),
            'hora_inicio' => '10:00',
            'hora_fin' => '11:00',
            'duracion_horas' => 1,
            'cantidad_personas' => 1,
            'precio_por_hora' => 1000,
            'precio_total' => 1000,
            'monto_final' => 1000,
            'estado' => 'pendiente',
            'metodo_pago' => 'efectivo',
            'estado_pago' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $turnId = DB::table('turnos')->insertGetId([
            'cancha_id' => $otherCourtId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($owner)->get("/admin/canchas/{$otherCourtId}")->assertNotFound();
        $this->actingAs($owner)->get("/admin/reservas/{$reservationId}")->assertNotFound();
        $this->actingAs($owner)->get("/admin/turnos/{$turnId}")->assertNotFound();
    }

    public function test_suspended_owner_cannot_use_dashboard(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER, true);

        $this->actingAs($owner)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_owner_cannot_reactivate_a_court_suspended_by_the_platform(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $courtId = DB::table('canchas')->insertGetId([
            'propietario_id' => $owner->id,
            'activa' => false,
            'suspendida_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($owner)
            ->post("/admin/canchas/{$courtId}/toggle-estado")
            ->assertForbidden();
    }

    public function test_owner_can_reactivate_a_court_they_deactivated(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $courtId = DB::table('canchas')->insertGetId([
            'propietario_id' => $owner->id,
            'activa' => false,
            'suspendida_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($owner)
            ->post("/admin/canchas/{$courtId}/toggle-estado")
            ->assertRedirect();

        $this->assertDatabaseHas('canchas', ['id' => $courtId, 'activa' => true]);
    }

    public function test_player_can_reach_booking_validation(): void
    {
        $player = $this->createUser(User::ROLE_PLAYER);

        $this->actingAs($player)
            ->post('/reservas', [])
            ->assertSessionHasErrors(['cancha_id', 'fecha', 'hora_inicio', 'hora_fin', 'cantidad_personas']);
    }

    public function test_player_can_view_unverified_court_published_by_its_owner(): void
    {
        $player = $this->createUser(User::ROLE_PLAYER);
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        DB::table('canchas')->insert([
            'propietario_id' => $owner->id,
            'nombre' => 'Cancha recién publicada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($player)
            ->get('/reservar-canchas')
            ->assertOk()
            ->assertSee('Cancha recién publicada')
            ->assertSee('Cancha disponible para reservas.');
    }

    public function test_player_can_book_an_unverified_published_court_at_server_calculated_price(): void
    {
        $player = $this->createUser(User::ROLE_PLAYER);
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $courtId = DB::table('canchas')->insertGetId([
            'propietario_id' => $owner->id,
            'precio_base' => 25000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data = [
            'cancha_id' => $courtId,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora_inicio' => '10:00',
            'hora_fin' => '12:00',
            'cantidad_personas' => 5,
            'precio_por_hora' => 1,
        ];

        $this->actingAs($player)->post('/reservas', $data)
            ->assertRedirect(route('mis-reservas'));

        $this->assertDatabaseHas('canchas', ['id' => $courtId, 'verificada' => false, 'activa' => true]);
        $this->assertDatabaseHas('reservas', [
            'cancha_id' => $courtId,
            'usuario_id' => $player->id,
            'precio_por_hora' => 25000,
            'precio_total' => 50000,
            'monto_final' => 50000,
            'estado' => 'pendiente',
        ]);

        $anotherPlayer = $this->createUser(User::ROLE_PLAYER);
        $this->actingAs($anotherPlayer)->post('/reservas', $data)
            ->assertSessionHasErrors('hora_inicio');
        $this->assertDatabaseCount('reservas', 1);
    }

    public function test_admin_invites_an_owner_and_the_owner_activates_by_setting_a_password(): void
    {
        $admin = $this->createUser(User::ROLE_PLATFORM_ADMIN);
        $data = [
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'email' => 'ana@example.test',
            'phone' => '3515551234',
            'city' => 'Córdoba',
            'billing_plan' => '',
            'billing_amount' => '',
            'nombre' => 'Complejo Central',
            'descripcion' => 'Complejo de prueba',
            'telefono' => '3515554321',
            'email_complejo' => '',
            'instagram_url' => '',
            'horario_atencion' => 'Todos los días de 9 a 23',
            'direccion' => 'Calle 123',
            'ciudad_complejo' => 'Córdoba',
            'latitude' => -31.4167,
            'longitude' => -64.1833,
            'tipo_superficie' => 'cesped_sintetico',
            'tipo_cancha' => 'f5',
            'capacidad_maxima' => 10,
            'precio_base' => 25000,
        ];

        $this->actingAs($admin)
            ->post('/admin/duenos', $data)
            ->assertRedirect('/admin/duenos')
            ->assertSessionHasNoErrors();

        $owner = User::where('email', 'ana@example.test')->firstOrFail();
        $this->assertSame('invitado', $owner->owner_status);
        $this->assertSame('al_dia', $owner->billing_status);
        $this->assertSame('Complejo Central', $owner->canchas()->firstOrFail()->nombre);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'ana@example.test']);
        $this->assertSame(
            $owner->trial_started_at->copy()->addMonth()->format('Y-m-d H:i'),
            $owner->trial_ends_at->format('Y-m-d H:i'),
        );

        $token = Password::createToken($owner);
        $this->post('/password/reset', [
            'token' => $token,
            'email' => $owner->email,
            'password' => 'secure-password-123',
            'password_confirmation' => 'secure-password-123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'id' => $owner->id,
            'owner_status' => 'activo',
        ]);
    }

    public function test_owner_invitation_uses_branded_spanish_email(): void
    {
        Notification::fake();
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $owner->update(['owner_status' => 'invitado']);

        $owner->sendPasswordResetNotification('sample-invitation-token');

        Notification::assertSentTo($owner, OwnerInvitationNotification::class);
        $mail = (new OwnerInvitationNotification('sample-invitation-token'))->toMail($owner);
        $this->assertSame('Tu invitación para administrar un complejo en FaltaUno', $mail->subject);
        $this->assertSame([
            'html' => 'emails.owner-invitation',
            'text' => 'emails.owner-invitation-text',
        ], $mail->view);
        $this->assertSame('Test', $mail->viewData['firstName']);
        $this->assertStringContainsString(
            'Activar mi cuenta y elegir contraseña',
            view('emails.owner-invitation', $mail->viewData)->render(),
        );
    }

    public function test_owner_plan_expiration_is_persisted_and_hides_its_courts(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $owner->update([
            'trial_ends_at' => now()->subDay(),
            'next_charge_at' => now()->subDay(),
        ]);
        DB::table('canchas')->insert([
            'propietario_id' => $owner->id,
            'nombre' => 'Cancha con plan vencido',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('app:expire-owner-plans')->assertExitCode(0);

        $this->assertDatabaseHas('users', ['id' => $owner->id, 'billing_status' => 'vencido']);
        $this->get('/reservar-canchas')->assertDontSee('Cancha con plan vencido');
        $this->assertDatabaseHas('admin_activities', [
            'action' => 'billing.plan_expired',
            'subject_id' => $owner->id,
        ]);
    }

    public function test_admin_recording_a_payment_reactivates_billing_for_thirty_days(): void
    {
        $admin = $this->createUser(User::ROLE_PLATFORM_ADMIN);
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        $owner->update([
            'billing_status' => 'vencido',
            'trial_ends_at' => now()->subDays(10),
            'next_charge_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->post("/admin/duenos/{$owner->id}/pago")
            ->assertRedirect();

        $owner->refresh();
        $this->assertSame('al_dia', $owner->billing_status);
        $this->assertSame(now()->addDays(30)->format('Y-m-d'), $owner->next_charge_at->format('Y-m-d'));
        $this->assertNotNull($owner->last_paid_at);
        $this->assertDatabaseHas('admin_activities', [
            'action' => 'owner.payment_recorded',
            'subject_id' => $owner->id,
        ]);
    }

    public function test_home_only_displays_featured_courts_that_are_published(): void
    {
        $owner = $this->createUser(User::ROLE_COURT_OWNER);
        DB::table('canchas')->insert([
            [
                'propietario_id' => $owner->id,
                'nombre' => 'Cancha destacada publicada',
                'destacada' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'propietario_id' => $owner->id,
                'nombre' => 'Cancha no destacada',
                'destacada' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Cancha destacada publicada')
            ->assertDontSee('Cancha no destacada');
    }

    public function test_platform_admin_cannot_assign_owner_or_another_admin_from_generic_user_management(): void
    {
        $admin = $this->createUser(User::ROLE_PLATFORM_ADMIN);
        $player = $this->createUser(User::ROLE_PLAYER);

        $this->actingAs($admin)
            ->get('/admin/moderacion/usuarios')
            ->assertOk()
            ->assertSee('Gestionar usuarios');

        $this->actingAs($admin)
            ->put("/admin/moderacion/usuarios/{$player->id}/rol", ['role' => User::ROLE_PLATFORM_ADMIN])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', ['id' => $player->id, 'role' => User::ROLE_PLAYER]);

        $this->put("/admin/moderacion/usuarios/{$player->id}/rol", ['role' => User::ROLE_COURT_OWNER])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', ['id' => $player->id, 'role' => User::ROLE_PLAYER]);

        $this->actingAs($admin)
            ->get('/admin/duenos/crear')
            ->assertOk()
            ->assertSee('Email de acceso');
    }

    private function createUser(string $role, bool $isSuspended = false): User
    {
        $isOwner = $role === User::ROLE_COURT_OWNER;
        $trialStartedAt = $isOwner ? now() : null;
        $trialEndsAt = $isOwner ? now()->addMonth() : null;
        $id = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => uniqid('user').'@example.test',
            'role' => $role,
            'is_suspended' => $isSuspended,
            'owner_status' => $isOwner ? ($isSuspended ? 'suspendido' : 'activo') : null,
            'billing_status' => $isOwner ? 'al_dia' : null,
            'trial_started_at' => $trialStartedAt,
            'trial_ends_at' => $trialEndsAt,
            'next_charge_at' => $trialEndsAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
