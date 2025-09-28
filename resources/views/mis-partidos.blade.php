@extends('layouts.app')

@section('title', 'Mis Partidos - FaltaUno')

@section('content')
<style>
    .my-matches-page {
        background: var(--light-green);
        min-height: 100vh;
        padding: 120px 0 80px 0;
    }
    
    .sidebar {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 120px;
    }
    
    .sidebar-section {
        margin-bottom: 2rem;
    }
    
    .sidebar-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-btn {
        background: var(--light-green);
        border: 1px solid var(--primary-green);
        color: var(--primary-green);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .filter-btn:hover {
        background: var(--primary-green);
        color: white;
        text-decoration: none;
    }
    
    .filter-btn.active {
        background: var(--primary-green);
        color: white;
    }
    
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: var(--light-green);
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-green);
        display: block;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.25rem;
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
    
    .match-role {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .role-organizador {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .role-participante {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .match-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-proximo {
        background: #d4edda;
        color: #155724;
    }
    
    .status-pasado {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-cancelado {
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
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background-color: var(--primary-green);
        border: none;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-green);
        color: white;
    }
    
    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        color: white;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border: none;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        color: white;
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
    
    @media (max-width: 768px) {
        .sidebar {
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
        
        .btn-action {
            text-align: center;
        }
    }
</style>

<div class="my-matches-page">
    <div class="container">
        <div class="row">
            <!-- Sidebar con filtros -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <h3 class="sidebar-title">
                        <i class="fas fa-filter"></i>
                        Filtros
                    </h3>
                    
                    <div class="sidebar-section">
                        <a href="{{ route('mis-partidos', ['filtro' => 'proximos']) }}" 
                           class="filter-btn {{ $filtro === 'proximos' ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            Próximos
                        </a>
                        <a href="{{ route('mis-partidos', ['filtro' => 'pasados']) }}" 
                           class="filter-btn {{ $filtro === 'pasados' ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            Pasados
                        </a>
                        <a href="{{ route('mis-partidos', ['filtro' => 'organizo']) }}" 
                           class="filter-btn {{ $filtro === 'organizo' ? 'active' : '' }}">
                            <i class="fas fa-crown"></i>
                            Que Organizo
                        </a>
                        <a href="{{ route('mis-partidos', ['filtro' => 'pendientes']) }}" 
                           class="filter-btn {{ $filtro === 'pendientes' ? 'active' : '' }}">
                            <i class="fas fa-clock"></i>
                            Pendientes
                        </a>
                    </div>
                    
                    <div class="sidebar-section">
                        <h4 class="sidebar-title">
                            <i class="fas fa-chart-bar"></i>
                            Estadísticas
                        </h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">{{ $pagination->where('organizador_id', auth()->id())->count() }}</span>
                                <div class="stat-label">Organizados</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $pagination->where('organizador_id', '!=', auth()->id())->count() }}</span>
                                <div class="stat-label">Participando</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-lg-9">
                <div class="results-header">
                    <div class="results-count">
                        {{ $pagination->total() }} partido(s) encontrado(s)
                    </div>
                </div>
                
                @if($pagination->count() > 0)
                    @foreach($pagination as $partido)
                        <div class="match-card">
                            <div class="match-header">
                                <div>
                                    <h4 class="match-title">{{ $partido->titulo }}</h4>
                                    <span class="match-role {{ $partido->organizador_id === auth()->id() ? 'role-organizador' : 'role-participante' }}">
                                        {{ $partido->organizador_id === auth()->id() ? 'Organizador' : 'Participante' }}
                                    </span>
                                </div>
                                <span class="match-status status-{{ $partido->fecha >= now()->format('Y-m-d') ? 'proximo' : 'pasado' }}">
                                    {{ $partido->fecha >= now()->format('Y-m-d') ? 'Próximo' : 'Pasado' }}
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
                                    <span>{{ $partido->jugadores_confirmados }}/{{ $partido->jugadores_requeridos }} jugadores</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span>${{ number_format($partido->costo_por_jugador, 2) }} p/p</span>
                                </div>
                            </div>
                            
                            <div class="match-tags">
                                <span class="match-tag">{{ $partido->nivel_formateado }}</span>
                                <span class="match-tag">{{ $partido->jugadores_requeridos }}v{{ $partido->jugadores_requeridos }}</span>
                                <span class="match-tag">{{ ucfirst($partido->estado) }}</span>
                            </div>
                            
                            <p class="match-description">{{ $partido->descripcion }}</p>
                            
                            <div class="match-actions">
                                @if($partido->organizador_id === auth()->id())
                                    <!-- Acciones para organizador -->
                                    <a href="#" class="btn-action btn-primary">
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </a>
                                    <a href="#" class="btn-action btn-primary">
                                        <i class="fas fa-users"></i>
                                        Participantes
                                    </a>
                                    @if($partido->estado !== 'cancelado')
                                        <form action="{{ route('mis-partidos.cancelar-partido', $partido->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-danger" onclick="return confirm('¿Estás seguro de cancelar este partido?')">
                                                <i class="fas fa-times"></i>
                                                Cancelar Partido
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <!-- Acciones para participante -->
                                    <a href="#" class="btn-action btn-primary">
                                        <i class="fas fa-eye"></i>
                                        Ver Detalles
                                    </a>
                                    @if($partido->estado !== 'cancelado' && $partido->fecha >= now()->format('Y-m-d'))
                                        <form action="{{ route('mis-partidos.cancelar-participacion', $partido->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-danger" onclick="return confirm('¿Estás seguro de cancelar tu participación?')">
                                                <i class="fas fa-sign-out-alt"></i>
                                                Cancelar Participación
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <a href="#" class="btn-action btn-secondary">
                                    <i class="fas fa-share"></i>
                                    Compartir
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $pagination->links() }}
                    </div>
                @else
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h4>No se encontraron partidos</h4>
                        <p>No tienes partidos en esta categoría.</p>
                        <a href="{{ route('buscar-partidos') }}" class="btn-action btn-primary">
                            <i class="fas fa-search"></i>
                            Buscar Partidos
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
            showToast.success('{{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast.error('{{ session('error') }}');
        });
    </script>
@endif

@endsection