@extends('layouts.dashboard')

@section('title', 'Mis Canchas - FaltaUno')

@section('styles')
<style>
    .cancha-card {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
        position: relative;
    }
    
    .cancha-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.2);
        border-color: var(--primary-green);
    }
    
    .cancha-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
        border-radius: 12px 12px 0 0;
    }
    
    .cancha-card .card-img-top {
        border-radius: 10px 10px 0 0;
        transition: transform 0.3s ease;
    }
    
    .cancha-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .badge-sm {
        font-size: 0.6rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }
    
    .cancha-card .card-body {
        padding: 0.75rem;
        height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    .cancha-card .btn-sm {
        font-size: 0.7rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    
    .cancha-card .btn-sm:hover {
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3);
    }
    
    .cancha-card .card-title {
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.2;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .cancha-card .text-muted {
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    .cancha-card .text-primary {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary-green);
    }
    
    .cancha-card .badge {
        border-radius: 6px;
        font-weight: 600;
    }
    
    .cancha-card .badge.bg-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    
    .cancha-card .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #495057) !important;
    }
    
    .cancha-card .badge.bg-info {
        background: linear-gradient(135deg, #17a2b8, #138496) !important;
    }
    
    /* Estilos para el modal */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(45, 90, 39, 0.2);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border-radius: 15px 15px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }
    
    .modal-header .btn-close {
        filter: invert(1);
    }
    
    .modal-body {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 1.5rem;
    }
    
    .modal-footer {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-top: 1px solid #e9ecef;
        border-radius: 0 0 15px 15px;
    }
    
    .modal-footer .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
    }
    
    .modal-footer .btn-primary {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border: none;
    }
    
    .modal-footer .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3);
    }
    
    .modal-footer .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
    }
    
    .modal-footer .btn-secondary:hover {
        background: linear-gradient(135deg, #495057, #6c757d);
        transform: translateY(-1px);
    }
    
    /* Estilos para las cards dentro del modal */
    .modal-body .card {
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
        transition: all 0.3s ease;
    }
    
    .modal-body .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(45, 90, 39, 0.15);
    }
    
    .modal-body .card-header {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        border-bottom: 2px solid var(--primary-green);
        color: var(--primary-green);
        font-weight: 700;
        border-radius: 10px 10px 0 0;
    }
    
    .modal-body .card-body {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    /* Estilos para la tabla de reservas */
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
    }
    
    .table thead th {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem 0.75rem;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        transform: scale(1.01);
    }
    
    .table tbody td {
        border-color: #e9ecef;
        padding: 0.75rem;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, buscando botones expandir...');
    
    // Manejar clic en botón expandir
    const expandButtons = document.querySelectorAll('.expand-btn');
    console.log('Botones encontrados:', expandButtons.length);
    
    expandButtons.forEach((button, index) => {
        console.log(`Configurando botón ${index + 1}:`, button);
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botón expandir clickeado');
            const canchaId = this.getAttribute('data-cancha-id');
            console.log('ID de cancha:', canchaId);
            
            // Test simple primero
            alert('Botón funcionando! ID: ' + canchaId);
            
            // Mostrar modal directamente sin depender de Bootstrap
            showModal(canchaId);
        });
    });
});

function showModal(canchaId) {
    console.log('Mostrando modal para cancha:', canchaId);
    
    // Verificar que el modal exista
    const modalElement = document.getElementById('canchaModal');
    if (!modalElement) {
        console.error('Modal no encontrado');
        alert('Error: Modal no encontrado');
        return;
    }
    
    // Mostrar modal usando Bootstrap si está disponible, sino usar método manual
    if (typeof bootstrap !== 'undefined') {
        console.log('Usando Bootstrap para mostrar modal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        console.log('Bootstrap no disponible, mostrando modal manualmente');
        // Mostrar modal manualmente
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Agregar backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modal-backdrop';
        document.body.appendChild(backdrop);
    }
    
    // Cargar datos
    loadCanchaDetails(canchaId);
}

function loadCanchaDetails(canchaId) {
    console.log('Cargando detalles de cancha:', canchaId);
    
    // Mostrar loading
    document.getElementById('cancha-info').innerHTML = `
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando información de la cancha...</p>
        </div>
    `;
    
    document.getElementById('reservas-table').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando reservas...</p>
        </div>
    `;
    
    console.log('Haciendo petición AJAX...');
    
    // Hacer petición AJAX para obtener datos de la cancha
    fetch(`/admin/canchas/${canchaId}/details`)
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            displayCanchaInfo(data);
            displayReservas(data.reservas || []);
            document.getElementById('edit-cancha-btn').href = `/admin/canchas/${canchaId}/edit`;
        })
        .catch(error => {
            console.error('Error en petición AJAX:', error);
            document.getElementById('cancha-info').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar la información de la cancha: ${error.message}
                    </div>
                </div>
            `;
        });
}

function displayCanchaInfo(data) {
    const cancha = data.cancha;
    document.getElementById('cancha-info').innerHTML = `
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información Básica</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> ${cancha.nombre}</p>
                    <p><strong>Descripción:</strong> ${cancha.descripcion || 'Sin descripción'}</p>
                    <p><strong>Tipo:</strong> <span class="badge bg-info">${cancha.tipo_cancha.toUpperCase()}</span></p>
                    <p><strong>Superficie:</strong> <span class="badge bg-secondary">${cancha.tipo_superficie.replace('_', ' ')}</span></p>
                    <p><strong>Capacidad:</strong> ${cancha.capacidad_maxima} jugadores</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Ubicación</h6>
                </div>
                <div class="card-body">
                    <p><strong>Dirección:</strong> ${cancha.direccion}</p>
                    <p><strong>Ciudad:</strong> ${cancha.ciudad}</p>
                    <p><strong>Barrio:</strong> ${cancha.barrio || 'No especificado'}</p>
                    <p><strong>Código Postal:</strong> ${cancha.codigo_postal || 'No especificado'}</p>
                    <p><strong>Coordenadas:</strong> ${cancha.latitude}, ${cancha.longitude}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Precios y Estadísticas</h6>
                </div>
                <div class="card-body">
                    <p><strong>Precio Base:</strong> $${Number(cancha.precio_base).toLocaleString()}</p>
                    <p><strong>Precio Pico:</strong> ${cancha.precio_pico ? '$' + Number(cancha.precio_pico).toLocaleString() : 'No definido'}</p>
                    <p><strong>Precio Valle:</strong> ${cancha.precio_valle ? '$' + Number(cancha.precio_valle).toLocaleString() : 'No definido'}</p>
                    <p><strong>Rating:</strong> <span class="text-warning">${cancha.rating} ⭐</span></p>
                    <p><strong>Total Reservas:</strong> ${cancha.total_reservas}</p>
                    <p><strong>Estado:</strong> 
                        <span class="badge ${cancha.activa ? 'bg-success' : 'bg-secondary'}">
                            ${cancha.activa ? 'Activa' : 'Inactiva'}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    `;
}

function displayReservas(reservas) {
    if (reservas.length === 0) {
        document.getElementById('reservas-table').innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay reservas registradas</h5>
                <p class="text-muted">Las reservas aparecerán aquí cuando los usuarios reserven esta cancha.</p>
            </div>
        `;
        return;
    }
    
    let reservasHtml = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    reservas.forEach(reserva => {
        reservasHtml += `
            <tr>
                <td>${new Date(reserva.fecha).toLocaleDateString()}</td>
                <td>${reserva.hora_inicio} - ${reserva.hora_fin}</td>
                <td>${reserva.usuario_nombre || 'Usuario'}</td>
                <td>
                    <span class="badge ${getReservaStatusBadge(reserva.estado)}">
                        ${reserva.estado}
                    </span>
                </td>
                <td>$${Number(reserva.precio_total).toLocaleString()}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="viewReserva(${reserva.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    reservasHtml += `
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('reservas-table').innerHTML = reservasHtml;
}

function getReservaStatusBadge(estado) {
    switch(estado) {
        case 'confirmada': return 'bg-success';
        case 'pendiente': return 'bg-warning';
        case 'cancelada': return 'bg-danger';
        case 'completada': return 'bg-info';
        default: return 'bg-secondary';
    }
}

function viewReserva(reservaId) {
    // Implementar vista de reserva individual
    alert('Ver reserva: ' + reservaId);
}
</script>
@endpush
@section('page-title', 'Mis Canchas')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Mis Canchas</h2>
                <p class="text-muted mb-0">Gestiona todas tus canchas registradas</p>
            </div>
            <div>
                <a href="{{ route('admin.canchas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Agregar Cancha
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.canchas.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="buscar" name="buscar" 
                               value="{{ request('buscar') }}" placeholder="Nombre de la cancha...">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="activas" {{ request('estado') === 'activas' ? 'selected' : '' }}>Activas</option>
                            <option value="inactivas" {{ request('estado') === 'inactivas' ? 'selected' : '' }}>Inactivas</option>
                            <option value="verificadas" {{ request('estado') === 'verificadas' ? 'selected' : '' }}>Verificadas</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="tipo">
                            <option value="">Todos</option>
                            <option value="f5" {{ request('tipo') === 'f5' ? 'selected' : '' }}>F5</option>
                            <option value="f7" {{ request('tipo') === 'f7' ? 'selected' : '' }}>F7</option>
                            <option value="f11" {{ request('tipo') === 'f11' ? 'selected' : '' }}>F11</option>
                            <option value="mixto" {{ request('tipo') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" 
                               value="{{ request('ciudad') }}" placeholder="Ciudad...">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.canchas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Canchas -->
<div class="row">
    @if($canchas->count() > 0)
        @foreach($canchas as $cancha)
        <div class="col-lg-3 col-xl-2 mb-3">
            <div class="card cancha-card" id="cancha-{{ $cancha->id }}">
                <!-- Imagen de la cancha -->
                <div class="position-relative">
                    @if($cancha->imagen_principal)
                        <img src="{{ Storage::url($cancha->imagen_principal) }}" 
                             class="card-img-top" style="height: 80px; object-fit: cover;" 
                             alt="{{ $cancha->nombre }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 80px;">
                            <i class="fas fa-futbol text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Badge de estado -->
                    <div class="position-absolute top-0 end-0 p-1">
                        @if($cancha->activa)
                            <span class="badge bg-success badge-sm">Activa</span>
                        @else
                            <span class="badge bg-secondary badge-sm">Inactiva</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body p-2">
                    <h6 class="card-title mb-1 text-truncate" style="font-size: 0.9rem;">{{ $cancha->nombre }}</h6>
                    
                    <div class="mb-1">
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $cancha->ciudad }}
                        </small>
                    </div>
                    
                    <div class="mb-2">
                        <span class="badge bg-info badge-sm me-1" style="font-size: 0.6rem;">{{ strtoupper($cancha->tipo_cancha) }}</span>
                    </div>
                    
                    <div class="text-center mb-2">
                        <div class="text-primary fw-bold" style="font-size: 0.9rem;">${{ number_format($cancha->precio_base, 0, ',', '.') }}</div>
                        <small class="text-muted" style="font-size: 0.7rem;">Precio/hora</small>
                    </div>
                    
                    <div class="d-grid">
                        <button class="btn btn-primary btn-sm expand-btn" 
                                data-cancha-id="{{ $cancha->id }}"
                                style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <i class="fas fa-expand-alt me-1"></i>Ampliar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        <!-- Paginación -->
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $canchas->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-map-marker-alt fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">No tienes canchas registradas</h3>
                <p class="text-muted mb-4">Comienza agregando tu primera cancha para empezar a recibir reservas</p>
                <a href="{{ route('admin.canchas.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Agregar Primera Cancha
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Modal para información expandida -->
<div class="modal fade" id="canchaModal" tabindex="-1" aria-labelledby="canchaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="canchaModalLabel">
                    <i class="fas fa-futbol me-2"></i>Información de la Cancha
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información de la Cancha -->
                <div id="cancha-info" class="row mb-4">
                    <!-- Se llenará dinámicamente -->
                </div>
                
                <!-- Tabla de Reservas -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Reservas Registradas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="reservas-table">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="edit-cancha-btn" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Editar Cancha
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush
