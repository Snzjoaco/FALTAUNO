@extends('layouts.dashboard')

@section('title', 'Detalles de Cancha - FaltaUno')
@section('page-title', 'Detalles de Cancha')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>{{ $cancha->nombre }}</h2>
                <p class="text-muted mb-0">{{ $cancha->direccion }}, {{ $cancha->ciudad }}</p>
            </div>
            <div>
                <a href="{{ route('admin.canchas.edit', $cancha->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('admin.canchas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información General
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Nombre:</strong>
                        <p>{{ $cancha->nombre }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tipo de Cancha:</strong>
                        <p>{{ strtoupper($cancha->tipo_cancha) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Superficie:</strong>
                        <p>{{ ucfirst(str_replace('_', ' ', $cancha->tipo_superficie)) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Capacidad:</strong>
                        <p>{{ $cancha->capacidad_maxima }} jugadores</p>
                    </div>
                    @if($cancha->descripcion)
                    <div class="col-12 mb-3">
                        <strong>Descripción:</strong>
                        <p>{{ $cancha->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estado y Estadísticas -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Estado y Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Estado:</strong>
                    <div class="mt-1">
                        @if($cancha->activa)
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                        
                        @if($cancha->verificada)
                            <span class="badge bg-primary">Verificada</span>
                        @endif
                        
                        @if($cancha->destacada)
                            <span class="badge bg-warning">Destacada</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Rating:</strong>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star text-warning me-1"></i>
                        <span>{{ number_format($cancha->rating, 1) }}</span>
                        <small class="text-muted ms-1">({{ $cancha->total_resenas }} reseñas)</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Total Reservas:</strong>
                    <p>{{ $cancha->total_reservas }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Fecha de Creación:</strong>
                    <p>{{ $cancha->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Ubicación -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Dirección:</strong>
                    <p>{{ $cancha->direccion }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Ciudad:</strong>
                        <p>{{ $cancha->ciudad }}</p>
                    </div>
                    @if($cancha->barrio)
                    <div class="col-md-6 mb-3">
                        <strong>Barrio:</strong>
                        <p>{{ $cancha->barrio }}</p>
                    </div>
                    @endif
                    @if($cancha->codigo_postal)
                    <div class="col-md-6 mb-3">
                        <strong>Código Postal:</strong>
                        <p>{{ $cancha->codigo_postal }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Latitud:</strong>
                        <p>{{ $cancha->latitude }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Longitud:</strong>
                        <p>{{ $cancha->longitude }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Amenities -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>Amenities
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $cancha->tiene_vestuarios ? 'check text-success' : 'times text-danger' }} me-2"></i>
                            <span>Vestuarios</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $cancha->tiene_estacionamiento ? 'check text-success' : 'times text-danger' }} me-2"></i>
                            <span>Estacionamiento</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $cancha->tiene_iluminacion ? 'check text-success' : 'times text-danger' }} me-2"></i>
                            <span>Iluminación</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $cancha->tiene_techado ? 'check text-success' : 'times text-danger' }} me-2"></i>
                            <span>Techado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Precios -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>Precios
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-primary fw-bold">${{ number_format($cancha->precio_base, 0, ',', '.') }}</div>
                        <small class="text-muted">Precio Base</small>
                    </div>
                    @if($cancha->precio_pico)
                    <div class="col-4">
                        <div class="text-warning fw-bold">${{ number_format($cancha->precio_pico, 0, ',', '.') }}</div>
                        <small class="text-muted">Precio Pico</small>
                    </div>
                    @endif
                    @if($cancha->precio_valle)
                    <div class="col-4">
                        <div class="text-success fw-bold">${{ number_format($cancha->precio_valle, 0, ',', '.') }}</div>
                        <small class="text-muted">Precio Valle</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Configuración de Reservas -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Configuración de Reservas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Anticipación Mínima:</strong>
                        <p>{{ $cancha->anticipacion_minima_horas }} horas</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Anticipación Máxima:</strong>
                        <p>{{ $cancha->anticipacion_maxima_dias }} días</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Permite Cancelación:</strong>
                        <p>{{ $cancha->permite_cancelacion ? 'Sí' : 'No' }}</p>
                    </div>
                    @if($cancha->permite_cancelacion)
                    <div class="col-md-6 mb-3">
                        <strong>Cancelación Gratuita:</strong>
                        <p>{{ $cancha->horas_cancelacion_gratuita }} horas antes</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($cancha->imagenes && count($cancha->imagenes) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i>Imágenes de la Cancha
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($cancha->imagenes as $imagen)
                    <div class="col-md-4 mb-3">
                        <img src="{{ Storage::url($imagen) }}" 
                             class="img-fluid rounded" 
                             alt="Imagen de {{ $cancha->nombre }}"
                             style="height: 200px; width: 100%; object-fit: cover;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
