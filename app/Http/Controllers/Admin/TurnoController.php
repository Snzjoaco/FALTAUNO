<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $canchaId = $request->get('cancha_id');
        
        $query = Turno::with('cancha')
            ->whereHas('cancha', function($q) use ($usuario) {
                $q->where('propietario_id', $usuario->id);
            });
            
        if ($canchaId) {
            $query->where('cancha_id', $canchaId);
        }
        
        $turnos = $query->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->paginate(20);
            
        $canchas = Cancha::where('propietario_id', $usuario->id)
            ->where('activa', true)
            ->get();
            
        return view('admin.turnos.index', compact('turnos', 'canchas', 'canchaId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $usuario = Auth::user();
        $canchaId = $request->get('cancha_id');
        
        $canchas = Cancha::where('propietario_id', $usuario->id)
            ->where('activa', true)
            ->get();
            
        $cancha = $canchaId ? Cancha::find($canchaId) : $canchas->first();
        
        return view('admin.turnos.create', compact('canchas', 'cancha'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'duracion_minutos' => 'required|integer|min:30|max:480',
            'precio_base' => 'required|numeric|min:0',
            'precio_pico' => 'nullable|numeric|min:0',
            'precio_valle' => 'nullable|numeric|min:0',
            'capacidad_maxima' => 'required|integer|min:1|max:50',
            'capacidad_minima' => 'required|integer|min:1',
            'anticipacion_minima_horas' => 'required|integer|min:0|max:168',
            'anticipacion_maxima_dias' => 'required|integer|min:1|max:365',
            'horas_cancelacion_gratuita' => 'required|integer|min:0|max:168',
            'porcentaje_penalidad_cancelacion' => 'required|numeric|min:0|max:100',
            'fecha_inicio' => 'nullable|date|after_or_equal:today',
            'fecha_fin' => 'nullable|date|after:fecha_inicio'
        ]);

        // Verificar que la cancha pertenece al usuario
        $cancha = Cancha::where('id', $request->cancha_id)
            ->where('propietario_id', Auth::id())
            ->firstOrFail();

        // Calcular duración en minutos
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = Carbon::parse($request->hora_fin);
        $duracionMinutos = $horaInicio->diffInMinutes($horaFin);

        $turno = Turno::create([
            'cancha_id' => $request->cancha_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dia_semana' => $request->dia_semana,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'duracion_minutos' => $duracionMinutos,
            'activo' => $request->has('activo'),
            'recurrente' => $request->has('recurrente'),
            'capacidad_maxima' => $request->capacidad_maxima,
            'capacidad_minima' => $request->capacidad_minima,
            'precio_base' => $request->precio_base,
            'precio_pico' => $request->precio_pico,
            'precio_valle' => $request->precio_valle,
            'anticipacion_minima_horas' => $request->anticipacion_minima_horas,
            'anticipacion_maxima_dias' => $request->anticipacion_maxima_dias,
            'permite_cancelacion' => $request->has('permite_cancelacion'),
            'horas_cancelacion_gratuita' => $request->horas_cancelacion_gratuita,
            'porcentaje_penalidad_cancelacion' => $request->porcentaje_penalidad_cancelacion,
            'solo_miembros' => $request->has('solo_miembros'),
            'visible_publico' => $request->has('visible_publico'),
            'destacado' => $request->has('destacado'),
            'prioridad' => $request->prioridad ?? 0,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'restricciones_edad' => $request->restricciones_edad ? [
                'min' => $request->edad_minima,
                'max' => $request->edad_maxima
            ] : null,
            'requisitos_especiales' => $request->requisitos_especiales ? 
                explode(',', $request->requisitos_especiales) : null
        ]);

        return redirect()
            ->route('admin.turnos.index', ['cancha_id' => $turno->cancha_id])
            ->with('success', '¡Turno creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turno $turno)
    {
        // Verificar que el turno pertenece a una cancha del usuario
        $this->authorize('view', $turno);
        
        $turno->load('cancha');
        
        return view('admin.turnos.show', compact('turno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turno $turno)
    {
        $this->authorize('update', $turno);
        
        $usuario = Auth::user();
        $canchas = Cancha::where('propietario_id', $usuario->id)
            ->where('activa', true)
            ->get();
            
        return view('admin.turnos.edit', compact('turno', 'canchas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turno $turno)
    {
        $this->authorize('update', $turno);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'duracion_minutos' => 'required|integer|min:30|max:480',
            'precio_base' => 'required|numeric|min:0',
            'precio_pico' => 'nullable|numeric|min:0',
            'precio_valle' => 'nullable|numeric|min:0',
            'capacidad_maxima' => 'required|integer|min:1|max:50',
            'capacidad_minima' => 'required|integer|min:1',
            'anticipacion_minima_horas' => 'required|integer|min:0|max:168',
            'anticipacion_maxima_dias' => 'required|integer|min:1|max:365',
            'horas_cancelacion_gratuita' => 'required|integer|min:0|max:168',
            'porcentaje_penalidad_cancelacion' => 'required|numeric|min:0|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio'
        ]);

        // Recalcular duración
        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = Carbon::parse($request->hora_fin);
        $duracionMinutos = $horaInicio->diffInMinutes($horaFin);

        $turno->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dia_semana' => $request->dia_semana,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'duracion_minutos' => $duracionMinutos,
            'activo' => $request->has('activo'),
            'recurrente' => $request->has('recurrente'),
            'capacidad_maxima' => $request->capacidad_maxima,
            'capacidad_minima' => $request->capacidad_minima,
            'precio_base' => $request->precio_base,
            'precio_pico' => $request->precio_pico,
            'precio_valle' => $request->precio_valle,
            'anticipacion_minima_horas' => $request->anticipacion_minima_horas,
            'anticipacion_maxima_dias' => $request->anticipacion_maxima_dias,
            'permite_cancelacion' => $request->has('permite_cancelacion'),
            'horas_cancelacion_gratuita' => $request->horas_cancelacion_gratuita,
            'porcentaje_penalidad_cancelacion' => $request->porcentaje_penalidad_cancelacion,
            'solo_miembros' => $request->has('solo_miembros'),
            'visible_publico' => $request->has('visible_publico'),
            'destacado' => $request->has('destacado'),
            'prioridad' => $request->prioridad ?? 0,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'restricciones_edad' => $request->restricciones_edad ? [
                'min' => $request->edad_minima,
                'max' => $request->edad_maxima
            ] : null,
            'requisitos_especiales' => $request->requisitos_especiales ? 
                explode(',', $request->requisitos_especiales) : null
        ]);

        return redirect()
            ->route('admin.turnos.index', ['cancha_id' => $turno->cancha_id])
            ->with('success', '¡Turno actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turno $turno)
    {
        $this->authorize('delete', $turno);
        
        $canchaId = $turno->cancha_id;
        $turno->delete();

        return redirect()
            ->route('admin.turnos.index', ['cancha_id' => $canchaId])
            ->with('success', '¡Turno eliminado exitosamente!');
    }

    /**
     * Cambiar estado del turno
     */
    public function toggleEstado(Turno $turno)
    {
        $this->authorize('update', $turno);
        
        $turno->update(['activo' => !$turno->activo]);
        
        $estado = $turno->activo ? 'activado' : 'desactivado';
        
        return redirect()
            ->back()
            ->with('success', "Turno {$estado} exitosamente!");
    }

    /**
     * Obtener turnos por cancha (AJAX)
     */
    public function porCancha(Request $request)
    {
        $canchaId = $request->cancha_id;
        $dia = $request->dia;
        
        $usuario = Auth::user();
        
        $query = Turno::where('cancha_id', $canchaId)
            ->whereHas('cancha', function($q) use ($usuario) {
                $q->where('propietario_id', $usuario->id);
            });
            
        if ($dia) {
            $query->where('dia_semana', $dia);
        }
        
        $turnos = $query->orderBy('hora_inicio')->get();
        
        return response()->json($turnos);
    }
}
