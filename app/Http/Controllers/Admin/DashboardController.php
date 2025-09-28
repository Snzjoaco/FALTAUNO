<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Obtener estadísticas del usuario
        $canchas = Cancha::where('propietario_id', $user->id)->get();
        
        // Estadísticas generales
        $totalCanchas = $canchas->count();
        $canchasActivas = $canchas->where('activa', true)->count();
        $canchasVerificadas = $canchas->where('verificada', true)->count();
        
        // Estadísticas de reservas (simuladas por ahora)
        $totalReservas = $canchas->sum('total_reservas');
        $reservasEsteMes = $canchas->sum('total_reservas'); // Simulado
        
        // Estadísticas de ingresos (simuladas)
        $ingresosEsteMes = $canchas->sum(function($cancha) {
            return $cancha->total_reservas * $cancha->precio_base;
        });
        
        // Rating promedio
        $ratingPromedio = $canchas->where('total_resenas', '>', 0)->avg('rating') ?? 0;
        
        // Canchas recientes
        $canchasRecientes = $canchas->sortByDesc('created_at')->take(5);
        
        // Gráfico de reservas por mes (simulado)
        $reservasPorMes = [];
        for ($i = 11; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $reservasPorMes[] = [
                'mes' => $fecha->format('M Y'),
                'reservas' => rand(10, 50) // Simulado
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalCanchas',
            'canchasActivas',
            'canchasVerificadas',
            'totalReservas',
            'reservasEsteMes',
            'ingresosEsteMes',
            'ratingPromedio',
            'canchasRecientes',
            'reservasPorMes'
        ));
    }
}
