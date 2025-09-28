@extends('layouts.app')

@section('title', 'Mi Perfil - FaltaUno')

@section('content')
<style>
    .profile-page {
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
    
    .sidebar .nav-item {
        display: block;
        padding: 0.75rem 1rem;
        color: #666;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .sidebar .nav-item:hover {
        background: var(--light-green);
        color: var(--primary-green);
    }
    
    .sidebar .nav-item.active {
        background: var(--primary-green);
        color: white;
    }
    
    .main-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .profile-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--primary-green);
        margin: 0;
    }
    
    .verified-badge {
        background: var(--accent-orange);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .profile-section {
        background: var(--light-green);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .profile-info {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        position: relative;
        flex-shrink: 0;
    }
    
    .avatar-actions {
        position: absolute;
        bottom: -10px;
        right: -10px;
        display: flex;
        gap: 0.5rem;
    }
    
    .avatar-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-orange);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .avatar-btn:hover {
        background: #e67a2e;
        transform: scale(1.1);
    }
    
    .profile-details {
        flex: 1;
        min-width: 300px;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .profile-status {
        color: var(--secondary-green);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .profile-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-action:hover {
        background: var(--secondary-green);
        color: white;
    }
    
    .btn-action.secondary {
        background: transparent;
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
    }
    
    .btn-action.secondary:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-input {
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        border-color: var(--primary-green);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .form-textarea {
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        resize: vertical;
        min-height: 80px;
        transition: all 0.3s ease;
    }
    
    .form-textarea:focus {
        border-color: var(--primary-green);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }
    
    .btn-save {
        background: var(--primary-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        background: var(--secondary-green);
    }
    
    .btn-cancel {
        background: transparent;
        color: #666;
        border: 1px solid #ddd;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #f8f9fa;
        color: var(--primary-green);
    }
    
    .preferences-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .payment-method {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .payment-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .payment-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--light-green);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
    }
    
    .payment-details h4 {
        margin: 0;
        font-size: 1rem;
        color: var(--primary-green);
    }
    
    .payment-details p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }
    
    .payment-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-edit {
        background: var(--light-green);
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-edit:hover {
        background: var(--primary-green);
        color: white;
    }
    
    .btn-delete {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }
    
    .notification-settings {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .notification-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    
    .notification-info h4 {
        margin: 0;
        font-size: 1rem;
        color: var(--primary-green);
    }
    
    .notification-info p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }
    
    .notification-status {
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .security-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .cta-section {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        margin-top: 2rem;
    }
    
    .cta-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .cta-text {
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }
    
    .btn-cta {
        background: white;
        color: var(--primary-green);
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 auto;
    }
    
    .btn-cta:hover {
        background: var(--light-green);
        transform: translateY(-2px);
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
    
    @media (max-width: 768px) {
        .profile-page {
            padding: 100px 0 60px 0;
        }
        
        .sidebar {
            position: static;
            margin-bottom: 2rem;
        }
        
        .profile-info {
            flex-direction: column;
            text-align: center;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .preferences-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .profile-page {
            padding: 80px 0 40px 0;
        }
    }
</style>

<div class="profile-page">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">
                            <i class="fas fa-user"></i>
                            Mi Cuenta
                        </h3>
                        <a href="#personal" class="nav-item active">
                            <i class="fas fa-user"></i>
                            Información Personal
                        </a>
                        <a href="#preferences" class="nav-item">
                            <i class="fas fa-cog"></i>
                            Preferencias
                        </a>
                        <a href="#security" class="nav-item">
                            <i class="fas fa-shield-alt"></i>
                            Seguridad
                        </a>
                    </div>
                    
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">
                            <i class="fas fa-chart-bar"></i>
                            Estadísticas
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
                    <div class="profile-header">
                        <div>
                            <h2 class="profile-title">Mi Perfil</h2>
                            <p class="text-muted">EL LUGAR DONDE SIEMPRE HAY LUGAR</p>
                        </div>
                        <div class="verified-badge">
                            <i class="fas fa-check"></i>
                            Verificado
                        </div>
                    </div>
                    
                    <!-- Información Personal -->
                    <div class="profile-section" id="personal">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Información Personal
                        </h3>
                        
                        <div class="profile-info">
                            <div class="profile-avatar">
                                JP
                                <div class="avatar-actions">
                                    <button class="avatar-btn" title="Cambiar foto">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="profile-details">
                                <h4 class="profile-name">Juan Pérez</h4>
                                <p class="profile-status">Organizador frecuente</p>
                                <div class="profile-actions">
                                    <a href="#" class="btn-action">
                                        <i class="fas fa-eye"></i>
                                        Ver perfil público
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-input" value="Juan">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-input" value="Pérez">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-input" value="juan.perez@mail.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-input" value="+54 11 5555 5555">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-input" value="CABA">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Posición favorita</label>
                                <select class="form-input">
                                    <option>Delantero</option>
                                    <option>Mediocampista</option>
                                    <option>Defensor</option>
                                    <option>Arquero</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea class="form-textarea" placeholder="Cuéntanos sobre ti...">Juego los martes y jueves. Busco partidos de 5v5 en Palermo.</textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-cancel">Cancelar</button>
                            <button class="btn-save">Guardar cambios</button>
                        </div>
                    </div>
                    
                    <!-- Preferencias de Juego -->
                    <div class="profile-section" id="preferences">
                        <h3 class="section-title">
                            <i class="fas fa-futbol"></i>
                            Preferencias de Juego
                        </h3>
                        
                        <div class="preferences-grid">
                            <div class="form-group">
                                <label class="form-label">Formato</label>
                                <select class="form-input">
                                    <option>5v5, 7v7</option>
                                    <option>5v5</option>
                                    <option>7v7</option>
                                    <option>11v11</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Días preferidos</label>
                                <input type="text" class="form-input" value="Mar, Jue">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Franja horaria</label>
                                <input type="text" class="form-input" value="19:00 - 22:00">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Zona</label>
                                <input type="text" class="form-input" value="Palermo / Chacarita">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-save">
                                <i class="fas fa-list"></i>
                                Guardar preferencias
                            </button>
                        </div>
                    </div>
                    
                    <!-- Métodos de Pago -->
                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="fas fa-credit-card"></i>
                            Métodos de Pago
                        </h3>
                        <p class="text-muted mb-3">El pago lo gestiona únicamente el organizador. Puedes guardar datos para agilizar reservas.</p>
                        
                        <div class="payment-methods">
                            <div class="payment-method">
                                <div class="payment-info">
                                    <div class="payment-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="payment-details">
                                        <h4>Tarjeta terminada en **** 4242</h4>
                                        <p>Visa • Expira 12/25</p>
                                    </div>
                                </div>
                                <div class="payment-actions">
                                    <button class="btn-edit">Editar</button>
                                    <button class="btn-delete">Eliminar</button>
                                </div>
                            </div>
                            
                            <div class="payment-method">
                                <div class="payment-info">
                                    <div class="payment-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="payment-details">
                                        <h4>Alias: JUANP.F5</h4>
                                        <p>Transferencia bancaria</p>
                                    </div>
                                </div>
                                <div class="payment-actions">
                                    <button class="btn-edit">Editar</button>
                                    <button class="btn-delete">Eliminar</button>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn-save">
                            <i class="fas fa-plus"></i>
                            Agregar método
                        </button>
                    </div>
                    
                    <!-- Notificaciones -->
                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="fas fa-bell"></i>
                            Notificaciones
                        </h3>
                        
                        <div class="notification-settings">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Emails de confirmación</h4>
                                    <p>Recibe confirmaciones de reservas y partidos</p>
                                </div>
                                <span class="notification-status">Activadas</span>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Recordatorios de partido</h4>
                                    <p>Te avisamos antes de tus partidos</p>
                                </div>
                                <span class="notification-status">24 hs antes</span>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Novedades y promos</h4>
                                    <p>Ofertas especiales y actualizaciones</p>
                                </div>
                                <span class="notification-status">Solo importantes</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seguridad -->
                    <div class="profile-section" id="security">
                        <h3 class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            Seguridad de la Cuenta
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-input" value="********" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Autenticación en 2 pasos</label>
                                <input type="text" class="form-input" value="Desactivada" readonly>
                            </div>
                        </div>
                        
                        <div class="security-actions">
                            <button class="btn-action">
                                <i class="fas fa-cog"></i>
                                Cambiar contraseña
                            </button>
                            <button class="btn-action secondary">
                                <i class="fas fa-key"></i>
                                Configurar 2FA
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted mb-3">Puedes descargar tus datos o eliminar tu cuenta desde aquí.</p>
                            <div class="security-actions">
                                <button class="btn-action secondary">
                                    <i class="fas fa-download"></i>
                                    Exportar datos
                                </button>
                                <button class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Eliminar cuenta
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CTA Section -->
                    <div class="cta-section">
                        <h3 class="cta-title">¿Quieres organizar más partidos?</h3>
                        <p class="cta-text">Crea publicaciones y gestiona reservas. "EL LUGAR DONDE SIEMPRE HAY LUGAR"</p>
                        <button class="btn-cta">
                            <i class="fas fa-plus"></i>
                            Crear partido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
