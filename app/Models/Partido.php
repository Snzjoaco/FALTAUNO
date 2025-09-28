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
        'nivel',
        'jugadores_necesarios',
        'jugadores_confirmados',
        'precio_por_persona',
        'equipamiento_incluido',
        'contacto_whatsapp',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'equipamiento_incluido' => 'boolean',
        'precio_por_persona' => 'decimal:2'
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
        return $this->belongsToMany(User::class, 'participantes_partidos')
                    ->withPivot('fecha_inscripcion')
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
        return $query->where('nivel', $nivel);
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
        return $this->jugadores_necesarios - $this->jugadores_confirmados;
    }

    public function getEstaCompletoAttribute()
    {
        return $this->jugadores_confirmados >= $this->jugadores_necesarios;
    }

    public function getFechaFormateadaAttribute()
    {
        return $this->fecha->format('d/m/Y H:i');
    }

    public function getNivelFormateadoAttribute()
    {
        $niveles = [
            'principiante' => 'Principiante',
            'intermedio' => 'Intermedio',
            'avanzado' => 'Avanzado'
        ];
        
        return $niveles[$this->nivel] ?? $this->nivel;
    }

    // Métodos
    public function puedeUnirse($userId)
    {
        return !$this->esta_completo && 
               !$this->participantes()->where('user_id', $userId)->exists() &&
               $this->organizador_id !== $userId;
    }

    public function puedeAbandonar($userId)
    {
        return $this->participantes()->where('user_id', $userId)->exists() &&
               $this->organizador_id !== $userId;
    }

    public function puedeEliminar($userId)
    {
        return $this->organizador_id === $userId;
    }
}
