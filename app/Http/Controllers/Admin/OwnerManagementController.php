<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class OwnerManagementController extends Controller
{
    public function index(Request $request)
    {
        $owners = User::courtOwners()
            ->withCount('canchas')
            ->withCount('reservasDeCanchas')
            ->when($request->filled('estado'), fn (Builder $query) => $query->where('owner_status', $request->estado))
            ->when($request->filled('buscar'), function (Builder $query) use ($request) {
                $search = $request->string('buscar')->toString();
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('owner_registered_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.duenos.index', compact('owners'));
    }

    public function create()
    {
        return view('admin.duenos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $owner = DB::transaction(function () use ($validated, $request) {
            $now = now();
            $trialEndsAt = $now->copy()->addMonth();
            $owner = User::create([
                'name' => trim($validated['first_name'].' '.$validated['last_name']),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'password' => Hash::make(Str::random(64)),
                'role' => User::ROLE_COURT_OWNER,
                'owner_status' => 'invitado',
                'is_suspended' => false,
                'owner_registered_at' => $now,
                'trial_started_at' => $now,
                'billing_plan' => $validated['billing_plan'] ?: null,
                'billing_amount' => $validated['billing_amount'] ?? null,
                'billing_status' => 'al_dia',
                'trial_ends_at' => $trialEndsAt,
                'next_charge_at' => $trialEndsAt,
            ]);

            $court = Cancha::create($this->courtData($validated, $request, $owner->id));
            AdminActivity::record('owner.invited', $owner, null, [
                'email' => $owner->email,
                'court_id' => $court->id,
                'owner_status' => $owner->owner_status,
            ]);
            AdminActivity::record('court.created_for_owner', $court, null, [
                'owner_id' => $owner->id,
                'name' => $court->nombre,
            ]);

            return $owner;
        });

        return $this->sendInvitation($owner, true);
    }

    public function edit(User $owner)
    {
        $this->ensureOwner($owner);
        $owner->load('canchas');

        return view('admin.duenos.edit', compact('owner'));
    }

    public function update(Request $request, User $owner)
    {
        $this->ensureOwner($owner);
        $validated = $request->validate($this->rules($owner));
        $oldValues = $owner->only([
            'name', 'email', 'phone', 'owner_status', 'billing_plan', 'billing_amount',
            'billing_status', 'next_charge_at',
        ]);
        $court = $owner->canchas()->find($validated['cancha_id'] ?? null);
        abort_if(isset($validated['cancha_id']) && !$court, 404);

        DB::transaction(function () use ($request, $validated, $owner, $court, $oldValues) {
            $owner->update([
                'name' => trim($validated['first_name'].' '.$validated['last_name']),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'billing_plan' => $validated['billing_plan'] ?: null,
                'billing_amount' => $validated['billing_amount'] ?? null,
            ]);

            if ($court) {
                $oldCourtValues = $court->only([
                    'nombre', 'direccion', 'ciudad', 'telefono', 'email', 'instagram_url',
                    'horario_atencion', 'latitude', 'longitude', 'tipo_superficie',
                    'tipo_cancha', 'capacidad_maxima', 'precio_base', 'logo_path', 'imagenes',
                ]);
                $court->update($this->courtData($validated, $request, $owner->id, $court));
                AdminActivity::record(
                    'court.updated',
                    $court,
                    $oldCourtValues,
                    $court->only(array_keys($oldCourtValues)),
                );
            } else {
                $court = Cancha::create($this->courtData($validated, $request, $owner->id));
                AdminActivity::record('court.created_for_owner', $court, null, [
                    'owner_id' => $owner->id,
                    'name' => $court->nombre,
                ]);
            }

            AdminActivity::record('owner.updated', $owner, $oldValues, $owner->only([
                'name', 'email', 'phone', 'owner_status', 'billing_plan', 'billing_amount',
                'billing_status', 'next_charge_at',
            ]));
        });

        return redirect()->route('admin.duenos.index')->with('success', 'Se actualizaron los datos del dueño y su complejo.');
    }

    public function resendInvitation(User $owner)
    {
        $this->ensureOwner($owner);
        abort_unless($owner->owner_status === 'invitado', 422, 'La invitación solo se puede reenviar a cuentas invitadas.');

        return $this->sendInvitation($owner, false);
    }

    public function setStatus(Request $request, User $owner)
    {
        $this->ensureOwner($owner);
        $validated = $request->validate([
            'owner_status' => ['required', 'in:activo,suspendido'],
        ]);
        abort_if(
            $validated['owner_status'] === 'activo' && $owner->owner_status !== 'suspendido',
            422,
            'Una invitación se activa cuando el dueño define su contraseña.',
        );

        $oldStatus = $owner->owner_status;
        $owner->owner_status = $validated['owner_status'];
        $owner->is_suspended = $validated['owner_status'] === 'suspendido';
        $owner->save();

        AdminActivity::record(
            $validated['owner_status'] === 'suspendido' ? 'owner.suspended' : 'owner.reactivated',
            $owner,
            ['owner_status' => $oldStatus],
            ['owner_status' => $owner->owner_status],
        );

        return back()->with('success', $owner->owner_status === 'suspendido'
            ? 'Se suspendió al dueño y sus canchas dejaron de publicarse.'
            : 'Se reactivó al dueño. Sus canchas activas volverán a publicarse si está al día.');
    }

    public function markPaid(User $owner)
    {
        $this->ensureOwner($owner);
        abort_unless(
            in_array($owner->owner_status, ['activo', 'suspendido'], true),
            422,
            'Solo se pueden registrar pagos de dueños que ya aceptaron la invitación.',
        );

        $oldValues = $owner->only(['billing_status', 'next_charge_at', 'last_paid_at']);
        $owner->billing_status = 'al_dia';
        $owner->last_paid_at = now();
        $owner->next_charge_at = now()->addDays(30);
        $owner->save();

        AdminActivity::record('owner.payment_recorded', $owner, $oldValues, $owner->only([
            'billing_status', 'next_charge_at', 'last_paid_at',
        ]));

        return back()->with('success', 'Pago registrado. El próximo vencimiento quedó en 30 días.');
    }

    private function sendInvitation(User $owner, bool $created): \Illuminate\Http\RedirectResponse
    {
        $status = Password::sendResetLink(['email' => $owner->email]);
        $sent = $status === Password::RESET_LINK_SENT;

        AdminActivity::record(
            $sent ? 'owner.invitation_sent' : 'owner.invitation_failed',
            $owner,
            null,
            ['email' => $owner->email, 'broker_status' => $status],
        );

        if (!$sent) {
            return back()->with('error', 'La cuenta quedó creada como invitada, pero no se pudo enviar el correo ('.$status.'). Revisá el correo saliente y reenviá la invitación.');
        }

        return redirect()->route('admin.duenos.index')->with(
            'success',
            $created
                ? 'Dueño y complejo creados. Se envió el enlace para que defina su contraseña.'
                : 'Se reenvió el enlace para definir la contraseña.',
        );
    }

    private function ensureOwner(User $owner): void
    {
        abort_unless($owner->isCourtOwner(), 404);
    }

    private function rules(?User $owner = null): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'.($owner ? ','.$owner->id : '')],
            'phone' => ['required', 'string', 'max:30'],
            'city' => ['required', 'string', 'max:100'],
            'billing_plan' => ['nullable', 'string', 'max:100'],
            'billing_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'cancha_id' => ['nullable', 'integer'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'telefono' => ['required', 'string', 'max:30'],
            'email_complejo' => ['nullable', 'email', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'horario_atencion' => ['required', 'string', 'max:1000'],
            'direccion' => ['required', 'string', 'max:500'],
            'ciudad_complejo' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'tipo_superficie' => ['required', 'in:cesped_natural,cesped_sintetico,cemento,parquet'],
            'tipo_cancha' => ['required', 'in:f5,f7,f11,mixto'],
            'capacidad_maxima' => ['required', 'integer', 'min:1', 'max:50'],
            'precio_base' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'imagenes' => ['nullable', 'array', 'max:10'],
            'imagenes.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ];
    }

    private function courtData(array $validated, Request $request, int $ownerId, ?Cancha $existing = null): array
    {
        $imagenes = $existing?->imagenes ?? [];
        foreach ($request->file('imagenes', []) ?? [] as $image) {
            $imagenes[] = $image->store('canchas', 'public');
        }

        $logoPath = $existing?->logo_path;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('complejos/logos', 'public');
        }

        return [
            'propietario_id' => $ownerId,
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'telefono' => $validated['telefono'],
            'email' => $validated['email_complejo'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'horario_atencion' => $validated['horario_atencion'],
            'direccion' => $validated['direccion'],
            'ciudad' => $validated['ciudad_complejo'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'tipo_superficie' => $validated['tipo_superficie'],
            'tipo_cancha' => $validated['tipo_cancha'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'precio_base' => $validated['precio_base'],
            'logo_path' => $logoPath,
            'imagenes' => $imagenes,
            'imagen_principal' => $existing?->imagen_principal ?? ($imagenes[0] ?? null),
            'activa' => $existing?->activa ?? true,
            'suspendida_admin' => $existing?->suspendida_admin ?? false,
            'verificada' => $existing?->verificada ?? false,
            'horarios_semana' => $existing?->horarios_semana ?? [],
            'horarios_fin_semana' => $existing?->horarios_fin_semana ?? [],
            'anticipacion_minima_horas' => $existing?->anticipacion_minima_horas ?? 2,
            'anticipacion_maxima_dias' => $existing?->anticipacion_maxima_dias ?? 30,
            'permite_cancelacion' => $existing?->permite_cancelacion ?? true,
            'horas_cancelacion_gratuita' => $existing?->horas_cancelacion_gratuita ?? 24,
            'porcentaje_penalidad_cancelacion' => $existing?->porcentaje_penalidad_cancelacion ?? 0,
        ];
    }
}
