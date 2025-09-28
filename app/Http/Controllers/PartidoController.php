<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Partido;
use App\Models\Cancha;
use Carbon\Carbon;

class PartidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Partido::with(['cancha', 'organizador'])
            ->where('fecha', '>=', now())
            ->where('estado', 'activo');

        // Filtros de búsqueda
        if ($request->filled('ubicacion')) {
            $query->whereHas('cancha', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->ubicacion . '%')
                  ->orWhere('direccion', 'like', '%' . $request->ubicacion . '%');
            });
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio_por_persona', '<=', $request->precio_max);
        }

        if ($request->filled('jugadores_faltantes')) {
            $query->whereRaw('(jugadores_necesarios - jugadores_confirmados) >= ?', [$request->jugadores_faltantes]);
        }

        $partidos = $query->orderBy('fecha', 'asc')->paginate(12);

        return view('buscar-partidos', compact('partidos'));
    }

    public function create()
    {
        $canchas = Cancha::where('activa', true)->get();
        return view('crear-partido', compact('canchas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date|after:now',
            'hora' => 'required',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
            'jugadores_necesarios' => 'required|integer|min:4|max:22',
            'precio_por_persona' => 'required|numeric|min:0',
            'equipamiento_incluido' => 'boolean',
            'contacto_whatsapp' => 'nullable|string|max:20'
        ]);

        $partido = Partido::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'cancha_id' => $request->cancha_id,
            'organizador_id' => Auth::id(),
            'fecha' => Carbon::parse($request->fecha . ' ' . $request->hora),
            'nivel' => $request->nivel,
            'jugadores_necesarios' => $request->jugadores_necesarios,
            'jugadores_confirmados' => 1, // El organizador se cuenta automáticamente
            'precio_por_persona' => $request->precio_por_persona,
            'equipamiento_incluido' => $request->has('equipamiento_incluido'),
            'contacto_whatsapp' => $request->contacto_whatsapp,
            'estado' => 'activo'
        ]);

        return redirect()->route('buscar-partidos')
            ->with('success', '¡Partido creado exitosamente! Ya puedes verlo en la lista.');
    }

    public function show($id)
    {
        $partido = Partido::with(['cancha', 'organizador', 'participantes'])->findOrFail($id);
        return view('ver-partido', compact('partido'));
    }

    public function join($id)
    {
        $partido = Partido::findOrFail($id);
        
        if ($partido->jugadores_confirmados >= $partido->jugadores_necesarios) {
            return back()->with('error', 'Este partido ya está completo.');
        }

        if ($partido->participantes()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Ya estás participando en este partido.');
        }

        $partido->participantes()->attach(Auth::id());
        $partido->increment('jugadores_confirmados');

        return back()->with('success', '¡Te has unido al partido exitosamente!');
    }

    public function leave($id)
    {
        $partido = Partido::findOrFail($id);
        
        if ($partido->organizador_id === Auth::id()) {
            return back()->with('error', 'No puedes abandonar un partido que organizas.');
        }

        $partido->participantes()->detach(Auth::id());
        $partido->decrement('jugadores_confirmados');

        return back()->with('success', 'Has abandonado el partido.');
    }

    public function destroy($id)
    {
        $partido = Partido::findOrFail($id);
        
        if ($partido->organizador_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para eliminar este partido.');
        }

        $partido->update(['estado' => 'cancelado']);
        
        return redirect()->route('buscar-partidos')
            ->with('success', 'Partido cancelado exitosamente.');
    }
}
