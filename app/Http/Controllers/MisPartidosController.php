<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Partido;
use Carbon\Carbon;

class MisPartidosController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filtro = $request->get('filtro', 'proximos');
        
        // Partidos organizados por el usuario
        $partidosOrganizados = Partido::with(['cancha', 'participantes'])
            ->where('organizador_id', $userId);
        
        // Partidos donde el usuario participa
        $partidosParticipando = Partido::with(['cancha', 'organizador'])
            ->whereHas('participantes', function($query) use ($userId) {
                $query->where('jugador_id', $userId);
            });
        
        // Aplicar filtros según la selección
        switch ($filtro) {
            case 'proximos':
                $partidosOrganizados = $partidosOrganizados->where('fecha', '>=', now()->format('Y-m-d'));
                $partidosParticipando = $partidosParticipando->where('fecha', '>=', now()->format('Y-m-d'));
                break;
                
            case 'pasados':
                $partidosOrganizados = $partidosOrganizados->where('fecha', '<', now()->format('Y-m-d'));
                $partidosParticipando = $partidosParticipando->where('fecha', '<', now()->format('Y-m-d'));
                break;
                
            case 'organizo':
                // Solo partidos organizados, sin filtro de fecha
                $partidosParticipando = collect(); // Vacío para este filtro
                break;
                
            case 'pendientes':
                // Partidos donde el usuario participa pero no está confirmado
                $partidosParticipando = Partido::with(['cancha', 'organizador'])
                    ->whereHas('participantes', function($query) use ($userId) {
                        $query->where('jugador_id', $userId)
                              ->whereIn('estado', ['solicitado', 'aceptado']);
                    });
                $partidosOrganizados = collect(); // Vacío para este filtro
                break;
        }
        
        // Obtener resultados
        $partidosOrganizados = $partidosOrganizados->orderBy('fecha', 'desc')->get();
        $partidosParticipando = $partidosParticipando->orderBy('fecha', 'desc')->get();
        
        // Combinar y paginar
        $todosLosPartidos = $partidosOrganizados->concat($partidosParticipando)
            ->sortByDesc('fecha')
            ->values();
        
        // Paginación manual
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $todosLosPartidos->slice($offset, $perPage)->values();
        
        // Crear paginador manual
        $total = $todosLosPartidos->count();
        $lastPage = ceil($total / $perPage);
        
        $pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );
        
        $pagination->appends($request->query());
        
        return view('mis-partidos', compact('pagination', 'filtro'));
    }
    
    public function cancelarParticipacion($partidoId)
    {
        $partido = Partido::findOrFail($partidoId);
        $userId = Auth::id();
        
        // Verificar que el usuario participa en el partido
        if (!$partido->participantes()->where('jugador_id', $userId)->exists()) {
            return back()->with('error', 'No participas en este partido.');
        }
        
        // Verificar que no es el organizador
        if ($partido->organizador_id === $userId) {
            return back()->with('error', 'No puedes cancelar tu participación en un partido que organizas.');
        }
        
        // Cancelar participación
        $partido->participantes()->where('jugador_id', $userId)->detach();
        $partido->decrement('jugadores_confirmados');
        
        return back()->with('success', 'Has cancelado tu participación en el partido.');
    }
    
    public function cancelarPartido($partidoId)
    {
        $partido = Partido::findOrFail($partidoId);
        
        // Verificar que el usuario es el organizador
        if ($partido->organizador_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para cancelar este partido.');
        }
        
        // Cambiar estado a cancelado
        $partido->update(['estado' => 'cancelado']);
        
        return back()->with('success', 'Partido cancelado exitosamente.');
    }
}
