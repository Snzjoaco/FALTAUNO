<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener todas las reservas de las canchas del usuario
        $canchasIds = Cancha::where('propietario_id', $usuario->id)->pluck('id');
        
        $reservas = Reserva::with(['cancha', 'usuario'])
            ->whereIn('cancha_id', $canchasIds)
            ->orderBy('fecha_hora_inicio', 'desc')
            ->paginate(15);

        // Estadísticas
        $estadisticas = [
            'total' => $reservas->total(),
            'pendientes' => Reserva::whereIn('cancha_id', $canchasIds)->pendientes()->count(),
            'confirmadas' => Reserva::whereIn('cancha_id', $canchasIds)->confirmadas()->count(),
            'completadas' => Reserva::whereIn('cancha_id', $canchasIds)->completadas()->count(),
            'canceladas' => Reserva::whereIn('cancha_id', $canchasIds)->canceladas()->count(),
            'ingresos_mes' => Reserva::whereIn('cancha_id', $canchasIds)
                ->where('estado', 'completada')
                ->whereMonth('fecha', now()->month)
                ->sum('monto_final')
        ];

        return view('admin.reservas.index', compact('reservas', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuario = Auth::user();
        $canchas = Cancha::where('propietario_id', $usuario->id)
            ->where('activa', true)
            ->get();

        return view('admin.reservas.create', compact('canchas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cantidad_personas' => 'required|integer|min:1|max:20',
            'precio_por_hora' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,mercadopago,tarjeta',
            'comentarios_cliente' => 'nullable|string'
        ]);

        // Verificar que la cancha pertenece al usuario
        $cancha = Cancha::where('id', $request->cancha_id)
            ->where('propietario_id', Auth::id())
            ->firstOrFail();

        // Calcular fechas y montos
        $fechaHoraInicio = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora_inicio);
        $fechaHoraFin = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora_fin);
        $duracionHoras = $fechaHoraInicio->diffInHours($fechaHoraFin);
        $precioTotal = $request->precio_por_hora * $duracionHoras;
        $montoFinal = $precioTotal - ($request->descuento ?? 0);

        $reserva = Reserva::create([
            'cancha_id' => $request->cancha_id,
            'usuario_id' => Auth::id(), // El propietario crea la reserva
            'codigo_reserva' => 'RES-' . strtoupper(Str::random(8)),
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tipo_reserva' => $request->tipo_reserva ?? 'individual',
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_hora_inicio' => $fechaHoraInicio,
            'fecha_hora_fin' => $fechaHoraFin,
            'duracion_horas' => $duracionHoras,
            'cantidad_personas' => $request->cantidad_personas,
            'precio_por_hora' => $request->precio_por_hora,
            'precio_total' => $precioTotal,
            'descuento' => $request->descuento ?? 0,
            'monto_final' => $montoFinal,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'pendiente',
            'estado_pago' => 'pendiente',
            'permite_cancelacion' => $cancha->permite_cancelacion,
            'fecha_limite_cancelacion' => $fechaHoraInicio->subHours($cancha->horas_cancelacion_gratuita),
            'porcentaje_penalidad' => $cancha->porcentaje_penalidad_cancelacion,
            'comentarios_cliente' => $request->comentarios_cliente
        ]);

        return redirect()
            ->route('admin.reservas.show', $reserva->id)
            ->with('success', '¡Reserva creada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        // Verificar que la reserva pertenece a una cancha del usuario
        $this->authorize('view', $reserva);
        
        $reserva->load(['cancha', 'usuario']);
        
        return view('admin.reservas.show', compact('reserva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        $this->authorize('update', $reserva);
        
        $usuario = Auth::user();
        $canchas = Cancha::where('propietario_id', $usuario->id)
            ->where('activa', true)
            ->get();

        return view('admin.reservas.edit', compact('reserva', 'canchas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $this->authorize('update', $reserva);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'cantidad_personas' => 'required|integer|min:1|max:20',
            'precio_por_hora' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,mercadopago,tarjeta',
            'estado' => 'required|in:pendiente,confirmada,en_curso,completada,cancelada,no_show,reembolsada',
            'estado_pago' => 'required|in:pendiente,pagado,reembolsado,disputado',
            'comentarios_cliente' => 'nullable|string',
            'notas_internas' => 'nullable|string'
        ]);

        // Recalcular fechas y montos si es necesario
        $fechaHoraInicio = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora_inicio);
        $fechaHoraFin = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora_fin);
        $duracionHoras = $fechaHoraInicio->diffInHours($fechaHoraFin);
        $precioTotal = $request->precio_por_hora * $duracionHoras;
        $montoFinal = $precioTotal - ($request->descuento ?? $reserva->descuento);

        $reserva->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_hora_inicio' => $fechaHoraInicio,
            'fecha_hora_fin' => $fechaHoraFin,
            'duracion_horas' => $duracionHoras,
            'cantidad_personas' => $request->cantidad_personas,
            'precio_por_hora' => $request->precio_por_hora,
            'precio_total' => $precioTotal,
            'monto_final' => $montoFinal,
            'metodo_pago' => $request->metodo_pago,
            'estado' => $request->estado,
            'estado_pago' => $request->estado_pago,
            'comentarios_cliente' => $request->comentarios_cliente,
            'notas_internas' => $request->notas_internas
        ]);

        return redirect()
            ->route('admin.reservas.show', $reserva->id)
            ->with('success', '¡Reserva actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $this->authorize('delete', $reserva);
        
        $reserva->delete();

        return redirect()
            ->route('admin.reservas.index')
            ->with('success', '¡Reserva eliminada exitosamente!');
    }

    /**
     * Cambiar estado de la reserva
     */
    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $this->authorize('update', $reserva);

        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,en_curso,completada,cancelada,no_show,reembolsada'
        ]);

        $reserva->update(['estado' => $request->estado]);

        return redirect()
            ->back()
            ->with('success', 'Estado de la reserva actualizado exitosamente!');
    }

    /**
     * Obtener reservas por fecha (AJAX)
     */
    public function porFecha(Request $request)
    {
        $fecha = $request->fecha;
        $canchaId = $request->cancha_id;
        
        $usuario = Auth::user();
        $canchasIds = Cancha::where('propietario_id', $usuario->id)->pluck('id');
        
        $reservas = Reserva::with(['cancha', 'usuario'])
            ->whereIn('cancha_id', $canchasIds)
            ->where('fecha', $fecha);
            
        if ($canchaId) {
            $reservas->where('cancha_id', $canchaId);
        }
        
        return response()->json($reservas->get());
    }
}
