@extends('layouts.dashboard')

@section('title', 'Reservas - FaltaUno')

@section('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(45, 90, 39, 0.2);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.3);
    }
    
    .stats-card .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .stats-card .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }
    
    .stats-card .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .reserva-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
    }
    
    .reserva-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.15);
        border-color: var(--primary-green);
    }
    
    .reserva-card .card-header {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        border-bottom: 2px solid var(--primary-green);
        border-radius: 10px 10px 0 0;
    }
    
    .reserva-card .card-title {
        color: var(--primary-green);
        font-weight: 700;
        margin: 0;
    }
    
    .badge-estado {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .badge-pendiente { background: linear-gradient(135deg, #ffc107, #ff8c00); color: white; }
    .badge-confirmada { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .badge-en-curso { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .badge-completada { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
    .badge-cancelada { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
    .badge-no-show { background: linear-gradient(135deg, #6c757d, #495057); color: white; }
    .badge-reembolsada { background: linear-gradient(135deg, #343a40, #212529); color: white; }
    
    .badge-pago-pendiente { background: linear-gradient(135deg, #ffc107, #ff8c00); color: white; }
    .badge-pago-pagado { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .badge-pago-reembolsado { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .badge-pago-disputado { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
    
    .filters-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border: none;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3);
    }
    
    .btn-outline-filter {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-filter:hover {
        background: var(--primary-green);
        color: white;
        transform: translateY(-1px);
    }
    
    .table-reservas {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
    }
    
    .table-reservas thead th {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem 0.75rem;
    }
    
    .table-reservas tbody tr {
        transition: all 0.3s ease;
    }
    
    .table-reservas tbody tr:hover {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        transform: scale(1.01);
    }
    
    .table-reservas tbody td {
        border-color: #e9ecef;
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    .action-buttons .btn {
        border-radius: 6px;
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
        margin: 0 0.125rem;
    }
    
    .pagination .page-link {
        color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .pagination .page-link:hover {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-calendar-check me-2"></i>Gestión de Reservas
                    </h1>
                    <p class="text-muted mb-0">Administra todas las reservas de tus canchas</p>
                </div>
                <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Reserva
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-number">{{ $estadisticas['total'] }}</div>
                        <div class="stats-label">Total Reservas</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-number">{{ $estadisticas['pendientes'] }}</div>
                        <div class="stats-label">Pendientes</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-number">{{ $estadisticas['completadas'] }}</div>
                        <div class="stats-label">Completadas</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-number">${{ number_format($estadisticas['ingresos_mes'], 0, ',', '.') }}</div>
                        <div class="stats-label">Ingresos del Mes</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold">Filtrar por Estado:</label>
                <select class="form-select" id="filtro-estado">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="confirmada">Confirmadas</option>
                    <option value="en_curso">En Curso</option>
                    <option value="completada">Completadas</option>
                    <option value="cancelada">Canceladas</option>
                    <option value="no_show">No Show</option>
                    <option value="reembolsada">Reembolsadas</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Filtrar por Fecha:</label>
                <input type="date" class="form-control" id="filtro-fecha">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Buscar:</label>
                <input type="text" class="form-control" id="filtro-buscar" placeholder="Código, título, cliente...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button class="btn btn-filter flex-fill" onclick="aplicarFiltros()">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <button class="btn btn-outline-filter" onclick="limpiarFiltros()">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Reservas -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Reservas
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="exportarReservas()">
                        <i class="fas fa-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="actualizarVista()">
                        <i class="fas fa-sync-alt me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($reservas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-reservas mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cancha</th>
                                <th>Cliente</th>
                                <th>Fecha y Hora</th>
                                <th>Estado</th>
                                <th>Pago</th>
                                <th>Monto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $reserva->codigo_reserva }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $reserva->titulo }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($reserva->cancha->imagen_principal)
                                                <img src="{{ Storage::url($reserva->cancha->imagen_principal) }}" 
                                                     alt="{{ $reserva->cancha->nombre }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-futbol text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $reserva->cancha->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $reserva->cancha->ciudad }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $reserva->usuario->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $reserva->usuario->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-estado badge-{{ str_replace('_', '-', $reserva->estado) }}">
                                            {{ ucfirst(str_replace('_', ' ', $reserva->estado)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-estado badge-pago-{{ str_replace('_', '-', $reserva->estado_pago) }}">
                                            {{ ucfirst(str_replace('_', ' ', $reserva->estado_pago)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-success">${{ number_format($reserva->monto_final, 0, ',', '.') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $reserva->duracion_horas }}h</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.reservas.show', $reserva->id) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.reservas.edit', $reserva->id) }}" 
                                               class="btn btn-outline-warning btn-sm" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="eliminarReserva({{ $reserva->id }})" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Mostrando {{ $reservas->firstItem() }} a {{ $reservas->lastItem() }} de {{ $reservas->total() }} reservas
                        </div>
                        <div>
                            {{ $reservas->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay reservas registradas</h5>
                    <p class="text-muted">Crea tu primera reserva para comenzar a gestionar tu negocio.</p>
                    <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Crear Primera Reserva
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function aplicarFiltros() {
    const estado = document.getElementById('filtro-estado').value;
    const fecha = document.getElementById('filtro-fecha').value;
    const buscar = document.getElementById('filtro-buscar').value;
    
    // Construir URL con parámetros
    let url = new URL(window.location);
    url.searchParams.set('estado', estado);
    url.searchParams.set('fecha', fecha);
    url.searchParams.set('buscar', buscar);
    
    window.location.href = url.toString();
}

function limpiarFiltros() {
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-fecha').value = '';
    document.getElementById('filtro-buscar').value = '';
    window.location.href = '{{ route("admin.reservas.index") }}';
}

function eliminarReserva(reservaId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta reserva? Esta acción no se puede deshacer.')) {
        // Crear formulario para eliminar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/reservas/${reservaId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportarReservas() {
    // Implementar exportación de reservas
    alert('Función de exportación en desarrollo');
}

function actualizarVista() {
    window.location.reload();
}

// Auto-aplicar filtros al cambiar valores
document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-fecha').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-buscar').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        aplicarFiltros();
    }
});
</script>
@endpush
