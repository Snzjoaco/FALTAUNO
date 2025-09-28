<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancha_id',
        'nombre',
        'descripcion',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'activo',
        'recurrente',
        'capacidad_maxima',
        'capacidad_minima',
        'precio_base',
        'precio_pico',
        'precio_valle',
        'precios_especiales',
        'anticipacion_minima_horas',
        'anticipacion_maxima_dias',
        'permite_cancelacion',
        'horas_cancelacion_gratuita',
        'porcentaje_penalidad_cancelacion',
        'solo_miembros',
        'restricciones_edad',
        'requisitos_especiales',
        'visible_publico',
        'destacado',
        'prioridad',
        'fecha_inicio',
        'fecha_fin',
        'fechas_excluidas'
    ];

    protected $casts = [
        'precios_especiales' => 'array',
        'restricciones_edad' => 'array',
        'requisitos_especiales' => 'array',
        'fechas_excluidas' => 'array',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'recurrente' => 'boolean',
        'permite_cancelacion' => 'boolean',
        'solo_miembros' => 'boolean',
        'visible_publico' => 'boolean',
        'destacado' => 'boolean',
        'precio_base' => 'decimal:2',
        'precio_pico' => 'decimal:2',
        'precio_valle' => 'decimal:2',
        'porcentaje_penalidad_cancelacion' => 'decimal:2'
    ];

    // Relaciones
    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeVisibles($query)
    {
        return $query->where('visible_publico', true);
    }

    public function scopePorDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    public function scopePorCancha($query, $canchaId)
    {
        return $query->where('cancha_id', $canchaId);
    }

    public function scopeDisponibles($query, $fecha = null)
    {
        $fecha = $fecha ? Carbon::parse($fecha) : now();
        $diaSemana = strtolower($fecha->locale('es')->dayName);
        
        return $query->where('activo', true)
                    ->where('visible_publico', true)
                    ->where('dia_semana', $diaSemana)
                    ->where(function($q) use ($fecha) {
                        $q->whereNull('fecha_inicio')
                          ->orWhere('fecha_inicio', '<=', $fecha);
                    })
                    ->where(function($q) use ($fecha) {
                        $q->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', $fecha);
                    });
    }

    // Métodos de utilidad
    public function getDiaSemanaAttribute($value)
    {
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];
        
        return $dias[$value] ?? $value;
    }

    public function getHoraInicioFormateadaAttribute()
    {
        return Carbon::parse($this->hora_inicio)->format('H:i');
    }

    public function getHoraFinFormateadaAttribute()
    {
        return Carbon::parse($this->hora_fin)->format('H:i');
    }

    public function getDuracionHorasAttribute()
    {
        return $this->duracion_minutos / 60;
    }

    public function getPrecioParaFecha($fecha = null)
    {
        $fecha = $fecha ? Carbon::parse($fecha) : now();
        
        // Verificar precios especiales
        if ($this->precios_especiales) {
            foreach ($this->precios_especiales as $precioEspecial) {
                if ($fecha->format('Y-m-d') === $precioEspecial['fecha']) {
                    return $precioEspecial['precio'];
                }
            }
        }
        
        // Verificar si es horario pico o valle
        $hora = $fecha->hour;
        
        if ($this->precio_pico && $hora >= 18 && $hora <= 22) {
            return $this->precio_pico;
        }
        
        if ($this->precio_valle && $hora >= 8 && $hora <= 12) {
            return $this->precio_valle;
        }
        
        return $this->precio_base;
    }

    public function estaDisponible($fecha)
    {
        $fecha = Carbon::parse($fecha);
        $diaSemana = strtolower($fecha->locale('es')->dayName);
        
        // Verificar día de la semana
        if ($this->dia_semana !== $diaSemana) {
            return false;
        }
        
        // Verificar fechas de inicio y fin
        if ($this->fecha_inicio && $fecha->lt($this->fecha_inicio)) {
            return false;
        }
        
        if ($this->fecha_fin && $fecha->gt($this->fecha_fin)) {
            return false;
        }
        
        // Verificar fechas excluidas
        if ($this->fechas_excluidas && in_array($fecha->format('Y-m-d'), $this->fechas_excluidas)) {
            return false;
        }
        
        return $this->activo && $this->visible_publico;
    }

    public function getEstadoBadgeClass(): string
    {
        if (!$this->activo) {
            return 'bg-secondary';
        }
        
        if ($this->destacado) {
            return 'bg-warning';
        }
        
        return 'bg-success';
    }

    public function getEstadoTexto(): string
    {
        if (!$this->activo) {
            return 'Inactivo';
        }
        
        if ($this->destacado) {
            return 'Destacado';
        }
        
        return 'Activo';
    }
}
