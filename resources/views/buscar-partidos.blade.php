@extends('layouts.app')

@section('title', 'Buscar Partidos - FaltaUno')

@section('content')
<style>
    .search-page {
        background: var(--light-green);
        min-height: 100vh;
        padding: 120px 0 80px 0;
    }
    
    .filters-sidebar {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 120px;
    }
    
    .filters-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1.5rem;
    }
    
    .filter-group {
        margin-bottom: 2rem;
    }
    
    .filter-label {
        font-weight: 600;
        color: var(--primary-green);
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .filter-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .filter-input:focus {
        border-color: var(--primary-green);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .filter-select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
    }
    
    .btn-filter {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .btn-filter:hover {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
    }
    
    .btn-clear {
        background-color: transparent;
        border: 2px solid #ddd;
        color: #666;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
    }
    
    .btn-clear:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .results-count {
        font-size: 1.1rem;
        color: var(--primary-green);
        font-weight: 600;
    }
    
    .sort-select {
        padding: 0.5rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .match-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary-green);
    }
    
    .match-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    }
    
    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .match-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .match-organizer {
        color: #666;
        font-size: 0.9rem;
    }
    
    .match-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-activo {
        background: #d4edda;
        color: #155724;
    }
    
    .status-completo {
        background: #f8d7da;
        color: #721c24;
    }
    
    .match-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.9rem;
    }
    
    .detail-item i {
        color: var(--primary-green);
        width: 16px;
    }
    
    .match-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .match-tag {
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .match-description {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .match-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .btn-join {
        background-color: var(--primary-green);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    
    .btn-join:hover {
        background-color: var(--secondary-green);
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-join:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    
    .btn-save {
        background-color: transparent;
        border: 2px solid #ddd;
        color: #666;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    
    .btn-create {
        background-color: var(--accent-orange);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        margin-bottom: 2rem;
    }
    
    .btn-create:hover {
        background-color: #e67a2e;
        color: white;
        transform: translateY(-2px);
    }
    
    .no-results {
        text-align: center;
        padding: 3rem;
        color: #666;
    }
    
    .no-results i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .filters-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
        
        .match-details {
            grid-template-columns: 1fr;
        }
        
        .match-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-join, .btn-save {
            text-align: center;
        }
    }
</style>

<div class="search-page">
    <div class="container">
        <div class="row">
            <!-- Filtros -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <h3 class="filters-title">
                        <i class="fas fa-filter me-2"></i>
                        Filtros
                    </h3>
                    
                    <form method="GET" action="{{ route('buscar-partidos') }}">
                        <div class="filter-group">
                            <label for="ubicacion" class="filter-label">Ubicación</label>
                            <input type="text" 
                                   id="ubicacion" 
                                   name="ubicacion" 
                                   class="filter-input" 
                                   placeholder="Ej: Palermo, Nuñez"
                                   value="{{ request('ubicacion') }}">
                        </div>
                        
                        <div class="filter-group">
                            <label for="fecha" class="filter-label">Fecha</label>
                            <input type="date" 
                                   id="fecha" 
                                   name="fecha" 
                                   class="filter-input"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ request('fecha') }}">
                        </div>
                        
                        <div class="filter-group">
                            <label for="nivel" class="filter-label">Nivel</label>
                            <select id="nivel" name="nivel" class="filter-select">
                                <option value="">Todos los niveles</option>
                                <option value="principiante" {{ request('nivel') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                                <option value="intermedio" {{ request('nivel') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="avanzado" {{ request('nivel') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="precio_max" class="filter-label">Precio máximo</label>
                            <input type="number" 
                                   id="precio_max" 
                                   name="precio_max" 
                                   class="filter-input" 
                                   placeholder="Ej: 10"
                                   min="0"
                                   step="0.01"
                                   value="{{ request('precio_max') }}">
                        </div>
                        
                        <div class="filter-group">
                            <label for="jugadores_faltantes" class="filter-label">Jugadores faltantes</label>
                            <select id="jugadores_faltantes" name="jugadores_faltantes" class="filter-select">
                                <option value="">Cualquier cantidad</option>
                                <option value="1" {{ request('jugadores_faltantes') == '1' ? 'selected' : '' }}>1+ jugadores</option>
                                <option value="2" {{ request('jugadores_faltantes') == '2' ? 'selected' : '' }}>2+ jugadores</option>
                                <option value="3" {{ request('jugadores_faltantes') == '3' ? 'selected' : '' }}>3+ jugadores</option>
                                <option value="5" {{ request('jugadores_faltantes') == '5' ? 'selected' : '' }}>5+ jugadores</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search me-2"></i>
                            Buscar
                        </button>
                        
                        <a href="{{ route('buscar-partidos') }}" class="btn-clear">
                            <i class="fas fa-times me-2"></i>
                            Limpiar
                        </a>
                    </form>
                </div>
            </div>
            
            <!-- Resultados -->
            <div class="col-lg-9">
                <div class="results-header">
                    <div class="results-count">
                        {{ $partidos->total() }} partido(s) encontrado(s)
                    </div>
                    <select class="sort-select" onchange="this.form.submit()">
                        <option value="fecha_asc" {{ request('sort') == 'fecha_asc' ? 'selected' : '' }}>Fecha (más cercano)</option>
                        <option value="fecha_desc" {{ request('sort') == 'fecha_desc' ? 'selected' : '' }}>Fecha (más lejano)</option>
                        <option value="precio_asc" {{ request('sort') == 'precio_asc' ? 'selected' : '' }}>Precio (menor)</option>
                        <option value="precio_desc" {{ request('sort') == 'precio_desc' ? 'selected' : '' }}>Precio (mayor)</option>
                    </select>
                </div>
                
                <a href="{{ route('partidos.create') }}" class="btn-create">
                    <i class="fas fa-plus-circle"></i>
                    Crear Nuevo Partido
                </a>
                
                @if($partidos->count() > 0)
                    @foreach($partidos as $partido)
                        <div class="match-card">
                            <div class="match-header">
                                <div>
                                    <h4 class="match-title">{{ $partido->titulo }}</h4>
                                    <p class="match-organizer">
                                        <i class="fas fa-user me-1"></i>
                                        Organizado por {{ $partido->organizador->name }}
                                    </p>
                                </div>
                                <span class="match-status status-{{ $partido->estado }}">
                                    {{ ucfirst($partido->estado) }}
                                </span>
                            </div>
                            
                            <div class="match-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $partido->cancha->nombre }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $partido->fecha_formateada }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $partido->jugadores_faltantes }} faltantes</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span>${{ number_format($partido->precio_por_persona, 2) }} p/p</span>
                                </div>
                            </div>
                            
                            <div class="match-tags">
                                <span class="match-tag">{{ $partido->nivel_formateado }}</span>
                                <span class="match-tag">{{ $partido->jugadores_necesarios }}v{{ $partido->jugadores_necesarios }}</span>
                                @if($partido->equipamiento_incluido)
                                    <span class="match-tag">Equipamiento incluido</span>
                                @endif
                            </div>
                            
                            <p class="match-description">{{ $partido->descripcion }}</p>
                            
                            <div class="match-actions">
                                @auth
                                    @if($partido->puedeUnirse(auth()->id()))
                                        <form action="{{ route('partidos.join', $partido->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-join">
                                                <i class="fas fa-handshake"></i>
                                                Unirme
                                            </button>
                                        </form>
                                    @elseif($partido->puedeAbandonar(auth()->id()))
                                        <form action="{{ route('partidos.leave', $partido->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-join" style="background-color: #dc3545;">
                                                <i class="fas fa-sign-out-alt"></i>
                                                Abandonar
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-join" disabled>
                                            <i class="fas fa-check"></i>
                                            Ya participas
                                        </button>
                                    @endif
                                    
                                    @if($partido->puedeEliminar(auth()->id()))
                                        <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-save" style="color: #dc3545; border-color: #dc3545;" onclick="return confirm('¿Estás seguro de cancelar este partido?')">
                                                <i class="fas fa-trash"></i>
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-join">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Iniciar sesión para unirse
                                    </a>
                                @endauth
                                
                                <a href="#" class="btn-save">
                                    <i class="fas fa-bookmark"></i>
                                    Guardar
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $partidos->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h4>No se encontraron partidos</h4>
                        <p>Intenta ajustar los filtros o crear un nuevo partido.</p>
                        <a href="{{ route('partidos.create') }}" class="btn-create">
                            <i class="fas fa-plus-circle"></i>
                            Crear Primer Partido
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('{{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('{{ session('error') }}');
        });
    </script>
@endif

@endsection