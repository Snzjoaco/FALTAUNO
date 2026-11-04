<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('role')->default('jugador');
            $table->boolean('is_suspended')->default(false);
            $table->timestamps();
        });

        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id');
            $table->string('nombre')->default('Cancha de prueba');
            $table->text('descripcion')->nullable();
            $table->string('direccion')->default('Calle 123');
            $table->string('ciudad')->default('Buenos Aires');
            $table->string('tipo_cancha')->default('f5');
            $table->string('imagen_principal')->nullable();
            $table->integer('capacidad_maxima')->default(10);
            $table->boolean('activa')->default(true);
            $table->boolean('verificada')->default(false);
            $table->boolean('suspendida_admin')->default(false);
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

    public function test_artisan_command_can_bootstrap_an_existing_admin_account(): void
    {
        $player = $this->createUser(User::ROLE_PLAYER);

        $this->artisan('app:make-admin', ['email' => $player->email])->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'id' => $player->id,
            'role' => User::ROLE_PLATFORM_ADMIN,
            'is_suspended' => false,
        ]);
    }

    public function test_platform_admin_can_assign_owner_but_not_create_another_admin_from_web(): void
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
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['id' => $player->id, 'role' => User::ROLE_COURT_OWNER]);
    }

    private function createUser(string $role, bool $isSuspended = false): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => uniqid('user').'@example.test',
            'role' => $role,
            'is_suspended' => $isSuspended,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
