<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use App\Models\Cancha;
use App\Models\Partido;
use App\Models\User;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.moderacion.usuarios', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        abort_if($user->isPlatformAdmin(), 403, 'No se puede cambiar el rol de otro administrador desde este panel.');
        abort_if($user->isCourtOwner(), 403, 'Gestioná las cuentas de dueños desde Gestión de dueños.');

        $validated = $request->validate([
            'role' => ['required', 'in:jugador'],
        ]);

        $user->role = $validated['role'];
        $user->save();
        AdminActivity::record('user.role_updated', $user, null, ['role' => $user->role]);

        return back()->with('success', 'El rol del usuario fue actualizado.');
    }

    public function toggleSuspension(User $user)
    {
        abort_if($user->isPlatformAdmin(), 403, 'No se puede suspender a un administrador desde este panel.');
        abort_if($user->isCourtOwner(), 403, 'Gestioná la suspensión de dueños desde Gestión de dueños.');

        $oldStatus = $user->is_suspended;
        $user->is_suspended = ! $user->is_suspended;
        $user->save();
        AdminActivity::record('user.suspension_toggled', $user, ['is_suspended' => $oldStatus], [
            'is_suspended' => $user->is_suspended,
            'owner_status' => $user->owner_status,
        ]);

        return back()->with('success', $user->is_suspended
            ? 'La cuenta fue suspendida.'
            : 'La cuenta fue reactivada.');
    }

    public function courts()
    {
        $canchas = Cancha::with('propietario')
            ->when(request()->filled('buscar'), function ($query) {
                $search = request()->string('buscar')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('ciudad', 'like', "%{$search}%")
                        ->orWhereHas('propietario', fn ($owner) => $owner
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.moderacion.canchas', compact('canchas'));
    }

    public function updateCourt(Request $request, Cancha $cancha)
    {
        $validated = $request->validate([
            'activa' => ['required', 'boolean'],
        ]);

        $oldValues = $cancha->only(['activa', 'suspendida_admin']);
        $cancha->update([
            'activa' => $validated['activa'],
            'suspendida_admin' => ! $validated['activa'],
        ]);
        AdminActivity::record('court.publication_updated', $cancha, $oldValues, $cancha->only(['activa', 'suspendida_admin']));

        return back()->with('success', $validated['activa']
            ? 'La cancha fue publicada nuevamente.'
            : 'La cancha fue suspendida.');
    }

    public function toggleFeatured(Cancha $cancha)
    {
        $oldValue = $cancha->destacada;
        $cancha->destacada = ! $cancha->destacada;
        $cancha->save();
        AdminActivity::record('court.featured_toggled', $cancha, ['destacada' => $oldValue], [
            'destacada' => $cancha->destacada,
        ]);

        return back()->with('success', $cancha->destacada
            ? 'La cancha se agregó a destacadas.'
            : 'La cancha se quitó de destacadas.');
    }

    public function matches()
    {
        $partidos = Partido::with(['organizador', 'cancha'])->latest()->paginate(20);

        return view('admin.moderacion.partidos', compact('partidos'));
    }

    public function updateMatch(Request $request, Partido $partido)
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:borrador,publicado,completo,en_progreso,finalizado,cancelado,suspendido'],
        ]);

        $oldState = $partido->estado;
        $partido->update(['estado' => $validated['estado']]);
        AdminActivity::record('match.moderated', $partido, ['estado' => $oldState], [
            'estado' => $partido->estado,
        ]);

        return back()->with('success', 'El estado del partido fue actualizado.');
    }

    public function activities()
    {
        $activities = AdminActivity::with('actor')->latest()->paginate(30);

        return view('admin.moderacion.actividad', compact('activities'));
    }
}
