@extends('layouts.app')

@section('title', 'Crear Partido - FaltaUno')

@section('content')
<style>
    .create-page {
        background: var(--light-green);
        min-height: 100vh;
        padding: 120px 0 80px 0;
    }
    
    .create-form {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    
    .form-input:focus {
        border-color: var(--primary-green);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        background: white;
    }
    
    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-checkbox input {
        width: 18px;
        height: 18px;
    }
    
    .btn-create {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s;
    }
    
    .btn-create:hover {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
        transform: translateY(-2px);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-help {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.25rem;
    }
    
    .required {
        color: #dc3545;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .create-form {
            margin: 0 1rem;
            padding: 1.5rem;
        }
    }
</style>

<div class="create-page">
    <div class="container">
        <div class="create-form">
            <h1 class="form-title">
                <i class="fas fa-futbol me-2"></i>
                Crear Nuevo Partido
            </h1>
            
            <form action="{{ route('partidos.store') }}" method="POST">
                @csrf
                
                <!-- Información básica -->
                <div class="form-group">
                    <label for="titulo" class="form-label">
                        Título del partido <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           class="form-input" 
                           placeholder="Ej: Partido mixto 6v6 en F5 Arena"
                           value="{{ old('titulo') }}"
                           required>
                    @error('titulo')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="descripcion" class="form-label">
                        Descripción <span class="required">*</span>
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-input form-textarea" 
                              placeholder="Describe el partido, nivel requerido, reglas especiales, etc."
                              required>{{ old('descripcion') }}</textarea>
                    <div class="form-help">Máximo 1000 caracteres</div>
                    @error('descripcion')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Ubicación y fecha -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="cancha_id" class="form-label">
                            Cancha <span class="required">*</span>
                        </label>
                        <select id="cancha_id" name="cancha_id" class="form-select" required>
                            <option value="">Selecciona una cancha</option>
                            @foreach($canchas as $cancha)
                                <option value="{{ $cancha->id }}" {{ old('cancha_id') == $cancha->id ? 'selected' : '' }}>
                                    {{ $cancha->nombre }} - ${{ $cancha->precio_por_hora }}/h
                                </option>
                            @endforeach
                        </select>
                        @error('cancha_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="nivel" class="form-label">
                            Nivel <span class="required">*</span>
                        </label>
                        <select id="nivel" name="nivel" class="form-select" required>
                            <option value="">Selecciona el nivel</option>
                            <option value="principiante" {{ old('nivel') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                            <option value="intermedio" {{ old('nivel') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('nivel') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                        @error('nivel')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Fecha y hora -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha" class="form-label">
                            Fecha <span class="required">*</span>
                        </label>
                        <input type="date" 
                               id="fecha" 
                               name="fecha" 
                               class="form-input" 
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('fecha') }}"
                               required>
                        @error('fecha')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="hora" class="form-label">
                            Hora <span class="required">*</span>
                        </label>
                        <input type="time" 
                               id="hora" 
                               name="hora" 
                               class="form-input" 
                               value="{{ old('hora') }}"
                               required>
                        @error('hora')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Jugadores y precio -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="jugadores_necesarios" class="form-label">
                            Jugadores necesarios <span class="required">*</span>
                        </label>
                        <select id="jugadores_necesarios" name="jugadores_necesarios" class="form-select" required>
                            <option value="">Selecciona cantidad</option>
                            @for($i = 4; $i <= 22; $i += 2)
                                <option value="{{ $i }}" {{ old('jugadores_necesarios') == $i ? 'selected' : '' }}>
                                    {{ $i }} jugadores
                                </option>
                            @endfor
                        </select>
                        <div class="form-help">Incluyéndote a ti</div>
                        @error('jugadores_necesarios')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="precio_por_persona" class="form-label">
                            Precio por persona ($) <span class="required">*</span>
                        </label>
                        <input type="number" 
                               id="precio_por_persona" 
                               name="precio_por_persona" 
                               class="form-input" 
                               placeholder="0.00"
                               step="0.01"
                               min="0"
                               value="{{ old('precio_por_persona') }}"
                               required>
                        <div class="form-help">Costo total dividido entre todos</div>
                        @error('precio_por_persona')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Opciones adicionales -->
                <div class="form-group">
                    <div class="form-checkbox">
                        <input type="checkbox" 
                               id="equipamiento_incluido" 
                               name="equipamiento_incluido" 
                               value="1"
                               {{ old('equipamiento_incluido') ? 'checked' : '' }}>
                        <label for="equipamiento_incluido" class="form-label" style="margin: 0;">
                            Incluir equipamiento (pelotas, conos, etc.)
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contacto_whatsapp" class="form-label">
                        WhatsApp de contacto (opcional)
                    </label>
                    <input type="text" 
                           id="contacto_whatsapp" 
                           name="contacto_whatsapp" 
                           class="form-input" 
                           placeholder="+54 9 11 1234-5678"
                           value="{{ old('contacto_whatsapp') }}">
                    <div class="form-help">Para consultas directas</div>
                    @error('contacto_whatsapp')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Botón de crear -->
                <div class="form-group">
                    <button type="submit" class="btn-create">
                        <i class="fas fa-plus-circle me-2"></i>
                        Crear Partido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como hoy
    const fechaInput = document.getElementById('fecha');
    const hoy = new Date().toISOString().split('T')[0];
    fechaInput.min = hoy;
    
    // Si no hay fecha seleccionada, establecer como mañana
    if (!fechaInput.value) {
        const mañana = new Date();
        mañana.setDate(mañana.getDate() + 1);
        fechaInput.value = mañana.toISOString().split('T')[0];
    }
    
    // Establecer hora por defecto como 19:00
    const horaInput = document.getElementById('hora');
    if (!horaInput.value) {
        horaInput.value = '19:00';
    }
});
</script>

@endsection
