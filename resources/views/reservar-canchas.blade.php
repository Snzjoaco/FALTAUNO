@extends('layouts.app')

@section('title', 'Reservar Canchas - FaltaUno')

@section('content')
<style>
    .reserve-page {
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
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
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
    }
    
    .filter-btn:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .filter-btn.active {
        background: var(--primary-green);
        color: white;
    }
    
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .tab-btn {
        background: var(--light-green);
        border: 1px solid var(--primary-green);
        color: var(--primary-green);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .tab-btn:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .tab-btn.active {
        background: var(--primary-green);
        color: white;
    }
    
    .filter-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .btn-apply {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        flex: 1;
    }
    
    .btn-clear {
        background: transparent;
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        flex: 1;
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
    
    .search-controls {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 300px;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .search-input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .control-btn {
        background: var(--light-green);
        border: 1px solid var(--primary-green);
        color: var(--primary-green);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .control-btn:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .map-container {
        background: var(--light-green);
        border-radius: 12px;
        height: 400px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
        font-size: 1.2rem;
        position: relative;
        overflow: hidden;
    }
    
    .recommended-section {
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--accent-orange);
    }
    
    .courts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .courts-horizontal {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .court-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .court-card:hover {
        transform: translateY(-5px);
    }
    
    .court-card-horizontal {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        padding: 1.5rem;
        gap: 1.5rem;
    }
    
    .court-card-horizontal:hover {
        transform: translateY(-2px);
    }
    
    .court-image-horizontal {
        width: 120px;
        height: 80px;
        background: linear-gradient(45deg, var(--secondary-green), #6b9b6b);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
        position: relative;
    }
    
    .court-image-horizontal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="50" r="2" fill="%23ffffff" opacity="0.3"/><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="80" cy="30" r="1.5" fill="%23ffffff" opacity="0.2"/><circle cx="30" cy="80" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="70" cy="70" r="1" fill="%23ffffff" opacity="0.2"/></svg>');
        border-radius: 8px;
    }
    
    .court-info-horizontal {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .court-header-horizontal {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    
    .court-name-horizontal {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--primary-green);
        margin: 0;
    }
    
    .court-location-horizontal {
        color: var(--secondary-green);
        font-size: 0.9rem;
    }
    
    .court-address-horizontal {
        color: #666;
        font-size: 0.85rem;
    }
    
    .court-details-horizontal {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .court-rating-horizontal {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .court-features-horizontal {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .court-actions-horizontal {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
        min-width: 120px;
    }
    
    .available-times-horizontal {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    
    .btn-reserve-horizontal {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-reserve-horizontal:hover {
        background: var(--secondary-green);
        color: white;
    }
    
    .court-image {
        height: 200px;
        background: linear-gradient(45deg, var(--secondary-green), #6b9b6b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        position: relative;
    }
    
    .court-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="50" r="2" fill="%23ffffff" opacity="0.3"/><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="80" cy="30" r="1.5" fill="%23ffffff" opacity="0.2"/><circle cx="30" cy="80" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="70" cy="70" r="1" fill="%23ffffff" opacity="0.2"/></svg>');
    }
    
    .court-info {
        padding: 1.5rem;
    }
    
    .court-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .court-location {
        color: var(--secondary-green);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .court-address {
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    
    .court-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .stars {
        color: #ffc107;
    }
    
    .rating-text {
        color: #666;
        font-size: 0.9rem;
    }
    
    .court-features {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .feature-tag {
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .available-times {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .time-btn {
        background: var(--light-green);
        border: 1px solid var(--primary-green);
        color: var(--primary-green);
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .time-btn:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .court-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .btn-reserve {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-reserve:hover {
        background: var(--secondary-green);
        color: white;
    }
    
    .price-range {
        color: var(--accent-orange);
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .referral-badge {
        background: var(--accent-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    
    @media (max-width: 768px) {
        .reserve-page {
            padding: 100px 0 60px 0;
        }
        
        .filters-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
        
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-controls {
            flex-direction: column;
        }
        
        .search-input {
            min-width: auto;
        }
        
        .courts-grid {
            grid-template-columns: 1fr;
        }
        
        .court-card-horizontal {
            flex-direction: column;
            text-align: center;
        }
        
        .court-image-horizontal {
            width: 100%;
            height: 120px;
        }
        
        .court-actions-horizontal {
            align-items: center;
            min-width: auto;
        }
    }
    
    @media (max-width: 576px) {
        .reserve-page {
            padding: 80px 0 40px 0;
        }
    }
</style>

<div class="reserve-page">
    <div class="container">
        <div class="row">
            <!-- Sidebar de Filtros -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <h3 class="filters-title">Filtros</h3>
                    
                    <!-- Buscar por nombre -->
                    <div class="filter-group">
                        <label class="filter-label">Buscar por nombre</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control filter-input" placeholder="Ej: F5 Arena, La Esquina">
                        </div>
                    </div>
                    
                    <!-- Zona o barrio -->
                    <div class="filter-group">
                        <label class="filter-label">Zona o barrio</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" class="form-control filter-input" placeholder="Ej: Palermo, Nuñez">
                        </div>
                        <div class="filter-buttons mt-2">
                            <button class="filter-btn">Palermo</button>
                            <button class="filter-btn">Nuñez</button>
                            <button class="filter-btn">Caballito</button>
                            <button class="filter-btn">Villa Urquiza</button>
                        </div>
                    </div>
                    
                    <!-- Formato -->
                    <div class="filter-group">
                        <label class="filter-label">Formato</label>
                        <div class="filter-buttons">
                            <button class="filter-btn">5v5</button>
                            <button class="filter-btn">6v6</button>
                            <button class="filter-btn">7v7</button>
                            <button class="filter-btn">8v8</button>
                        </div>
                    </div>
                    
                    <!-- Tipo de cancha -->
                    <div class="filter-group">
                        <label class="filter-label">Tipo de cancha</label>
                        <div class="filter-buttons">
                            <button class="filter-btn">Sintético</button>
                            <button class="filter-btn">Natural</button>
                            <button class="filter-btn">Techada</button>
                        </div>
                    </div>
                    
                    <!-- Rango de precio -->
                    <div class="filter-group">
                        <label class="filter-label">Rango de precio</label>
                        <div class="filter-buttons">
                            <button class="filter-btn">$</button>
                            <button class="filter-btn">$$</button>
                            <button class="filter-btn">$$$</button>
                        </div>
                    </div>
                    
                    <!-- Disponibilidad -->
                    <div class="filter-group">
                        <label class="filter-label">Disponibilidad</label>
                        <div class="filter-tabs">
                            <button class="tab-btn active">Hoy</button>
                            <button class="tab-btn">Mañana</button>
                            <button class="tab-btn">Esta semana</button>
                        </div>
                        <div class="filter-buttons mt-2">
                            <button class="filter-btn">Lun</button>
                            <button class="filter-btn">Mar</button>
                            <button class="filter-btn">Mié</button>
                            <button class="filter-btn">Jue</button>
                            <button class="filter-btn">Vie</button>
                            <button class="filter-btn">Sáb</button>
                        </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="filter-actions">
                        <button class="btn btn-apply">
                            <i class="fas fa-filter"></i>
                            Aplicar filtros
                        </button>
                        <button class="btn btn-clear">Limpiar</button>
                    </div>
                </div>
            </div>
            
            <!-- Contenido Principal -->
            <div class="col-lg-9">
                <div class="main-content">
                    <!-- Header del contenido -->
                    <div class="content-header">
                        <h2 class="content-title">Reservar Canchas</h2>
                        <div class="search-controls">
                            <input type="text" class="search-input" placeholder="Buscar canchas por nombre o zona...">
                            <a href="#" class="control-btn">
                                <i class="fas fa-map"></i>
                                Ver mapa
                            </a>
                            <a href="#" class="control-btn">
                                <i class="fas fa-calendar"></i>
                                Elegir fecha
                            </a>
                            <a href="#" class="control-btn">
                                <i class="fas fa-sync-alt"></i>
                                Actualizar
                            </a>
                        </div>
                    </div>
                    
                    <!-- Mapa -->
                    <div class="map-container">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                            <h4>Mapa Interactivo</h4>
                            <p>Encuentra canchas cerca de ti</p>
                        </div>
                    </div>
                    
                    <!-- Canchas Recomendadas -->
                    <div class="recommended-section">
                        <h3 class="section-title">
                            <i class="fas fa-star"></i>
                            Canchas Recomendadas
                        </h3>
                        <div class="courts-grid">
                            <!-- Cancha 1 -->
                            <div class="court-card">
                                <div class="court-image">
                                    <i class="fas fa-futbol fa-3x"></i>
                                </div>
                                <div class="court-info">
                                    <h4 class="court-name">F5 Arena Palermo</h4>
                                    <div class="court-location">Palermo</div>
                                    <div class="court-address">Godoy Cruz 1234</div>
                                    <div class="court-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="rating-text">4.7</span>
                                    </div>
                                    <div class="court-features">
                                        <span class="feature-tag">5v5</span>
                                        <span class="feature-tag">Sintético</span>
                                        <span class="feature-tag">Techada</span>
                                    </div>
                                    <div class="available-times">
                                        <button class="time-btn">Hoy 19:00</button>
                                        <button class="time-btn">Hoy 20:00</button>
                                        <button class="time-btn">Mañana 21:00</button>
                                    </div>
                                    <div class="court-actions">
                                        <span class="price-range">$$</span>
                                        <a href="#" class="btn-reserve">Reservar</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cancha 2 -->
                            <div class="court-card">
                                <div class="court-image">
                                    <i class="fas fa-futbol fa-3x"></i>
                                </div>
                                <div class="court-info">
                                    <h4 class="court-name">La Esquina Fútbol</h4>
                                    <div class="court-location">Nuñez</div>
                                    <div class="court-address">Av. Cabildo 2200</div>
                                    <div class="court-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="rating-text">4.5</span>
                                    </div>
                                    <div class="court-features">
                                        <span class="feature-tag">6v6</span>
                                        <span class="feature-tag">Sintético</span>
                                    </div>
                                    <div class="available-times">
                                        <button class="time-btn">Hoy 18:30</button>
                                        <button class="time-btn">Mañana 19:30</button>
                                        <button class="time-btn">Sáb 17:00</button>
                                    </div>
                                    <div class="court-actions">
                                        <span class="price-range">$$</span>
                                        <a href="#" class="btn-reserve">Reservar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Canchas Referidas -->
                    <div class="recommended-section">
                        <h3 class="section-title">
                            <i class="fas fa-handshake"></i>
                            Canchas Referidas
                        </h3>
                        <div class="courts-grid">
                            <!-- Cancha 3 -->
                            <div class="court-card">
                                <div class="court-image">
                                    <i class="fas fa-futbol fa-3x"></i>
                                    <div class="referral-badge">Referida</div>
                                </div>
                                <div class="court-info">
                                    <h4 class="court-name">Club Mitre</h4>
                                    <div class="court-location">Caballito</div>
                                    <div class="court-address">Rivadavia 4500</div>
                                    <div class="court-rating">
                                        <div class="stars">★★★★☆</div>
                                        <span class="rating-text">4.2</span>
                                    </div>
                                    <div class="court-features">
                                        <span class="feature-tag">7v7</span>
                                        <span class="feature-tag">Natural</span>
                                    </div>
                                    <div class="available-times">
                                        <button class="time-btn">Hoy 21:00</button>
                                        <button class="time-btn">Vie 20:00</button>
                                        <button class="time-btn">Sáb 18:00</button>
                                    </div>
                                    <div class="court-actions">
                                        <span class="price-range">$$$</span>
                                        <a href="#" class="btn-reserve">Reservar</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cancha 4 -->
                            <div class="court-card">
                                <div class="court-image">
                                    <i class="fas fa-futbol fa-3x"></i>
                                    <div class="referral-badge">Referida</div>
                                </div>
                                <div class="court-info">
                                    <h4 class="court-name">Urquiza Fútbol Park</h4>
                                    <div class="court-location">Villa Urquiza</div>
                                    <div class="court-address">Av. Triunvirato 3300</div>
                                    <div class="court-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="rating-text">4.6</span>
                                    </div>
                                    <div class="court-features">
                                        <span class="feature-tag">5v5</span>
                                        <span class="feature-tag">Sintético</span>
                                        <span class="feature-tag">Iluminación</span>
                                    </div>
                                    <div class="available-times">
                                        <button class="time-btn">Hoy 20:30</button>
                                        <button class="time-btn">Mañana 19:00</button>
                                        <button class="time-btn">Dom 17:30</button>
                                    </div>
                                    <div class="court-actions">
                                        <span class="price-range">$$</span>
                                        <a href="#" class="btn-reserve">Reservar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Todas las Canchas -->
                    <div class="recommended-section">
                        <h3 class="section-title">
                            <i class="fas fa-list"></i>
                            Todas las Canchas
                        </h3>
                        <div class="courts-horizontal">
                            <!-- Cancha 1 -->
                            <div class="court-card-horizontal">
                                <div class="court-image-horizontal">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                                <div class="court-info-horizontal">
                                    <div class="court-header-horizontal">
                                        <div>
                                            <h4 class="court-name-horizontal">Cancha San Telmo</h4>
                                            <div class="court-location-horizontal">San Telmo</div>
                                            <div class="court-address-horizontal">Defensa 1200</div>
                                        </div>
                                        <span class="price-range">$$</span>
                                    </div>
                                    <div class="court-details-horizontal">
                                        <div class="court-rating-horizontal">
                                            <div class="stars">★★★★☆</div>
                                            <span class="rating-text">4.1</span>
                                        </div>
                                        <div class="court-features-horizontal">
                                            <span class="feature-tag">5v5</span>
                                            <span class="feature-tag">Sintético</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="court-actions-horizontal">
                                    <div class="available-times-horizontal">
                                        <button class="time-btn">Hoy 20:00</button>
                                        <button class="time-btn">Mañana 19:00</button>
                                    </div>
                                    <a href="#" class="btn-reserve-horizontal">Reservar</a>
                                </div>
                            </div>
                            
                            <!-- Cancha 2 -->
                            <div class="court-card-horizontal">
                                <div class="court-image-horizontal">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                                <div class="court-info-horizontal">
                                    <div class="court-header-horizontal">
                                        <div>
                                            <h4 class="court-name-horizontal">Club Belgrano</h4>
                                            <div class="court-location-horizontal">Belgrano</div>
                                            <div class="court-address-horizontal">Monroe 1800</div>
                                        </div>
                                        <span class="price-range">$$$</span>
                                    </div>
                                    <div class="court-details-horizontal">
                                        <div class="court-rating-horizontal">
                                            <div class="stars">★★★★★</div>
                                            <span class="rating-text">4.8</span>
                                        </div>
                                        <div class="court-features-horizontal">
                                            <span class="feature-tag">7v7</span>
                                            <span class="feature-tag">Natural</span>
                                            <span class="feature-tag">Techada</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="court-actions-horizontal">
                                    <div class="available-times-horizontal">
                                        <button class="time-btn">Hoy 21:00</button>
                                        <button class="time-btn">Vie 20:00</button>
                                    </div>
                                    <a href="#" class="btn-reserve-horizontal">Reservar</a>
                                </div>
                            </div>
                            
                            <!-- Cancha 3 -->
                            <div class="court-card-horizontal">
                                <div class="court-image-horizontal">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                                <div class="court-info-horizontal">
                                    <div class="court-header-horizontal">
                                        <div>
                                            <h4 class="court-name-horizontal">Fútbol 5 Flores</h4>
                                            <div class="court-location-horizontal">Flores</div>
                                            <div class="court-address-horizontal">Av. Rivadavia 4500</div>
                                        </div>
                                        <span class="price-range">$</span>
                                    </div>
                                    <div class="court-details-horizontal">
                                        <div class="court-rating-horizontal">
                                            <div class="stars">★★★☆☆</div>
                                            <span class="rating-text">3.5</span>
                                        </div>
                                        <div class="court-features-horizontal">
                                            <span class="feature-tag">5v5</span>
                                            <span class="feature-tag">Sintético</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="court-actions-horizontal">
                                    <div class="available-times-horizontal">
                                        <button class="time-btn">Hoy 19:30</button>
                                        <button class="time-btn">Sáb 18:00</button>
                                    </div>
                                    <a href="#" class="btn-reserve-horizontal">Reservar</a>
                                </div>
                            </div>
                            
                            <!-- Cancha 4 -->
                            <div class="court-card-horizontal">
                                <div class="court-image-horizontal">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                                <div class="court-info-horizontal">
                                    <div class="court-header-horizontal">
                                        <div>
                                            <h4 class="court-name-horizontal">Deportivo Almagro</h4>
                                            <div class="court-location-horizontal">Almagro</div>
                                            <div class="court-address-horizontal">Corrientes 3500</div>
                                        </div>
                                        <span class="price-range">$$</span>
                                    </div>
                                    <div class="court-details-horizontal">
                                        <div class="court-rating-horizontal">
                                            <div class="stars">★★★★☆</div>
                                            <span class="rating-text">4.3</span>
                                        </div>
                                        <div class="court-features-horizontal">
                                            <span class="feature-tag">6v6</span>
                                            <span class="feature-tag">Sintético</span>
                                            <span class="feature-tag">Iluminación</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="court-actions-horizontal">
                                    <div class="available-times-horizontal">
                                        <button class="time-btn">Hoy 20:30</button>
                                        <button class="time-btn">Dom 17:00</button>
                                    </div>
                                    <a href="#" class="btn-reserve-horizontal">Reservar</a>
                                </div>
                            </div>
                            
                            <!-- Cancha 5 -->
                            <div class="court-card-horizontal">
                                <div class="court-image-horizontal">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                                <div class="court-info-horizontal">
                                    <div class="court-header-horizontal">
                                        <div>
                                            <h4 class="court-name-horizontal">Cancha Boedo</h4>
                                            <div class="court-location-horizontal">Boedo</div>
                                            <div class="court-address-horizontal">San Juan 2800</div>
                                        </div>
                                        <span class="price-range">$</span>
                                    </div>
                                    <div class="court-details-horizontal">
                                        <div class="court-rating-horizontal">
                                            <div class="stars">★★★★☆</div>
                                            <span class="rating-text">4.0</span>
                                        </div>
                                        <div class="court-features-horizontal">
                                            <span class="feature-tag">5v5</span>
                                            <span class="feature-tag">Natural</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="court-actions-horizontal">
                                    <div class="available-times-horizontal">
                                        <button class="time-btn">Hoy 21:30</button>
                                        <button class="time-btn">Lun 20:00</button>
                                    </div>
                                    <a href="#" class="btn-reserve-horizontal">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
