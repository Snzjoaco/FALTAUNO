<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancha_id',
        'usuario_id',
        'partido_id',
        'codigo_reserva',
        'titulo',
        'descripcion',
        'tipo_reserva',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'duracion_horas',
        'cantidad_personas',
        'cantidad_confirmados',
        'participantes',
        'precio_por_hora',
        'precio_total',
        'descuento',
        'monto_final',
        'moneda',
        'estado',
        'metodo_pago',
        'estado_pago',
        'referencia_pago',
        'fecha_pago',
        'permite_cancelacion',
        'fecha_limite_cancelacion',
        'porcentaje_penalidad',
        'motivo_cancelacion',
        'fecha_cancelacion',
        'notificacion_email',
        'notificacion_sms',
        'ultima_notificacion',
        'datos_adicionales',
        'notas_internas',
        'comentarios_cliente'
    ];

    protected $casts = [
        'participantes' => 'array',
        'datos_adicionales' => 'array',
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
        'fecha_pago' => 'datetime',
        'fecha_limite_cancelacion' => 'datetime',
        'fecha_cancelacion' => 'datetime',
        'ultima_notificacion' => 'datetime',
        'precio_por_hora' => 'decimal:2',
        'precio_total' => 'decimal:2',
        'descuento' => 'decimal:2',
        'monto_final' => 'decimal:2',
        'porcentaje_penalidad' => 'decimal:2',
        'permite_cancelacion' => 'boolean',
        'notificacion_email' => 'boolean',
        'notificacion_sms' => 'boolean'
    ];

    // Relaciones
    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    public function scopePorCancha($query, $canchaId)
    {
        return $query->where('cancha_id', $canchaId);
    }

    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    // Métodos de utilidad
    public function generarCodigoReserva(): string
    {
        return 'RES-' . strtoupper(uniqid());
    }

    public function calcularMontoFinal(): float
    {
        return $this->precio_total - $this->descuento;
    }

    public function puedeCancelar(): bool
    {
        if (!$this->permite_cancelacion) {
            return false;
        }

        if ($this->fecha_limite_cancelacion && now() > $this->fecha_limite_cancelacion) {
            return false;
        }

        return in_array($this->estado, ['pendiente', 'confirmada']);
    }

    public function getEstadoBadgeClass(): string
    {
        return match($this->estado) {
            'pendiente' => 'bg-warning',
            'confirmada' => 'bg-success',
            'en_curso' => 'bg-info',
            'completada' => 'bg-primary',
            'cancelada' => 'bg-danger',
            'no_show' => 'bg-secondary',
            'reembolsada' => 'bg-dark',
            default => 'bg-light'
        };
    }

    public function getEstadoPagoBadgeClass(): string
    {
        return match($this->estado_pago) {
            'pendiente' => 'bg-warning',
            'pagado' => 'bg-success',
            'reembolsado' => 'bg-info',
            'disputado' => 'bg-danger',
            default => 'bg-light'
        };
    }
}
