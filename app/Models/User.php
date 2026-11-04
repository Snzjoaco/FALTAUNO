<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\OwnerInvitationNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function sendPasswordResetNotification($token): void
    {
        if ($this->isCourtOwner() && $this->owner_status === 'invitado') {
            $this->notify(new OwnerInvitationNotification($token));

            return;
        }

        $this->notify(new ResetPassword($token));
    }

    public const ROLE_PLAYER = 'jugador';
    public const ROLE_COURT_OWNER = 'dueno_cancha';
    public const ROLE_PLATFORM_ADMIN = 'admin_plataforma';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'owner_status',
        'is_suspended',
        'billing_plan',
        'billing_amount',
        'billing_status',
        'owner_registered_at',
        'trial_started_at',
        'trial_ends_at',
        'next_charge_at',
        'last_paid_at',
        'first_name',
        'last_name',
        'phone',
        'city',
        'bio',
        'avatar',
        'preferred_position',
        'skill_level',
        'preferred_days',
        'preferred_time_slot',
        'preferred_zone',
        'jersey_preferences',
        'rating',
        'total_ratings',
        'games_played',
        'games_organized',
        'email_notifications',
        'match_reminders',
        'news_and_promos',
        'share_whatsapp',
        'is_verified',
        'latitude',
        'longitude',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'owner_registered_at' => 'datetime',
            'trial_started_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'next_charge_at' => 'datetime',
            'last_paid_at' => 'datetime',
            'billing_amount' => 'decimal:2',
        ];
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isPlatformAdmin(): bool
    {
        return $this->role === self::ROLE_PLATFORM_ADMIN;
    }

    public function isCourtOwner(): bool
    {
        return $this->role === self::ROLE_COURT_OWNER;
    }

    public function scopeCourtOwners(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_COURT_OWNER);
    }

    public function scopePubliclyEligible(Builder $query): Builder
    {
        return $query->courtOwners()
            ->where('owner_status', 'activo')
            ->where('is_suspended', false)
            ->where(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('billing_status', 'al_dia')
                        ->where(function (Builder $query) {
                            $query->whereNull('next_charge_at')
                                ->orWhereDate('next_charge_at', '>=', today());
                        });
                })->orWhereDate('trial_ends_at', '>=', today());
            });
    }

    public function getEffectiveBillingStatusAttribute(): string
    {
        if ($this->billing_status === 'vencido') {
            return 'vencido';
        }

        if ($this->trial_ends_at && $this->trial_ends_at->lt(today())
            && (!$this->next_charge_at || $this->next_charge_at->lt(today()))) {
            return 'vencido';
        }

        if ($this->next_charge_at && $this->next_charge_at->lt(today())) {
            return 'vencido';
        }

        return $this->billing_status ?? 'al_dia';
    }

    // Relaciones
    public function partidosOrganizados()
    {
        return $this->hasMany(Partido::class, 'organizador_id');
    }

    public function partidosParticipando()
    {
        return $this->belongsToMany(Partido::class, 'participantes_partidos', 'jugador_id', 'partido_id')
                    ->withPivot('estado', 'fecha_solicitud', 'fecha_confirmacion')
                    ->withTimestamps();
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function canchas()
    {
        return $this->hasMany(Cancha::class, 'propietario_id');
    }

    public function reservasDeCanchas()
    {
        return $this->hasManyThrough(Reserva::class, Cancha::class, 'propietario_id', 'cancha_id');
    }
}
