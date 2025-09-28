@extends('layouts.app')

@section('title', 'FaltaUno - Encuentra jugadores, reserva canchas, juega al fútbol')

@section('content')

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast.success('{{ session('success') }}');
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast.error('{{ session('error') }}');
        });
    </script>
@endif

<!-- Botón de prueba para verificar notificaciones -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Crear botón de prueba
    const testButton = document.createElement('button');
    testButton.innerHTML = '🧪 Probar Notificaciones';
    testButton.style.cssText = `
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 10000;
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
    `;
    testButton.onclick = function() {
        if (window.showToast) {
            showToast.success('¡Notificación de prueba funcionando!');
            showToast.error('Error de prueba');
            showToast.warning('Advertencia de prueba');
            showToast.info('Información de prueba');
        } else {
            alert('showToast no está disponible');
        }
    };
    document.body.appendChild(testButton);
});
</script>
<style>
    * {
        box-sizing: border-box;
    }
    
    body {
        overflow-x: hidden;
    }
    
    .container {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
        padding: 120px 0 80px 0;
        padding-left: 80px;
        padding-right: 80px;
        overflow-x: hidden;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: bold;
        color: var(--primary-green);
        line-height: 1.2;
        margin-bottom: 1.5rem;
        word-wrap: break-word;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 2rem;
        word-wrap: break-word;
    }
    
    .search-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .search-input {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        font-size: 1rem;
    }
    
    .search-input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .search-btn {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .search-btn:hover {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
    }
    
    .action-btn {
        background-color: var(--accent-orange);
        border-color: var(--accent-orange);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-right: 1rem;
        max-width: 100%;
        white-space: nowrap;
    }
    
    .action-btn:hover {
        background-color: #e67a2e;
        border-color: #e67a2e;
        color: white;
    }
    
    .map-btn {
        background-color: transparent;
        border: 2px solid #ddd;
        color: #666;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .map-btn:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    
    .stats-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .map-placeholder {
        background: linear-gradient(45deg, #e8f5e8, #d4edda);
        border-radius: 12px;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
        font-size: 1.2rem;
        position: relative;
        overflow: hidden;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-green);
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: var(--accent-orange);
        border-radius: 2px;
    }
    
    .step-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
        height: 100%;
    }
    
    .step-icon {
        width: 80px;
        height: 80px;
        background: var(--light-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: var(--primary-green);
    }
    
    .step-number {
        background: var(--primary-green);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-weight: bold;
    }
    
    .how-it-works {
        background: white;
        padding: 4rem 0;
    }
    
    .featured-section {
        background: var(--light-beige);
        padding: 4rem 0;
    }
    
    .recent-section {
        background: var(--light-green);
        padding: 4rem 0;
    }
    
    .testimonials-section {
        background: var(--light-beige);
        padding: 4rem 0;
    }
    
    .pitch-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        height: 100%;
    }
    
    .pitch-card:hover {
        transform: translateY(-5px);
    }
    
    .pitch-image {
        height: 200px;
        background: linear-gradient(45deg, var(--secondary-green), #6b9b6b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        position: relative;
    }
    
    .pitch-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="50" r="2" fill="%23ffffff" opacity="0.3"/><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="80" cy="30" r="1.5" fill="%23ffffff" opacity="0.2"/><circle cx="30" cy="80" r="1" fill="%23ffffff" opacity="0.2"/><circle cx="70" cy="70" r="1" fill="%23ffffff" opacity="0.2"/></svg>');
    }
    
    .pitch-info {
        padding: 1.5rem;
        background: var(--light-green);
    }
    
    .pitch-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .pitch-location {
        color: var(--secondary-green);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .pitch-price {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--primary-green);
        text-align: right;
    }
    
    .match-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-left: 4px solid var(--primary-green);
        height: 100%;
    }
    
    .match-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }
    
    .match-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .match-tag {
        background: #f8f9fa;
        color: #666;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .match-price {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--primary-green);
    }
    
    .testimonial-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
        height: 100%;
    }
    
    .testimonial-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--secondary-green), #6b9b6b);
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }
    
    .testimonial-quote {
        font-style: italic;
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .testimonial-name {
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.25rem;
    }
    
    .testimonial-role {
        color: #999;
        font-size: 0.9rem;
    }
    
    .referral-section {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        padding: 4rem 0;
        color: white;
        text-align: center;
    }
    
    .referral-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    
    .referral-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .referral-benefits {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        backdrop-filter: blur(10px);
    }
    
    .btn-referral {
        background: var(--accent-orange);
        border: none;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 140, 66, 0.3);
    }
    
    .btn-referral:hover {
        background: #e67a2e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 140, 66, 0.4);
        color: white;
    }
    
    .cta-section {
        background: var(--light-green);
        padding: 4rem 0;
        text-align: center;
    }
    
    .cta-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }
    
    .cta-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 2rem;
    }
    
    .cta-features {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-green);
        font-weight: 500;
    }
    
    .feature-item i {
        color: var(--accent-orange);
        font-size: 1.2rem;
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-register {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-register:hover {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-download {
        background-color: transparent;
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-download:hover {
        background-color: var(--primary-green);
        color: white;
        transform: translateY(-2px);
    }
    
    .cta-visual {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .phone-mockup {
        width: 200px;
        height: 400px;
        background: #333;
        border-radius: 25px;
        padding: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        position: relative;
    }
    
    .phone-screen {
        width: 100%;
        height: 100%;
        background: white;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .app-preview {
        padding: 1rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .app-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-green);
        font-weight: bold;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    
    .app-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .match-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: var(--light-green);
        border-radius: 8px;
        font-size: 0.8rem;
    }
    
    .match-time {
        background: var(--primary-green);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: bold;
        min-width: 40px;
        text-align: center;
    }
    
    .match-info {
        flex: 1;
    }
    
    .match-title {
        font-weight: bold;
        color: var(--primary-green);
        font-size: 0.9rem;
    }
    
    .match-location {
        color: #666;
        font-size: 0.8rem;
    }
    
    .match-price {
        color: var(--accent-orange);
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    @media (max-width: 1200px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .phone-mockup {
            width: 180px;
            height: 360px;
        }
    }
    
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .phone-mockup {
            width: 160px;
            height: 320px;
        }
        
        .cta-features {
            flex-direction: column;
            gap: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-section {
            padding: 100px 0 60px 0;
        }
        
        .search-container {
            padding: 1rem;
        }
        
        .action-btn, .map-btn {
            width: 48%;
            margin-bottom: 0.5rem;
            margin-right: 2%;
        }
        
        .action-btn:last-child, .map-btn:last-child {
            margin-right: 0;
        }
        
        .stats-item {
            justify-content: center;
            margin-bottom: 0.5rem;
        }
        
        .referral-benefits {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .referral-title {
            font-size: 1.8rem;
        }
        
        .referral-subtitle {
            font-size: 1rem;
        }
        
        .cta-title {
            font-size: 1.8rem;
        }
        
        .cta-subtitle {
            font-size: 1rem;
        }
        
        .cta-features {
            flex-direction: column;
            gap: 1rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-register, .btn-download {
            width: 100%;
            max-width: 300px;
        }
        
        .phone-mockup {
            width: 140px;
            height: 280px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .hero-section {
            padding: 80px 0 40px 0;
        }
        
        .search-container {
            padding: 0.75rem;
        }
        
        .search-input {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .search-btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .referral-title {
            font-size: 1.5rem;
        }
        
        .cta-title {
            font-size: 1.5rem;
        }
        
        .phone-mockup {
            width: 120px;
            height: 240px;
        }
    }
            </style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    Encuentra jugadores,<br>
                    reserva canchas,<br>
                    juega al fútbol
                </h1>
                <p class="hero-subtitle">
                    Une equipos, descubre partidos cerca de ti y gestiona tus reservas en una sola plataforma.
                </p>
                
                <!-- Search Container -->
                <div class="search-container">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <input type="text" class="form-control search-input border-start-0" 
                                       placeholder="Ingresa ubicación (ej. Palermo, Buenos Aires)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn search-btn w-100">
                                <i class="fas fa-search me-2"></i>
                                Encontrar partidos cerca de mí
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mb-4">
                    <a href="#" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        Crear publicación
                    </a>
                    <a href="#" class="map-btn">
                        <i class="fas fa-map"></i>
                        Ver mapa
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-item">
                            <i class="fas fa-users text-primary"></i>
                            <span>1.5k+ jugadores</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-item">
                            <i class="fas fa-futbol text-primary"></i>
                            <span>120+ canchas</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-item">
                            <i class="fas fa-calendar text-primary"></i>
                            <span>300+ partidos/sem</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="col-lg-6">
                <div id="map" style="height: 400px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);"></div>
            </div>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">Cómo funciona</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="mb-3">Buscar</h4>
                    <p class="text-muted">Explora partidos y canchas por ubicación, fecha y nivel.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h4 class="mb-3">Unirse/Reservar</h4>
                    <p class="text-muted">Únete a un partido o reserva una cancha con pocos clics.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h4 class="mb-3">Jugar</h4>
                    <p class="text-muted">Confirma tu asistencia, paga y disfruta del juego.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courts Section -->
<section class="featured-section">
    <div class="container">
        <h2 class="section-title">Canchas destacadas</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="pitch-card">
                    <div class="pitch-image">
                        <i class="fas fa-futbol fa-3x"></i>
                    </div>
                    <div class="pitch-info">
                        <div class="pitch-name">F5 Arena Palermo</div>
                        <div class="pitch-location">Palermo</div>
                        <div class="pitch-price">$20/h</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pitch-card">
                    <div class="pitch-image">
                        <i class="fas fa-futbol fa-3x"></i>
                    </div>
                    <div class="pitch-info">
                        <div class="pitch-name">Parque Norte 7</div>
                        <div class="pitch-location">Nuñez</div>
                        <div class="pitch-price">$18/h</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pitch-card">
                    <div class="pitch-image">
                        <i class="fas fa-futbol fa-3x"></i>
                    </div>
                    <div class="pitch-info">
                        <div class="pitch-name">Villa Urquiza Club</div>
                        <div class="pitch-location">Villa Urquiza</div>
                        <div class="pitch-price">$22/h</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Matches Section -->
<section class="recent-section">
    <div class="container">
        <h2 class="section-title">Partidos recientes</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="match-card">
                    <div class="match-title">Mixto 6v6 en F5 Arena</div>
                    <div class="match-tags">
                        <span class="match-tag">Hoy 20:00</span>
                        <span class="match-tag">Nivel intermedio</span>
                        <span class="match-tag">3 jugadores</span>
                    </div>
                    <div class="match-price">$5 p/p</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="match-card">
                    <div class="match-title">Pickup 5v5 Nuñez</div>
                    <div class="match-tags">
                        <span class="match-tag">Mañana 19:30</span>
                        <span class="match-tag">Todos los niveles</span>
                        <span class="match-tag">2 jugadores</span>
                    </div>
                    <div class="match-price">$4 p/p</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="match-card">
                    <div class="match-title">Torneo relámpago Villa Urquiza</div>
                    <div class="match-tags">
                        <span class="match-tag">Sáb 17:00</span>
                        <span class="match-tag">Avanzado</span>
                        <span class="match-tag">5 cupos</span>
                    </div>
                    <div class="match-price">$10 p/p</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-title">Lo que dicen los jugadores</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="testimonial-quote">
                        "Encontré partidos cada semana cerca de casa. La reserva es rapidísima."
                    </div>
                    <div class="testimonial-name">Lucía</div>
                    <div class="testimonial-role">mediocampista</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="testimonial-quote">
                        "Como propietario, gestiono horarios y pagos sin complicaciones."
                    </div>
                    <div class="testimonial-name">Diego</div>
                    <div class="testimonial-role">dueño de cancha</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="testimonial-quote">
                        "Los filtros por nivel y ubicación facilitan encontrar el partido ideal."
                    </div>
                    <div class="testimonial-name">Camila</div>
                    <div class="testimonial-role">delantera</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Referral Section -->
<section class="referral-section">
    <div class="container">
        <h2 class="referral-title">¿Quieres que tu cancha sea parte de FaltaUno?</h2>
        <p class="referral-subtitle">Únete a nuestro plan de referidos y aumenta tus reservas</p>
        
        <div class="referral-benefits">
            <div class="benefit-item">
                <i class="fas fa-chart-line"></i>
                <span>Más reservas</span>
            </div>
            <div class="benefit-item">
                <i class="fas fa-dollar-sign"></i>
                <span>Suscripcion mensual</span>
            </div>
            <div class="benefit-item">
                <i class="fas fa-dollar-sign"></i>
                <span>Prueba gratuita</span>
            </div>
            <div class="benefit-item">
                <i class="fas fa-users"></i>
                <span>Acceso a 1.5k+ jugadores</span>
            </div>
            <div class="benefit-item">
                <i class="fas fa-calendar-check"></i>
                <span>Recomendacion dentro del sitio</span>
            </div>
        </div>
        
        <a href="#referral" class="btn-referral">
            <i class="fas fa-handshake"></i>
            Únete al plan de referidos
        </a>
                </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="cta-title">¿Listo para tu próximo partido?</h3>
                <p class="cta-subtitle">Encuentra jugadores, reserva canchas, juega fútbol</p>
                
                <!-- CTA Features -->
                <div class="cta-features">
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>Reserva en 2 minutos</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Pago seguro</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-mobile-alt"></i>
                        <span>App móvil</span>
                </div>
        </div>

                <div class="cta-buttons">
                    <a href="#" class="btn-register">
                        <i class="fas fa-futbol"></i>
                        Empezar ahora
                    </a>
                    <a href="#" class="btn-download">
                        <i class="fas fa-download"></i>
                        Descargar app
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="cta-visual">
                    <div class="phone-mockup">
                        <div class="phone-screen">
                            <div class="app-preview">
                                <div class="app-header">
                                    <i class="fas fa-futbol"></i>
                                    <span>FaltaUno</span>
                                </div>
                                <div class="app-content">
                                    <div class="match-item">
                                        <div class="match-time">20:00</div>
                                        <div class="match-info">
                                            <div class="match-title">Mixto 6v6</div>
                                            <div class="match-location">F5 Arena</div>
                                        </div>
                                        <div class="match-price">$5</div>
                                    </div>
                                    <div class="match-item">
                                        <div class="match-time">19:30</div>
                                        <div class="match-info">
                                            <div class="match-title">Pickup 5v5</div>
                                            <div class="match-location">Nuñez</div>
                                        </div>
                                        <div class="match-price">$4</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Coordenadas por defecto (Mar del Plata - Costa Atlántica)
    const defaultLat = -38.0023;
    const defaultLon = -57.5575;
    
    // Inicializar mapa
    const map = L.map('map').setView([defaultLat, defaultLon], 13);
    
    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Icono personalizado para canchas de fútbol
    const footballIcon = L.icon({
        iconUrl: 'data:image/svg+xml;base64,' + btoa(`
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                <circle cx="16" cy="16" r="14" fill="#2d5a27" stroke="white" stroke-width="2"/>
                <path d="M16 4 L20 12 L28 12 L22 18 L24 26 L16 22 L8 26 L10 18 L4 12 L12 12 Z" fill="white"/>
            </svg>
        `),
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -16]
    });
    
    // Función para buscar canchas de fútbol usando Overpass API
    function searchFootballPitches(lat, lon, radius = 10000) {
        const overpassUrl = 'https://overpass-api.de/api/interpreter';
        const query = `
            [out:json][timeout:60];
            (
                node["leisure"="pitch"]["sport"="soccer"](around:${radius},${lat},${lon});
                way["leisure"="pitch"]["sport"="soccer"](around:${radius},${lat},${lon});
                relation["leisure"="pitch"]["sport"="soccer"](around:${radius},${lat},${lon});
            );
            out center;
        `;
        
        // Mostrar indicador de carga
        const loadingPopup = L.popup()
            .setLatLng([lat, lon])
            .setContent(`
                <div style="text-align: center; min-width: 200px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; color: #2d5a27;"></i><br>
                    <strong>Buscando canchas...</strong><br>
                    <small>Radio: ${radius/1000} km</small>
                </div>
            `)
            .openOn(map);
        
        fetch(overpassUrl, {
            method: 'POST',
            body: query,
            headers: {
                'Content-Type': 'text/plain'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Cerrar popup de carga
            map.closePopup();
            
            console.log('Datos recibidos:', data);
            
            // Limpiar marcadores anteriores
            map.eachLayer(layer => {
                if (layer instanceof L.Marker && layer !== map._layers[Object.keys(map._layers)[0]]) {
                    map.removeLayer(layer);
                }
            });
            
            // Agregar marcadores para cada cancha encontrada
            data.elements.forEach(element => {
                let lat, lon;
                
                if (element.type === 'node') {
                    lat = element.lat;
                    lon = element.lon;
                } else if (element.center) {
                    lat = element.center.lat;
                    lon = element.center.lon;
                }
                
                if (lat && lon) {
                    const name = element.tags.name || 'Cancha de Fútbol';
                    const surface = element.tags.surface || 'No especificado';
                    const access = element.tags.access || 'No especificado';
                    const operator = element.tags.operator || '';
                    const fee = element.tags.fee || '';
                    const opening_hours = element.tags.opening_hours || '';
                    
                    // Determinar el tipo de acceso basado en los tags reales
                    let accessType = 'No especificado';
                    let accessColor = '#666';
                    
                    if (element.tags.access === 'yes' || element.tags.access === 'public') {
                        accessType = 'Público';
                        accessColor = '#28a745';
                    } else if (element.tags.access === 'private') {
                        accessType = 'Privado';
                        accessColor = '#dc3545';
                    } else if (element.tags.access === 'customers') {
                        accessType = 'Solo clientes';
                        accessColor = '#ffc107';
                    } else if (element.tags.access === 'permissive') {
                        accessType = 'Permisivo';
                        accessColor = '#17a2b8';
                    }
                    
                    const marker = L.marker([lat, lon], { icon: footballIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="min-width: 220px;">
                                <h6 style="margin: 0 0 8px 0; color: #2d5a27; font-weight: bold;">${name}</h6>
                                <p style="margin: 4px 0; font-size: 0.9rem;"><strong>Superficie:</strong> ${surface}</p>
                                <p style="margin: 4px 0; font-size: 0.9rem;"><strong>Acceso:</strong> <span style="color: ${accessColor};">${accessType}</span></p>
                                ${operator ? `<p style="margin: 4px 0; font-size: 0.9rem;"><strong>Operador:</strong> ${operator}</p>` : ''}
                                ${fee ? `<p style="margin: 4px 0; font-size: 0.9rem;"><strong>Tarifa:</strong> ${fee}</p>` : ''}
                                ${opening_hours ? `<p style="margin: 4px 0; font-size: 0.9rem;"><strong>Horarios:</strong> ${opening_hours}</p>` : ''}
                                <button class="btn btn-sm btn-success" style="margin-top: 8px; width: 100%;" onclick="reservarCancha('${name}', ${lat}, ${lon})">
                                    <i class="fas fa-calendar-plus"></i> Reservar
                                </button>
                            </div>
                        `);
                }
            });
            
            // Ajustar vista para mostrar todas las canchas
            if (data.elements.length > 0) {
                const group = new L.featureGroup();
                data.elements.forEach(element => {
                    let lat, lon;
                    if (element.type === 'node') {
                        lat = element.lat;
                        lon = element.lon;
                    } else if (element.center) {
                        lat = element.center.lat;
                        lon = element.center.lon;
                    }
                    if (lat && lon) {
                        group.addLayer(L.marker([lat, lon]));
                    }
                });
                map.fitBounds(group.getBounds().pad(0.1));
                
                // Mostrar mensaje de éxito
                L.popup()
                    .setLatLng([lat, lon])
                    .setContent(`
                        <div style="text-align: center; color: #28a745;">
                            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i><br>
                            <strong>¡Canchas encontradas!</strong><br>
                            <small>${data.elements.length} cancha(s) en ${radius/1000} km</small>
                        </div>
                    `)
                    .openOn(map);
                
                // Cerrar mensaje después de 3 segundos
                setTimeout(() => {
                    map.closePopup();
                }, 3000);
            } else {
                // No se encontraron canchas
                L.popup()
                    .setLatLng([lat, lon])
                    .setContent(`
                        <div style="text-align: center; color: #ffc107;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem;"></i><br>
                            <strong>No se encontraron canchas</strong><br>
                            <small>Intenta con un radio más amplio</small>
                        </div>
                    `)
                    .openOn(map);
            }
        })
        .catch(error => {
            console.error('Error buscando canchas:', error);
            // Cerrar popup de carga
            map.closePopup();
            
            // Mostrar mensaje de error
            L.popup()
                .setLatLng([lat, lon])
                .setContent(`
                    <div style="text-align: center; color: #dc3545;">
                        <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i><br>
                        <strong>Error al cargar canchas</strong><br>
                        <small>Intenta nuevamente en unos momentos</small>
                    </div>
                `)
                .openOn(map);
        });
    }
    
    // Función para obtener ubicación del usuario
    function getUserLocation() {
        if (navigator.geolocation) {
            // Mostrar mensaje de carga
            const loadingPopup = L.popup()
                .setLatLng([defaultLat, defaultLon])
                .setContent('<div style="text-align: center;"><i class="fas fa-spinner fa-spin"></i><br>Detectando tu ubicación...</div>')
                .openOn(map);
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    console.log('Ubicación detectada:', lat, lon);
                    
                    // Cerrar popup de carga
                    map.closePopup();
                    
                    map.setView([lat, lon], 12);
                    searchFootballPitches(lat, lon, 10000);
                },
                function(error) {
                    console.log('Error obteniendo ubicación:', error);
                    
                    // Cerrar popup de carga
                    map.closePopup();
                    
                    // Mostrar mensaje informativo
                    const errorPopup = L.popup()
                        .setLatLng([defaultLat, defaultLon])
                        .setContent(`
                            <div style="text-align: center;">
                                <i class="fas fa-map-marker-alt" style="color: #2d5a27;"></i><br>
                                <strong>Ubicación no detectada</strong><br>
                                <small>Mostrando canchas en Mar del Plata</small>
                            </div>
                        `)
                        .openOn(map);
                    
                    // Usar ubicación por defecto (Mar del Plata)
                    searchFootballPitches(defaultLat, defaultLon, 10000);
                    
                    // Cerrar popup después de 3 segundos
                    setTimeout(() => {
                        map.closePopup();
                    }, 3000);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutos
                }
            );
        } else {
            // Navegador no soporta geolocalización
            const noGeolocationPopup = L.popup()
                .setLatLng([defaultLat, defaultLon])
                .setContent(`
                    <div style="text-align: center;">
                        <i class="fas fa-exclamation-triangle" style="color: #ff8c42;"></i><br>
                        <strong>Geolocalización no disponible</strong><br>
                        <small>Mostrando canchas en Mar del Plata</small>
                    </div>
                `)
                .openOn(map);
            
            // Usar ubicación por defecto
            searchFootballPitches(defaultLat, defaultLon, 10000);
            
            // Cerrar popup después de 3 segundos
            setTimeout(() => {
                map.closePopup();
            }, 3000);
        }
    }
    
    // Función para reservar cancha (placeholder)
    window.reservarCancha = function(name, lat, lon) {
        alert(`Función de reserva para: ${name}\nCoordenadas: ${lat}, ${lon}\n\nEsta funcionalidad se implementará próximamente.`);
    };
    
    // Buscar canchas al cargar la página
    getUserLocation();
    
    // Agregar botón para buscar canchas cerca
    const searchButton = document.querySelector('.search-btn');
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            const searchInput = document.querySelector('.search-input');
            const location = searchInput.value.trim();
            
            if (location) {
                // Aquí podrías integrar geocoding para convertir texto a coordenadas
                alert(`Buscando canchas cerca de: ${location}\n\nEsta funcionalidad se implementará próximamente.`);
            } else {
                // Mostrar opciones de ubicación y radio
                const locationOptions = L.popup()
                    .setLatLng([defaultLat, defaultLon])
                    .setContent(`
                        <div style="text-align: center; min-width: 280px;">
                            <h6 style="margin: 0 0 15px 0; color: #2d5a27;">Buscar canchas de fútbol</h6>
                            
                            <div style="margin-bottom: 15px;">
                                <label style="font-size: 0.9rem; color: #666; margin-bottom: 8px; display: block;">Radio de búsqueda:</label>
                                <select id="searchRadius" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="5000">5 km (Cerca)</option>
                                    <option value="10000" selected>10 km (Recomendado)</option>
                                    <option value="15000">15 km (Amplio)</option>
                                    <option value="25000">25 km (Muy amplio)</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-success btn-sm" onclick="searchWithUserLocation()" style="margin: 5px; width: 100%;">
                                <i class="fas fa-location-arrow"></i> Usar mi ubicación
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="searchWithDefaultLocation()" style="margin: 5px; width: 100%;">
                                <i class="fas fa-map-marker-alt"></i> Mar del Plata
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="map.closePopup()" style="margin: 5px; width: 100%;">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    `)
                    .openOn(map);
            }
        });
    }
    
    // Funciones para búsqueda con radio personalizado
    function searchWithUserLocation() {
        const radius = document.getElementById('searchRadius').value;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    map.closePopup();
                    map.setView([lat, lon], 12);
                    searchFootballPitches(lat, lon, parseInt(radius));
                },
                function(error) {
                    console.log('Error obteniendo ubicación:', error);
                    map.closePopup();
                    searchFootballPitches(defaultLat, defaultLon, parseInt(radius));
                }
            );
        } else {
            map.closePopup();
            searchFootballPitches(defaultLat, defaultLon, parseInt(radius));
        }
    }
    
    function searchWithDefaultLocation() {
        const radius = document.getElementById('searchRadius').value;
        map.closePopup();
        searchFootballPitches(defaultLat, defaultLon, parseInt(radius));
    }
    
    // Hacer funciones globales para los botones
    window.getUserLocation = getUserLocation;
    window.searchFootballPitches = searchFootballPitches;
    window.searchWithUserLocation = searchWithUserLocation;
    window.searchWithDefaultLocation = searchWithDefaultLocation;
});
</script>

@endsection