<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
}
