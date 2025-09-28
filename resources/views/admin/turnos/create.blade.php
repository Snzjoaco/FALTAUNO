@extends('layouts.dashboard')

@section('title', 'Crear Turno - FaltaUno')

@section('styles')
<style>
    .form-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(45, 90, 39, 0.1);
    }
    
    .form-section h4 {
        color: var(--primary-green);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-green);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border: none;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 2rem;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .form-check-label {
        font-weight: 500;
        color: var(--primary-green);
    }
    
    .precio-section {
        background: linear-gradient(135deg, var(--light-green), #ffffff);
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid var(--primary-green);
    }
    
    .restricciones-section {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
    }
    
    .help-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
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
                        <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Turno
                    </h1>
                    <p class="text-muted mb-0">Configura un nuevo horario disponible para tus canchas</p>
                </div>
                <a href="{{ route('admin.turnos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Turnos
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Error en el formulario</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.turnos.store') }}">
        @csrf
        
        <!-- Información Básica -->
        <div class="form-section">
            <h4><i class="fas fa-info-circle me-2"></i>Información Básica</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cancha_id" class="form-label required-field">Cancha</label>
                    <select class="form-select @error('cancha_id') is-invalid @enderror" 
                            id="cancha_id" name="cancha_id" required>
                        <option value="">Selecciona una cancha</option>
                        @foreach($canchas as $cancha)
                            <option value="{{ $cancha->id }}" 
                                    {{ (old('cancha_id', $cancha->id ?? '') == $cancha->id) ? 'selected' : '' }}>
                                {{ $cancha->nombre }} - {{ $cancha->ciudad }}
                            </option>
                        @endforeach
                    </select>
                    @error('cancha_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label required-field">Nombre del Turno</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre') }}" 
                           placeholder="Ej: Turno Matutino, Fútbol 5, etc." required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Nombre descriptivo para identificar el turno</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" name="descripcion" rows="3" 
                              placeholder="Descripción opcional del turno...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Día y Horario -->
        <div class="form-section">
            <h4><i class="fas fa-calendar-alt me-2"></i>Día y Horario</h4>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="dia_semana" class="form-label required-field">Día de la Semana</label>
                    <select class="form-select @error('dia_semana') is-invalid @enderror" 
                            id="dia_semana" name="dia_semana" required>
                        <option value="">Selecciona un día</option>
                        <option value="lunes" {{ old('dia_semana') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                        <option value="martes" {{ old('dia_semana') == 'martes' ? 'selected' : '' }}>Martes</option>
                        <option value="miercoles" {{ old('dia_semana') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                        <option value="jueves" {{ old('dia_semana') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                        <option value="viernes" {{ old('dia_semana') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                        <option value="sabado" {{ old('dia_semana') == 'sabado' ? 'selected' : '' }}>Sábado</option>
                        <option value="domingo" {{ old('dia_semana') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                    </select>
                    @error('dia_semana')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="hora_inicio" class="form-label required-field">Hora de Inicio</label>
                    <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" 
                           id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}" required>
                    @error('hora_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="hora_fin" class="form-label required-field">Hora de Fin</label>
                    <input type="time" class="form-control @error('hora_fin') is-invalid @enderror" 
                           id="hora_fin" name="hora_fin" value="{{ old('hora_fin') }}" required>
                    @error('hora_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                           id="fecha_inicio" name="fecha_inicio" 
                           value="{{ old('fecha_inicio') }}" min="{{ date('Y-m-d') }}">
                    @error('fecha_inicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Opcional: Fecha desde la cual estará disponible</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" 
                           id="fecha_fin" name="fecha_fin" 
                           value="{{ old('fecha_fin') }}">
                    @error('fecha_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Opcional: Fecha hasta la cual estará disponible</div>
                </div>
            </div>
        </div>

        <!-- Precios -->
        <div class="form-section">
            <h4><i class="fas fa-dollar-sign me-2"></i>Configuración de Precios</h4>
            
            <div class="precio-section">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="precio_base" class="form-label required-field">Precio Base</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('precio_base') is-invalid @enderror" 
                                   id="precio_base" name="precio_base" value="{{ old('precio_base') }}" 
                                   min="0" step="0.01" required>
                        </div>
                        @error('precio_base')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="help-text">Precio estándar por hora</div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="precio_pico" class="form-label">Precio Pico</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('precio_pico') is-invalid @enderror" 
                                   id="precio_pico" name="precio_pico" value="{{ old('precio_pico') }}" 
                                   min="0" step="0.01">
                        </div>
                        @error('precio_pico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="help-text">Precio para horarios pico (18:00-22:00)</div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="precio_valle" class="form-label">Precio Valle</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('precio_valle') is-invalid @enderror" 
                                   id="precio_valle" name="precio_valle" value="{{ old('precio_valle') }}" 
                                   min="0" step="0.01">
                        </div>
                        @error('precio_valle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="help-text">Precio para horarios valle (08:00-12:00)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacidad y Restricciones -->
        <div class="form-section">
            <h4><i class="fas fa-users me-2"></i>Capacidad y Restricciones</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capacidad_maxima" class="form-label required-field">Capacidad Máxima</label>
                    <input type="number" class="form-control @error('capacidad_maxima') is-invalid @enderror" 
                           id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima', 10) }}" 
                           min="1" max="50" required>
                    @error('capacidad_maxima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="capacidad_minima" class="form-label required-field">Capacidad Mínima</label>
                    <input type="number" class="form-control @error('capacidad_minima') is-invalid @enderror" 
                           id="capacidad_minima" name="capacidad_minima" value="{{ old('capacidad_minima', 1) }}" 
                           min="1" required>
                    @error('capacidad_minima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="restricciones-section">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="hidden" name="solo_miembros" value="0">
                            <input class="form-check-input" type="checkbox" id="solo_miembros" 
                                   name="solo_miembros" value="1" {{ old('solo_miembros') ? 'checked' : '' }}>
                            <label class="form-check-label" for="solo_miembros">
                                Solo para miembros
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="hidden" name="restricciones_edad" value="0">
                            <input class="form-check-input" type="checkbox" id="restricciones_edad" 
                                   name="restricciones_edad" value="1" {{ old('restricciones_edad') ? 'checked' : '' }}>
                            <label class="form-check-label" for="restricciones_edad">
                                Aplicar restricciones de edad
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row" id="edad-restricciones" style="display: none;">
                    <div class="col-md-6 mb-3">
                        <label for="edad_minima" class="form-label">Edad Mínima</label>
                        <input type="number" class="form-control" id="edad_minima" name="edad_minima" 
                               value="{{ old('edad_minima', 18) }}" min="1" max="100">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="edad_maxima" class="form-label">Edad Máxima</label>
                        <input type="number" class="form-control" id="edad_maxima" name="edad_maxima" 
                               value="{{ old('edad_maxima', 65) }}" min="1" max="100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de Reservas -->
        <div class="form-section">
            <h4><i class="fas fa-cog me-2"></i>Configuración de Reservas</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="anticipacion_minima_horas" class="form-label required-field">Anticipación Mínima (horas)</label>
                    <input type="number" class="form-control @error('anticipacion_minima_horas') is-invalid @enderror" 
                           id="anticipacion_minima_horas" name="anticipacion_minima_horas" 
                           value="{{ old('anticipacion_minima_horas', 2) }}" min="0" max="168" required>
                    @error('anticipacion_minima_horas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Mínimo de horas de anticipación para reservar</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="anticipacion_maxima_dias" class="form-label required-field">Anticipación Máxima (días)</label>
                    <input type="number" class="form-control @error('anticipacion_maxima_dias') is-invalid @enderror" 
                           id="anticipacion_maxima_dias" name="anticipacion_maxima_dias" 
                           value="{{ old('anticipacion_maxima_dias', 30) }}" min="1" max="365" required>
                    @error('anticipacion_maxima_dias')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Máximo de días de anticipación para reservar</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="horas_cancelacion_gratuita" class="form-label required-field">Horas Cancelación Gratuita</label>
                    <input type="number" class="form-control @error('horas_cancelacion_gratuita') is-invalid @enderror" 
                           id="horas_cancelacion_gratuita" name="horas_cancelacion_gratuita" 
                           value="{{ old('horas_cancelacion_gratuita', 24) }}" min="0" max="168" required>
                    @error('horas_cancelacion_gratuita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="porcentaje_penalidad_cancelacion" class="form-label required-field">Penalidad Cancelación (%)</label>
                    <input type="number" class="form-control @error('porcentaje_penalidad_cancelacion') is-invalid @enderror" 
                           id="porcentaje_penalidad_cancelacion" name="porcentaje_penalidad_cancelacion" 
                           value="{{ old('porcentaje_penalidad_cancelacion', 0) }}" min="0" max="100" step="0.01" required>
                    @error('porcentaje_penalidad_cancelacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Estado y Visibilidad -->
        <div class="form-section">
            <h4><i class="fas fa-eye me-2"></i>Estado y Visibilidad</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="hidden" name="activo" value="0">
                        <input class="form-check-input" type="checkbox" id="activo" 
                               name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">
                            Turno Activo
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="hidden" name="recurrente" value="0">
                        <input class="form-check-input" type="checkbox" id="recurrente" 
                               name="recurrente" value="1" {{ old('recurrente', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="recurrente">
                            Turno Recurrente
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="hidden" name="visible_publico" value="0">
                        <input class="form-check-input" type="checkbox" id="visible_publico" 
                               name="visible_publico" value="1" {{ old('visible_publico', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="visible_publico">
                            Visible al Público
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="hidden" name="destacado" value="0">
                        <input class="form-check-input" type="checkbox" id="destacado" 
                               name="destacado" value="1" {{ old('destacado') ? 'checked' : '' }}>
                        <label class="form-check-label" for="destacado">
                            Turno Destacado
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="hidden" name="permite_cancelacion" value="0">
                        <input class="form-check-input" type="checkbox" id="permite_cancelacion" 
                               name="permite_cancelacion" value="1" {{ old('permite_cancelacion', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="permite_cancelacion">
                            Permite Cancelación
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="prioridad" class="form-label">Prioridad</label>
                    <input type="number" class="form-control" id="prioridad" name="prioridad" 
                           value="{{ old('prioridad', 0) }}" min="0" max="100">
                    <div class="help-text">Número para ordenar turnos (mayor = más prioritario)</div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.turnos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Crear Turno
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar restricciones de edad
    const restriccionesEdad = document.getElementById('restricciones_edad');
    const edadRestricciones = document.getElementById('edad-restricciones');
    
    restriccionesEdad.addEventListener('change', function() {
        if (this.checked) {
            edadRestricciones.style.display = 'block';
        } else {
            edadRestricciones.style.display = 'none';
        }
    });
    
    // Mostrar restricciones si ya están marcadas
    if (restriccionesEdad.checked) {
        edadRestricciones.style.display = 'block';
    }
    
    // Calcular duración automáticamente
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    
    function calcularDuracion() {
        if (horaInicio.value && horaFin.value) {
            const inicio = new Date('2000-01-01T' + horaInicio.value);
            const fin = new Date('2000-01-01T' + horaFin.value);
            
            if (fin > inicio) {
                const duracionMs = fin - inicio;
                const duracionHoras = duracionMs / (1000 * 60 * 60);
                console.log('Duración:', duracionHoras, 'horas');
            }
        }
    }
    
    horaInicio.addEventListener('change', calcularDuracion);
    horaFin.addEventListener('change', calcularDuracion);
});
</script>
@endpush
