<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $validated = $request->validate([
            'role' => ['required', 'in:jugador,dueno_cancha'],
        ]);

        $user->role = $validated['role'];
        $user->save();

        return back()->with('success', 'El rol del usuario fue actualizado.');
    }

    public function toggleSuspension(User $user)
    {
        abort_if($user->isPlatformAdmin(), 403, 'No se puede suspender a un administrador desde este panel.');

        $user->is_suspended = ! $user->is_suspended;
        $user->save();

        return back()->with('success', $user->is_suspended
            ? 'La cuenta fue suspendida.'
            : 'La cuenta fue reactivada.');
    }

    public function courts()
    {
        $canchas = Cancha::with('propietario')->latest()->paginate(20);

        return view('admin.moderacion.canchas', compact('canchas'));
    }

    public function updateCourt(Request $request, Cancha $cancha)
    {
        $validated = $request->validate([
            'activa' => ['required', 'boolean'],
        ]);

        $cancha->update([
            'activa' => $validated['activa'],
            'suspendida_admin' => ! $validated['activa'],
        ]);

        return back()->with('success', $validated['activa']
            ? 'La cancha fue publicada nuevamente.'
            : 'La cancha fue suspendida.');
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

        $partido->update(['estado' => $validated['estado']]);

        return back()->with('success', 'El estado del partido fue actualizado.');
    }
}
