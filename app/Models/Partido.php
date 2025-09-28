<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Partido extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'cancha_id',
        'organizador_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'nivel_juego',
        'jugadores_requeridos',
        'jugadores_confirmados',
        'costo_por_jugador',
        'costo_total',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
        'costo_por_jugador' => 'decimal:2',
        'costo_total' => 'decimal:2'
    ];

    // Relaciones
    public function cancha()
    {
        return $this->belongsTo(Cancha::class);
    }

    public function organizador()
    {
        return $this->belongsTo(User::class, 'organizador_id');
    }

    public function participantes()
    {
        return $this->belongsToMany(User::class, 'participantes_partidos', 'partido_id', 'jugador_id')
                    ->withPivot('estado', 'fecha_solicitud', 'fecha_confirmacion')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo')
                    ->where('fecha', '>=', now());
    }

    public function scopePorNivel($query, $nivel)
    {
        return $query->where('nivel_juego', $nivel);
    }

    public function scopePorUbicacion($query, $ubicacion)
    {
        return $query->whereHas('cancha', function($q) use ($ubicacion) {
            $q->where('nombre', 'like', '%' . $ubicacion . '%')
              ->orWhere('direccion', 'like', '%' . $ubicacion . '%');
        });
    }

    // Accessors
    public function getJugadoresFaltantesAttribute()
    {
        return $this->jugadores_requeridos - $this->jugadores_confirmados;
    }

    public function getEstaCompletoAttribute()
    {
        return $this->jugadores_confirmados >= $this->jugadores_requeridos;
    }

    public function getFechaFormateadaAttribute()
    {
        if ($this->fecha_hora_inicio) {
            return $this->fecha_hora_inicio->format('d/m/Y H:i');
        }
        return $this->fecha->format('d/m/Y');
    }

    public function getNivelFormateadoAttribute()
    {
        $niveles = [
            'casual' => 'Casual',
            'serio' => 'Serio'
        ];
        
        return $niveles[$this->nivel_juego] ?? $this->nivel_juego;
    }

    // Métodos
    public function puedeUnirse($userId)
    {
        return !$this->esta_completo && 
               !$this->participantes()->where('jugador_id', $userId)->exists() &&
               $this->organizador_id !== $userId;
    }

    public function puedeAbandonar($userId)
    {
        return $this->participantes()->where('jugador_id', $userId)->exists() &&
               $this->organizador_id !== $userId;
    }

    public function puedeEliminar($userId)
    {
        return $this->organizador_id === $userId;
    }
}
