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
    }
    
    .filter-btn:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .filter-btn.active {
        background: var(--primary-green);
        color: white;
    }
    
    .action-btn {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: var(--secondary-green);
        color: white;
    }
    
    .action-btn.secondary {
        background: transparent;
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
    }
    
    .action-btn.secondary:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .profile-card {
        background: var(--light-green);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }
    
    .profile-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto 0.5rem;
    }
    
    .profile-name {
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.25rem;
    }
    
    .profile-level {
        color: var(--secondary-green);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .profile-stats {
        display: flex;
        justify-content: space-around;
        font-size: 0.8rem;
        color: #666;
    }
    
    .main-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .content-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--primary-green);
        margin: 0;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-create {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-create:hover {
        background: var(--secondary-green);
        color: white;
    }
    
    .btn-exit {
        background: transparent;
        color: #666;
        border: 1px solid #ddd;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-exit:hover {
        background: #f8f9fa;
        color: var(--primary-green);
    }
    
    .filters-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-label {
        font-weight: 600;
        color: var(--primary-green);
        font-size: 0.9rem;
    }
    
    .filter-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    
    .search-input {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 20px;
        font-size: 0.9rem;
        min-width: 200px;
    }
    
    .search-input:focus {
        border-color: var(--primary-green);
        outline: none;
    }
    
    .status-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }
    
    .status-tab {
        background: transparent;
        border: none;
        color: #666;
        padding: 1rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }
    
    .status-tab:hover {
        color: var(--primary-green);
    }
    
    .status-tab.active {
        color: var(--primary-green);
        border-bottom-color: var(--primary-green);
    }
    
    .matches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .match-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-left: 4px solid var(--primary-green);
        transition: transform 0.3s ease;
    }
    
    .match-card:hover {
        transform: translateY(-2px);
    }
    
    .match-card.cancelled {
        border-left-color: #dc3545;
        opacity: 0.7;
    }
    
    .match-card.organized {
        border-left-color: var(--accent-orange);
    }
    
    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .match-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin: 0;
    }
    
    .match-type {
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .match-details {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
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
    }
    
    .match-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.upcoming {
        background: #d4edda;
        color: #155724;
    }
    
    .status-badge.past {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-badge.organized {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-badge.pending {
        background: #cce5ff;
        color: #004085;
    }
    
    .status-badge.cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .match-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .action-link {
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .action-link:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .action-link.danger {
        background: #f8d7da;
        color: #721c24;
    }
    
    .action-link.danger:hover {
        background: #dc3545;
        color: white;
    }
    
    .cta-section {
        background: var(--light-green);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        margin-top: 2rem;
    }
    
    .cta-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .cta-text {
        color: #666;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .my-matches-page {
            padding: 100px 0 60px 0;
        }
        
        .sidebar {
            position: static;
            margin-bottom: 2rem;
        }
        
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filters-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .status-tabs {
            flex-wrap: wrap;
        }
        
        .matches-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 576px) {
        .my-matches-page {
            padding: 80px 0 40px 0;
        }
    }
</style>

<div class="my-matches-page">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <!-- Filtros Rápidos -->
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">
                            <i class="fas fa-filter"></i>
                            Filtros Rápidos
                        </h3>
                        <button class="filter-btn active">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                        <button class="filter-btn">
                            <i class="fas fa-calendar-week"></i>
                            Esta semana
                        </button>
                        <button class="filter-btn">
                            <i class="fas fa-futbol"></i>
                            5v5
                        </button>
                        <button class="filter-btn">
                            <i class="fas fa-map-marker-alt"></i>
                            Cerca de mí
                        </button>
                    </div>
                    
                    <!-- Mi Perfil -->
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">
                            <i class="fas fa-user"></i>
                            Mi Perfil
                        </h3>
                        <div class="profile-card">
                            <div class="profile-avatar">JP</div>
                            <div class="profile-name">Juan Pérez</div>
                            <div class="profile-level">Nivel: Intermedio</div>
                            <div class="profile-stats">
                                <div>
                                    <div>15</div>
                                    <div>Partidos</div>
                                </div>
                                <div>
                                    <div>4.2</div>
                                    <div>Rating</div>
                                </div>
                                <div>
                                    <div>3</div>
                                    <div>Organizados</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenido Principal -->
            <div class="col-lg-9">
                <div class="main-content">
                    <!-- Header -->
                    <div class="content-header">
                        <h2 class="content-title">Mis Partidos</h2>
                        <div class="header-actions">
                            <a href="#" class="btn-create">
                                <i class="fas fa-plus"></i>
                                Crear partido
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="filters-section">
                        <div class="filter-group">
                            <label class="filter-label">Filtrar por sede:</label>
                            <select class="filter-select">
                                <option>Todas</option>
                                <option>F5 Arena</option>
                                <option>La Esquina</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Tipo:</label>
                            <select class="filter-select">
                                <option>5v5, 7v7, 11v11</option>
                                <option>5v5</option>
                                <option>7v7</option>
                                <option>11v11</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Esta semana</label>
                        </div>
                        <input type="text" class="search-input" placeholder="Buscar por nombre o barrio...">
                    </div>
                    
                    <!-- Tabs de Estado -->
                    <div class="status-tabs">
                        <button class="status-tab active">Próximos</button>
                        <button class="status-tab">Pasados</button>
                        <button class="status-tab">Organizo</button>
                        <button class="status-tab">Pendientes</button>
                    </div>
                    
                    <!-- Lista de Partidos -->
                    <div class="matches-grid">
                        <!-- Partido 1 -->
                        <div class="match-card">
                            <div class="match-header">
                                <h4 class="match-title">Mixto 6v6 en F5 Arena</h4>
                                <span class="match-type">F5</span>
                            </div>
                            <div class="match-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Palermo</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Hoy 20:00</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>10/12 jugadores</span>
                                </div>
                            </div>
                            <div class="match-status">
                                <span class="status-badge upcoming">Próximo</span>
                                <span class="price-range">$5 p/p</span>
                            </div>
                            <div class="match-actions">
                                <a href="#" class="action-link">
                                    <i class="fas fa-eye"></i>
                                    Ver detalles
                                </a>
                                <a href="#" class="action-link">
                                    <i class="fas fa-comments"></i>
                                    Chat
                                </a>
                            </div>
                        </div>
                        
                        <!-- Partido 2 -->
                        <div class="match-card organized">
                            <div class="match-header">
                                <h4 class="match-title">Pickup 5v5 Nuñez</h4>
                                <span class="match-type">F5</span>
                            </div>
                            <div class="match-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Nuñez</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Mañana 19:30</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>8/10 jugadores</span>
                                </div>
                            </div>
                            <div class="match-status">
                                <span class="status-badge organized">Organizo</span>
                                <span class="price-range">$4 p/p</span>
                            </div>
                            <div class="match-actions">
                                <a href="#" class="action-link">
                                    <i class="fas fa-cog"></i>
                                    Gestionar
                                </a>
                                <a href="#" class="action-link">
                                    <i class="fas fa-users"></i>
                                    Jugadores
                                </a>
                            </div>
                        </div>
                        
                        <!-- Partido 3 -->
                        <div class="match-card">
                            <div class="match-header">
                                <h4 class="match-title">Torneo Villa Urquiza</h4>
                                <span class="match-type">F7</span>
                            </div>
                            <div class="match-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Villa Urquiza</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Sáb 17:00</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>12/14 jugadores</span>
                                </div>
                            </div>
                            <div class="match-status">
                                <span class="status-badge pending">Pendiente</span>
                                <span class="price-range">$10 p/p</span>
                            </div>
                            <div class="match-actions">
                                <a href="#" class="action-link">
                                    <i class="fas fa-eye"></i>
                                    Ver detalles
                                </a>
                                <a href="#" class="action-link danger">
                                    <i class="fas fa-times"></i>
                                    Salir del partido
                                </a>
                            </div>
                        </div>
                        
                        <!-- Partido 4 -->
                        <div class="match-card cancelled">
                            <div class="match-header">
                                <h4 class="match-title">Partido Cancelado</h4>
                                <span class="match-type">F5</span>
                            </div>
                            <div class="match-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Caballito</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Ayer 20:00</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>Cancelado</span>
                                </div>
                            </div>
                            <div class="match-status">
                                <span class="status-badge cancelled">Cancelado</span>
                                <span class="price-range">-</span>
                            </div>
                            <div class="match-actions">
                                <a href="#" class="action-link">
                                    <i class="fas fa-eye"></i>
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CTA Section -->
                    <div class="cta-section">
                        <h3 class="cta-title">¿No ves el partido que buscás?</h3>
                        <p class="cta-text">Crea un nuevo partido y encuentra jugadores</p>
                        <a href="#" class="btn-create">
                            <i class="fas fa-plus"></i>
                            Crear nuevo partido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
