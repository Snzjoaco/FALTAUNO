@extends('layouts.dashboard')

@section('title', 'Dashboard - FaltaUno')
@section('page-title', 'Panel de Administración')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">¡Bienvenido, {{ auth()->user()->name }}!</h2>
                <p class="text-muted mb-0">Aquí tienes un resumen de tus canchas y estadísticas</p>
            </div>
            <div>
                <a href="{{ route('admin.canchas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Agregar Cancha
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon primary">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3>{{ $totalCanchas }}</h3>
            <p>Total de Canchas</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>{{ $canchasActivas }}</h3>
            <p>Canchas Activas</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon warning">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3>{{ $totalReservas }}</h3>
            <p>Total Reservas</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="icon info">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3>${{ number_format($ingresosEsteMes, 0, ',', '.') }}</h3>
            <p>Ingresos Este Mes</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Canchas Recientes -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Canchas Recientes
                </h5>
                <a href="{{ route('admin.canchas.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver Todas
                </a>
            </div>
            <div class="card-body">
                @if($canchasRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cancha</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Rating</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($canchasRecientes as $cancha)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($cancha->imagen_principal)
                                                <img src="{{ $cancha->imagen_principal }}" alt="{{ $cancha->nombre }}" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-futbol text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $cancha->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $cancha->tipo_cancha }} - {{ $cancha->tipo_superficie }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $cancha->ciudad }}, {{ $cancha->barrio }}</small>
                                    </td>
                                    <td>
                                        @if($cancha->activa)
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Inactiva</span>
                                        @endif
                                        
                                        @if($cancha->verificada)
                                            <span class="badge bg-primary ms-1">Verificada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            <span>{{ number_format($cancha->rating, 1) }}</span>
                                            <small class="text-muted ms-1">({{ $cancha->total_resenas }})</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.canchas.show', $cancha->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.canchas.edit', $cancha->id) }}" 
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes canchas registradas</h5>
                        <p class="text-muted">Comienza agregando tu primera cancha</p>
                        <a href="{{ route('admin.canchas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Agregar Primera Cancha
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Panel de Acciones Rápidas -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.canchas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Agregar Nueva Cancha
                    </a>
                    <a href="{{ route('admin.canchas.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Gestionar Canchas
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-calendar-alt me-2"></i>Ver Reservas
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-chart-line me-2"></i>Ver Reportes
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas Adicionales -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success">{{ $canchasVerificadas }}</h4>
                        <small class="text-muted">Verificadas</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ number_format($ratingPromedio, 1) }}</h4>
                        <small class="text-muted">Rating Promedio</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Reservas este mes:</span>
                    <strong>{{ $reservasEsteMes }}</strong>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span>Ingresos este mes:</span>
                    <strong class="text-success">${{ number_format($ingresosEsteMes, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Reservas (simulado) -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Reservas por Mes
                </h5>
            </div>
            <div class="card-body">
                <canvas id="reservasChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de reservas por mes
    const ctx = document.getElementById('reservasChart').getContext('2d');
    const reservasData = @json($reservasPorMes);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: reservasData.map(item => item.mes),
            datasets: [{
                label: 'Reservas',
                data: reservasData.map(item => item.reservas),
                borderColor: '#2d5a27',
                backgroundColor: 'rgba(45, 90, 39, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });
</script>
@endpush
