<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservaPublicaController extends Controller
{
    public function index(Request $request)
    {
        $canchas = $this->availableCourts()
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $search = $request->string('buscar')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('ciudad', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo_cancha', $request->tipo))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('reservas-publicas.index', compact('canchas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cancha_id' => ['required', 'integer'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'cantidad_personas' => ['required', 'integer', 'min:1', 'max:50'],
            'comentarios_cliente' => ['nullable', 'string', 'max:1000'],
        ]);

        $cancha = $this->availableCourts()->findOrFail($validated['cancha_id']);
        if ($validated['cantidad_personas'] > $cancha->capacidad_maxima) {
            return back()->withInput()->withErrors([
                'cantidad_personas' => 'La cantidad de personas supera la capacidad de esta cancha.',
            ]);
        }

        $inicio = Carbon::parse($validated['fecha'].' '.$validated['hora_inicio']);
        $fin = Carbon::parse($validated['fecha'].' '.$validated['hora_fin']);
        $duracionMinutos = $inicio->diffInMinutes($fin);

        if ($duracionMinutos < 60 || $duracionMinutos % 60 !== 0) {
            return back()->withInput()->withErrors([
                'hora_fin' => 'La reserva debe durar una o más horas completas.',
            ]);
        }

        $ahora = now();
        if ($inicio->lt($ahora->copy()->addHours($cancha->anticipacion_minima_horas))
            || $inicio->gt($ahora->copy()->addDays($cancha->anticipacion_maxima_dias))) {
            return back()->withInput()->withErrors([
                'fecha' => 'La fecha no respeta la anticipación mínima o máxima de esta cancha.',
            ]);
        }

        $reserva = DB::transaction(function () use ($validated, $cancha, $inicio, $fin, $duracionMinutos) {
            $cancha = $this->availableCourts()->whereKey($cancha->id)->lockForUpdate()->firstOrFail();

            if ($validated['cantidad_personas'] > $cancha->capacidad_maxima) {
                throw ValidationException::withMessages([
                    'cantidad_personas' => 'La cantidad de personas supera la capacidad de esta cancha.',
                ]);
            }

            $ahora = now();
            if ($inicio->lt($ahora->copy()->addHours($cancha->anticipacion_minima_horas))
                || $inicio->gt($ahora->copy()->addDays($cancha->anticipacion_maxima_dias))) {
                throw ValidationException::withMessages([
                    'fecha' => 'La fecha no respeta la anticipación mínima o máxima de esta cancha.',
                ]);
            }

            $ocupada = Reserva::where('cancha_id', $cancha->id)
                ->whereNotIn('estado', ['cancelada', 'reembolsada'])
                ->where('fecha_hora_inicio', '<', $fin)
                ->where('fecha_hora_fin', '>', $inicio)
                ->exists();

            if ($ocupada) {
                return null;
            }

            $precioTotal = (float) $cancha->precio_base * ($duracionMinutos / 60);

            return Reserva::create([
                'cancha_id' => $cancha->id,
                'usuario_id' => auth()->id(),
                'codigo_reserva' => 'RES-'.strtoupper(Str::random(8)),
                'titulo' => 'Reserva de '.$cancha->nombre,
                'tipo_reserva' => 'individual',
                'fecha' => $inicio->toDateString(),
                'hora_inicio' => $inicio->format('H:i:s'),
                'hora_fin' => $fin->format('H:i:s'),
                'fecha_hora_inicio' => $inicio,
                'fecha_hora_fin' => $fin,
                'duracion_horas' => (int) ($duracionMinutos / 60),
                'cantidad_personas' => $validated['cantidad_personas'],
                'precio_por_hora' => $cancha->precio_base,
                'precio_total' => $precioTotal,
                'monto_final' => $precioTotal,
                'estado' => 'pendiente',
                'metodo_pago' => 'efectivo',
                'estado_pago' => 'pendiente',
                'permite_cancelacion' => $cancha->permite_cancelacion,
                'fecha_limite_cancelacion' => $inicio->copy()->subHours($cancha->horas_cancelacion_gratuita),
                'porcentaje_penalidad' => $cancha->porcentaje_penalidad_cancelacion,
                'comentarios_cliente' => $validated['comentarios_cliente'] ?? null,
            ]);
        });

        if (! $reserva) {
            return back()->withInput()->withErrors([
                'hora_inicio' => 'Ese horario acaba de ser reservado. Elegí otro horario.',
            ]);
        }

        return redirect()->route('mis-reservas')
            ->with('success', 'Tu solicitud de reserva fue enviada. Código: '.$reserva->codigo_reserva);
    }

    public function mine()
    {
        $reservas = Reserva::with('cancha')
            ->where('usuario_id', auth()->id())
            ->latest('fecha_hora_inicio')
            ->paginate(15);

        return view('mis-reservas.index', compact('reservas'));
    }

    private function availableCourts()
    {
        return Cancha::query()
            ->where('activa', true)
            ->whereHas('propietario', function ($query) {
                $query->where('role', User::ROLE_COURT_OWNER)
                    ->where('is_suspended', false);
            });
    }
}
