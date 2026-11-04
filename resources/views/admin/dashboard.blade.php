@extends('layouts.dashboard')

@section('title', 'Dashboard - FaltaUno')
@section('page-title', 'Resumen general')

@section('content')
@push('styles')
<style>
    .dashboard-home {
        --dashboard-green: #2d5a27;
        --dashboard-green-soft: #4a7c59;
        --dashboard-orange: #ff8c42;
        --dashboard-ink: #26352a;
        --dashboard-muted: #68746b;
        --dashboard-border: #e4ebe3;
    }

    .dashboard-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        padding: clamp(1.5rem, 3vw, 2.5rem);
        margin-bottom: 1.75rem;
        border: 1px solid var(--dashboard-border);
        border-radius: 20px;
        background: linear-gradient(115deg, #fff 0%, #f0f8f0 100%);
    }

    .dashboard-welcome-copy {
        max-width: 650px;
    }

    .dashboard-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        color: var(--dashboard-green-soft);
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .dashboard-welcome h2 {
        margin: 0 0 0.5rem;
        color: var(--dashboard-ink);
        font-size: clamp(1.65rem, 3vw, 2.25rem);
        font-weight: 750;
        line-height: 1.2;
    }

    .dashboard-welcome p {
        margin: 0;
        color: var(--dashboard-muted);
        font-size: 1rem;
    }

    .dashboard-logo {
        display: block;
        width: 82px;
        height: 82px;
        object-fit: contain;
        flex: 0 0 auto;
        border-radius: 16px;
        box-shadow: 0 8px 18px rgba(45, 90, 39, 0.12);
    }

    .dashboard-primary-action {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 1.25rem;
        padding: 0.8rem 1.2rem;
        border: 0;
        border-radius: 10px;
        background: var(--dashboard-green);
        color: #fff;
        font-weight: 650;
        text-decoration: none;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .dashboard-primary-action:hover,
    .dashboard-primary-action:focus {
        background: var(--dashboard-green-soft);
        color: #fff;
        transform: translateY(-1px);
    }

    .dashboard-metrics {
        margin-bottom: 2rem;
    }

    .dashboard-metric {
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
        min-height: 112px;
        padding: 1.25rem;
        border: 1px solid var(--dashboard-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(38, 53, 42, 0.045);
    }

    .dashboard-metric-icon {
        display: grid;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        place-items: center;
        border-radius: 14px;
        background: #edf5ed;
        color: var(--dashboard-green);
        font-size: 1.2rem;
    }

    .dashboard-metric-icon.orange {
        background: #fff1e6;
        color: #c76520;
    }

    .dashboard-metric-value {
        display: block;
        color: var(--dashboard-ink);
        font-size: 1.75rem;
        font-weight: 750;
        line-height: 1.1;
    }

    .dashboard-metric-label {
        display: block;
        margin-top: 0.35rem;
        color: var(--dashboard-muted);
        font-size: 0.9rem;
    }

    .dashboard-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin: 0 0 1rem;
    }

    .dashboard-section-heading h3 {
        margin: 0;
        color: var(--dashboard-ink);
        font-size: 1.3rem;
        font-weight: 700;
    }

    .dashboard-section-heading p {
        margin: 0.3rem 0 0;
        color: var(--dashboard-muted);
    }

    .dashboard-text-link {
        color: var(--dashboard-green);
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
    }

    .dashboard-text-link:hover {
        color: var(--dashboard-green-soft);
        text-decoration: underline;
    }

    .dashboard-court-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--dashboard-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(38, 53, 42, 0.045);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .dashboard-court-card:hover {
        box-shadow: 0 10px 24px rgba(38, 53, 42, 0.09);
        transform: translateY(-2px);
    }

    .dashboard-court-main {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
    }

    .dashboard-court-image {
        display: grid;
        width: 60px;
        height: 60px;
        flex: 0 0 60px;
        place-items: center;
        overflow: hidden;
        border-radius: 13px;
        background: #edf5ed;
        color: var(--dashboard-green);
        font-size: 1.35rem;
    }

    .dashboard-court-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dashboard-court-info {
        min-width: 0;
        flex: 1;
    }

    .dashboard-court-info h4 {
        margin: 0 0 0.35rem;
        overflow-wrap: anywhere;
        color: var(--dashboard-ink);
        font-size: 1.05rem;
        font-weight: 700;
    }

    .dashboard-court-location,
    .dashboard-court-type {
        margin: 0;
        color: var(--dashboard-muted);
        font-size: 0.9rem;
    }

    .dashboard-court-location i {
        width: 1rem;
        color: var(--dashboard-green-soft);
    }

    .dashboard-court-status {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.8rem;
    }

    .dashboard-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.6rem;
        border-radius: 999px;
        background: #edf5ed;
        color: #2d6535;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .dashboard-status.inactive {
        background: #f0f1f0;
        color: #5f6861;
    }

    .dashboard-status.verified {
        background: #fff1e6;
        color: #a64e14;
    }

    .dashboard-court-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid #eef1ed;
        background: #fcfdfb;
    }

    .dashboard-court-rating {
        color: var(--dashboard-muted);
        font-size: 0.85rem;
    }

    .dashboard-court-rating i {
        color: var(--dashboard-orange);
    }

    .dashboard-court-actions {
        display: flex;
        gap: 0.5rem;
    }

    .dashboard-court-actions a {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.7rem;
        border: 1px solid var(--dashboard-border);
        border-radius: 8px;
        color: var(--dashboard-green);
        font-size: 0.82rem;
        font-weight: 650;
        text-decoration: none;
    }

    .dashboard-court-actions a:hover,
    .dashboard-court-actions a:focus {
        border-color: var(--dashboard-green);
        background: #edf5ed;
    }

    .dashboard-shortcuts {
        margin-top: 2rem;
    }

    .dashboard-shortcut {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        height: 100%;
        padding: 1rem;
        border: 1px solid var(--dashboard-border);
        border-radius: 14px;
        background: #fff;
        color: var(--dashboard-ink);
        text-decoration: none;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }

    .dashboard-shortcut:hover,
    .dashboard-shortcut:focus {
        border-color: var(--dashboard-green-soft);
        background: #f7fbf6;
        color: var(--dashboard-ink);
    }

    .dashboard-shortcut-icon {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        place-items: center;
        border-radius: 12px;
        background: #edf5ed;
        color: var(--dashboard-green);
    }

    .dashboard-shortcut strong,
    .dashboard-shortcut small {
        display: block;
    }

    .dashboard-shortcut small {
        margin-top: 0.2rem;
        color: var(--dashboard-muted);
    }

    .dashboard-empty {
        padding: 2.5rem 1.5rem;
        border: 1px dashed #cbd9c9;
        border-radius: 16px;
        background: #fbfdfb;
        text-align: center;
    }

    .dashboard-empty-icon {
        display: grid;
        width: 58px;
        height: 58px;
        margin: 0 auto 1rem;
        place-items: center;
        border-radius: 18px;
        background: #edf5ed;
        color: var(--dashboard-green);
        font-size: 1.4rem;
    }

    .dashboard-empty h4 {
        margin-bottom: 0.4rem;
        color: var(--dashboard-ink);
        font-weight: 700;
    }

    .dashboard-empty p {
        margin-bottom: 1rem;
        color: var(--dashboard-muted);
    }

    @media (max-width: 640px) {
        .dashboard-welcome {
            align-items: flex-start;
        }

        .dashboard-logo {
            width: 64px;
            height: 64px;
            border-radius: 13px;
        }

        .dashboard-section-heading {
            align-items: flex-start;
        }

        .dashboard-court-main {
            padding: 1rem;
        }

        .dashboard-court-footer {
            align-items: flex-start;
            flex-direction: column;
            padding: 0.85rem 1rem;
        }
    }
</style>
@endpush

<div class="dashboard-home">
    <section class="dashboard-welcome" aria-labelledby="dashboard-welcome-title">
        <div class="dashboard-welcome-copy">
            <span class="dashboard-eyebrow">
                <i class="fas fa-futbol" aria-hidden="true"></i>
                Tu espacio de gestión
            </span>
            <h2 id="dashboard-welcome-title">Hola, {{ auth()->user()->name }}. ¿Qué hacemos hoy?</h2>
            <p>Administra tus canchas, disponibilidad y reservas desde un solo lugar.</p>
            <a href="{{ route('admin.canchas.create') }}" class="dashboard-primary-action">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Agregar una cancha
            </a>
        </div>
        <img
            class="dashboard-logo"
            src="{{ asset('images/logos/logosmall.png') }}"
            alt="FaltaUno"
        >
    </section>

    <section class="dashboard-metrics" aria-label="Resumen de tus canchas">
        <div class="row g-3">
            <div class="col-md-4">
                <article class="dashboard-metric">
                    <span class="dashboard-metric-icon" aria-hidden="true">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <div>
                        <strong class="dashboard-metric-value">{{ $totalCanchas }}</strong>
                        <span class="dashboard-metric-label">Canchas registradas</span>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="dashboard-metric">
                    <span class="dashboard-metric-icon" aria-hidden="true">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div>
                        <strong class="dashboard-metric-value">{{ $canchasActivas }}</strong>
                        <span class="dashboard-metric-label">Canchas activas</span>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="dashboard-metric">
                    <span class="dashboard-metric-icon orange" aria-hidden="true">
                        <i class="fas fa-calendar-check"></i>
                    </span>
                    <div>
                        <strong class="dashboard-metric-value">{{ $totalReservas }}</strong>
                        <span class="dashboard-metric-label">Reservas registradas</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section aria-labelledby="dashboard-courts-title">
        <div class="dashboard-section-heading">
            <div>
                <h3 id="dashboard-courts-title">Tus canchas</h3>
                <p>Un vistazo rápido a las últimas que agregaste.</p>
            </div>
            <a href="{{ route('admin.canchas.index') }}" class="dashboard-text-link">
                Ver todas <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>
        </div>

        @if($canchasRecientes->count() > 0)
            <div class="row g-3">
                @foreach($canchasRecientes as $cancha)
                    <div class="col-xl-6">
                        <article class="dashboard-court-card">
                            <div class="dashboard-court-main">
                                <div class="dashboard-court-image" aria-hidden="true">
                                    @if($cancha->imagen_principal)
                                        <img src="{{ $cancha->imagen_principal }}" alt="">
                                    @else
                                        <i class="fas fa-futbol"></i>
                                    @endif
                                </div>
                                <div class="dashboard-court-info">
                                    <h4>{{ $cancha->nombre }}</h4>
                                    <p class="dashboard-court-location">
                                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                                        {{ $cancha->ciudad }}{{ $cancha->barrio ? ', ' . $cancha->barrio : '' }}
                                    </p>
                                    <p class="dashboard-court-type">
                                        {{ strtoupper($cancha->tipo_cancha) }} · {{ ucfirst(str_replace('_', ' ', $cancha->tipo_superficie)) }}
                                    </p>
                                    <div class="dashboard-court-status">
                                        @if($cancha->activa)
                                            <span class="dashboard-status">
                                                <i class="fas fa-check" aria-hidden="true"></i> Activa
                                            </span>
                                        @else
                                            <span class="dashboard-status inactive">
                                                <i class="fas fa-pause" aria-hidden="true"></i> Inactiva
                                            </span>
                                        @endif
                                        @if($cancha->verificada)
                                            <span class="dashboard-status verified">
                                                <i class="fas fa-check-circle" aria-hidden="true"></i> Verificada
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-court-footer">
                                <span class="dashboard-court-rating">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                    {{ number_format($cancha->rating, 1) }}
                                    <span class="ms-1">({{ $cancha->total_resenas }} reseñas)</span>
                                </span>
                                <div class="dashboard-court-actions">
                                    <a href="{{ route('admin.canchas.show', $cancha->id) }}" aria-label="Ver {{ $cancha->nombre }}">
                                        <i class="fas fa-eye" aria-hidden="true"></i> Ver
                                    </a>
                                    <a href="{{ route('admin.canchas.edit', $cancha->id) }}" aria-label="Editar {{ $cancha->nombre }}">
                                        <i class="fas fa-pen" aria-hidden="true"></i> Editar
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="dashboard-empty">
                <span class="dashboard-empty-icon" aria-hidden="true">
                    <i class="fas fa-futbol"></i>
                </span>
                <h4>Tu primera cancha empieza aquí</h4>
                <p>Agrega los datos de tu cancha para empezar a organizar horarios y reservas.</p>
                <a href="{{ route('admin.canchas.create') }}" class="dashboard-primary-action">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Agregar mi primera cancha
                </a>
            </div>
        @endif
    </section>

    <section class="dashboard-shortcuts" aria-labelledby="dashboard-shortcuts-title">
        <div class="dashboard-section-heading">
            <div>
                <h3 id="dashboard-shortcuts-title">Accesos rápidos</h3>
                <p>Ve directamente a lo que necesitas gestionar.</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('admin.canchas.index') }}" class="dashboard-shortcut">
                    <span class="dashboard-shortcut-icon" aria-hidden="true"><i class="fas fa-list"></i></span>
                    <span><strong>Mis canchas</strong><small>Revisar y editar canchas</small></span>
                </a>
            </div>
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('admin.turnos.index') }}" class="dashboard-shortcut">
                    <span class="dashboard-shortcut-icon" aria-hidden="true"><i class="fas fa-clock"></i></span>
                    <span><strong>Horarios y turnos</strong><small>Configurar disponibilidad</small></span>
                </a>
            </div>
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('admin.reservas.index') }}" class="dashboard-shortcut">
                    <span class="dashboard-shortcut-icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
                    <span><strong>Reservas</strong><small>Consultar las reservas recibidas</small></span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
