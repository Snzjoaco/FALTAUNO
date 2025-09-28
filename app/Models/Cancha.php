<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    protected $fillable = [
        'propietario_id',
        'nombre',
        'descripcion',
        'telefono',
        'email',
        'sitio_web',
        'direccion',
        'ciudad',
        'barrio',
        'latitude',
        'longitude',
        'codigo_postal',
        'tipo_superficie',
        'tipo_cancha',
        'capacidad_maxima',
        'tiene_vestuarios',
        'tiene_estacionamiento',
        'tiene_iluminacion',
        'tiene_techado',
        'amenities',
        'rating',
        'total_resenas',
        'total_reservas',
        'precio_base',
        'precio_pico',
        'precio_valle',
        'precios_especiales',
        'anticipacion_minima_horas',
        'anticipacion_maxima_dias',
        'permite_cancelacion',
        'horas_cancelacion_gratuita',
        'porcentaje_penalidad_cancelacion',
        'activa',
        'verificada',
        'destacada',
        'fecha_verificacion',
        'imagenes',
        'imagen_principal',
        'horarios_semana',
        'horarios_fin_semana',
        'dias_cerrado'
    ];

    protected $casts = [
        'amenities' => 'array',
        'precios_especiales' => 'array',
        'imagenes' => 'array',
        'horarios_semana' => 'array',
        'horarios_fin_semana' => 'array',
        'dias_cerrado' => 'array',
        'tiene_vestuarios' => 'boolean',
        'tiene_estacionamiento' => 'boolean',
        'tiene_iluminacion' => 'boolean',
        'tiene_techado' => 'boolean',
        'activa' => 'boolean',
        'verificada' => 'boolean',
        'destacada' => 'boolean',
        'permite_cancelacion' => 'boolean',
        'fecha_verificacion' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'precio_base' => 'decimal:2',
        'precio_pico' => 'decimal:2',
        'precio_valle' => 'decimal:2',
        'porcentaje_penalidad_cancelacion' => 'decimal:2'
    ];

    // Relaciones
    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeVerificadas($query)
    {
        return $query->where('verificada', true);
    }

    public function scopeDestacadas($query)
    {
        return $query->where('destacadas', true);
    }

    // Métodos de utilidad
    public function getRatingFormateadoAttribute()
    {
        return number_format($this->rating, 1);
    }

    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->precio_base, 0, ',', '.');
    }

    public function getUbicacionCompletaAttribute()
    {
        $ubicacion = $this->ciudad;
        if ($this->barrio) {
            $ubicacion .= ', ' . $this->barrio;
        }
        return $ubicacion;
    }

    public function getEstadoAttribute()
    {
        if (!$this->activa) {
            return 'Inactiva';
        }
        
        if ($this->verificada) {
            return 'Verificada';
        }
        
        return 'Pendiente de verificación';
    }
}
