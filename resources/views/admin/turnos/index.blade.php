@extends('layouts.dashboard')

@section('title', 'Gestión de Turnos - FaltaUno')

@section('styles')
<style>
    .turno-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
        margin-bottom: 1rem;
    }
    
    .turno-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.15);
        border-color: var(--primary-green);
    }
    
    .turno-card .card-header {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        border-bottom: 2px solid var(--primary-green);
        border-radius: 10px 10px 0 0;
    }
    
    .turno-card .card-title {
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
    
    .badge-activo { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .badge-inactivo { background: linear-gradient(135deg, #6c757d, #495057); color: white; }
    .badge-destacado { background: linear-gradient(135deg, #ffc107, #ff8c00); color: white; }
    
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
    
    .table-turnos {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
    }
    
    .table-turnos thead th {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem 0.75rem;
    }
    
    .table-turnos tbody tr {
        transition: all 0.3s ease;
    }
    
    .table-turnos tbody tr:hover {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        transform: scale(1.01);
    }
    
    .table-turnos tbody td {
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
    
    .dias-semana {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .dia-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--light-green);
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
    }
    
    .horario-info {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 8px;
        padding: 0.75rem;
        border-left: 4px solid var(--primary-green);
    }
    
    .precio-info {
        text-align: right;
    }
    
    .precio-base {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-green);
    }
    
    .precio-pico, .precio-valle {
        font-size: 0.85rem;
        color: #6c757d;
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
                        <i class="fas fa-clock me-2"></i>Gestión de Turnos
                    </h1>
                    <p class="text-muted mb-0">Configura los horarios disponibles para tus canchas</p>
                </div>
                <a href="{{ route('admin.turnos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Turno
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold">Filtrar por Cancha:</label>
                <select class="form-select" id="filtro-cancha">
                    <option value="">Todas las canchas</option>
                    @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}" {{ $canchaId == $cancha->id ? 'selected' : '' }}>
                            {{ $cancha->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Filtrar por Día:</label>
                <select class="form-select" id="filtro-dia">
                    <option value="">Todos los días</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sabado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Filtrar por Estado:</label>
                <select class="form-select" id="filtro-estado">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="destacado">Destacados</option>
                </select>
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

    <!-- Lista de Turnos -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Turnos
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="exportarTurnos()">
                        <i class="fas fa-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="actualizarVista()">
                        <i class="fas fa-sync-alt me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($turnos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-turnos mb-0">
                        <thead>
                            <tr>
                                <th>Turno</th>
                                <th>Cancha</th>
                                <th>Día y Horario</th>
                                <th>Precios</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($turnos as $turno)
                                <tr>
                                    <td>
                                        <div>
                                            <strong class="text-primary">{{ $turno->nombre }}</strong>
                                            @if($turno->descripcion)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($turno->descripcion, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($turno->cancha->imagen_principal)
                                                <img src="{{ Storage::url($turno->cancha->imagen_principal) }}" 
                                                     alt="{{ $turno->cancha->nombre }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-futbol text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $turno->cancha->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $turno->cancha->ciudad }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="horario-info">
                                            <div class="dias-semana mb-2">
                                                <span class="dia-badge">{{ $turno->dia_semana }}</span>
                                            </div>
                                            <div>
                                                <strong>{{ $turno->hora_inicio_formateada }} - {{ $turno->hora_fin_formateada }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $turno->duracion_horas }}h de duración</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="precio-info">
                                            <div class="precio-base">${{ number_format($turno->precio_base, 0, ',', '.') }}</div>
                                            @if($turno->precio_pico)
                                                <div class="precio-pico">Pico: ${{ number_format($turno->precio_pico, 0, ',', '.') }}</div>
                                            @endif
                                            @if($turno->precio_valle)
                                                <div class="precio-valle">Valle: ${{ number_format($turno->precio_valle, 0, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $turno->cantidad_personas }}/{{ $turno->capacidad_maxima }}</strong>
                                            <br>
                                            <small class="text-muted">personas</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-estado badge-{{ $turno->activo ? 'activo' : 'inactivo' }}">
                                            {{ $turno->getEstadoTexto() }}
                                        </span>
                                        @if($turno->destacado)
                                            <br>
                                            <span class="badge-estado badge-destacado mt-1">
                                                Destacado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.turnos.show', $turno->id) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.turnos.edit', $turno->id) }}" 
                                               class="btn btn-outline-warning btn-sm" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.turnos.toggle-estado', $turno->id) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('¿Cambiar estado del turno?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $turno->activo ? 'secondary' : 'success' }} btn-sm" 
                                                        title="{{ $turno->activo ? 'Desactivar' : 'Activar' }}">
                                                    <i class="fas fa-{{ $turno->activo ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="eliminarTurno({{ $turno->id }})" 
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
                            Mostrando {{ $turnos->firstItem() }} a {{ $turnos->lastItem() }} de {{ $turnos->total() }} turnos
                        </div>
                        <div>
                            {{ $turnos->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay turnos configurados</h5>
                    <p class="text-muted">Configura los horarios disponibles para tus canchas.</p>
                    <a href="{{ route('admin.turnos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Crear Primer Turno
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
    const cancha = document.getElementById('filtro-cancha').value;
    const dia = document.getElementById('filtro-dia').value;
    const estado = document.getElementById('filtro-estado').value;
    
    // Construir URL con parámetros
    let url = new URL(window.location);
    url.searchParams.set('cancha_id', cancha);
    url.searchParams.set('dia', dia);
    url.searchParams.set('estado', estado);
    
    window.location.href = url.toString();
}

function limpiarFiltros() {
    document.getElementById('filtro-cancha').value = '';
    document.getElementById('filtro-dia').value = '';
    document.getElementById('filtro-estado').value = '';
    window.location.href = '{{ route("admin.turnos.index") }}';
}

function eliminarTurno(turnoId) {
    if (confirm('¿Estás seguro de que quieres eliminar este turno? Esta acción no se puede deshacer.')) {
        // Crear formulario para eliminar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/turnos/${turnoId}`;
        
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

function exportarTurnos() {
    // Implementar exportación de turnos
    alert('Función de exportación en desarrollo');
}

function actualizarVista() {
    window.location.reload();
}

// Auto-aplicar filtros al cambiar valores
document.getElementById('filtro-cancha').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-dia').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
</script>
@endpush
