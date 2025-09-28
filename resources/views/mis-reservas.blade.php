@extends('layouts.app')

@section('title', 'Mis Reservas - FaltaUno')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                    Mis Reservas
                </h1>
                <a href="{{ route('reservar-canchas') }}" class="btn btn-primary-custom">
                    <i class="fas fa-plus me-2"></i>Nueva Reserva
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filtro-estado" class="form-label">Estado</label>
                            <select class="form-select" id="filtro-estado">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="filtro-fecha">
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-cancha" class="form-label">Cancha</label>
                            <select class="form-select" id="filtro-cancha">
                                <option value="">Todas</option>
                                <option value="1">Cancha 1</option>
                                <option value="2">Cancha 2</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-outline-custom w-100">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de reservas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(count($reservas ?? []) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Cancha</th>
                                                <th>Fecha</th>
                                                <th>Hora</th>
                                                <th>Estado</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservas ?? [] as $reserva)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-futbol me-2 text-primary"></i>
                                                        {{ $reserva->cancha->nombre ?? 'Cancha ' . $reserva->cancha_id }}
                                                    </div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                                                <td>{{ $reserva->turno->hora_inicio ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'pendiente' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($reserva->estado) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($reserva->precio_total, 0, ',', '.') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detalleReserva{{ $reserva->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if($reserva->estado === 'pendiente')
                                                        <button class="btn btn-sm btn-outline-danger" onclick="cancelarReserva({{ $reserva->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No tienes reservas</h4>
                                    <p class="text-muted">¡Reserva tu primera cancha y comienza a jugar!</p>
                                    <a href="{{ route('reservar-canchas') }}" class="btn btn-primary-custom">
                                        <i class="fas fa-plus me-2"></i>Hacer Reserva
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalle de reserva -->
@if(isset($reservas))
@foreach($reservas as $reserva)
<div class="modal fade" id="detalleReserva{{ $reserva->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <strong>Cancha:</strong><br>
                        {{ $reserva->cancha->nombre ?? 'Cancha ' . $reserva->cancha_id }}
                    </div>
                    <div class="col-6">
                        <strong>Fecha:</strong><br>
                        {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Hora:</strong><br>
                        {{ $reserva->turno->hora_inicio ?? 'N/A' }}
                    </div>
                    <div class="col-6">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-{{ $reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'pendiente' ? 'warning' : 'danger') }}">
                            {{ ucfirst($reserva->estado) }}
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Precio:</strong><br>
                        ${{ number_format($reserva->precio_total, 0, ',', '.') }}
                    </div>
                    <div class="col-6">
                        <strong>Duración:</strong><br>
                        {{ $reserva->turno->duracion ?? 'N/A' }} minutos
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if($reserva->estado === 'pendiente')
                <button type="button" class="btn btn-danger" onclick="cancelarReserva({{ $reserva->id }})">
                    Cancelar Reserva
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

@push('scripts')
<script>
function cancelarReserva(reservaId) {
    if (confirm('¿Estás seguro de que quieres cancelar esta reserva?')) {
        // Aquí iría la lógica para cancelar la reserva
        console.log('Cancelando reserva:', reservaId);
        // fetch('/mis-reservas/' + reservaId + '/cancelar', {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     }
        // }).then(response => {
        //     if (response.ok) {
        //         location.reload();
        //     }
        // });
    }
}
</script>
@endpush
@endsection
