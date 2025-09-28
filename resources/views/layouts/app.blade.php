<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FaltaUno - Encuentra jugadores, reserva canchas, juega al fútbol')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logos/logosmall.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-green: #2d5a27;
            --secondary-green: #4a7c59;
            --accent-orange: #ff8c42;
            --light-green: #f0f8f0;
            --light-beige: #fdfbf8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        
        /* Nuevo Header */
        .main-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            overflow: visible !important;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            width: 100%;
        }
        
        .logo a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-green);
        }
        
        .logo-img {
            height: 5rem;
            width: auto;
            object-fit: contain;
            background: transparent;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        .main-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex: 1;
            justify-content: center;
            margin: 0 3rem;
        }
        
        .main-nav a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .main-nav a:hover {
            color: var(--secondary-green);
        }
        
        .user-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: nowrap;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1rem;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .user-info .username {
            font-weight: 500;
            color: var(--primary-green);
            font-size: 0.95rem;
        }
        
        
        /* Botones personalizados */
        .btn-outline-success {
            border-color: var(--primary-green);
            color: var(--primary-green);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            white-space: nowrap;
            border-radius: 6px;
        }
        
        .btn-outline-success:hover {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }
        
        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            white-space: nowrap;
            border-radius: 6px;
        }
        
        .btn-success:hover {
            background-color: var(--secondary-green);
            border-color: var(--secondary-green);
        }
        
        .btn-danger {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            white-space: nowrap;
            border-radius: 6px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .main-nav {
                gap: 1.5rem;
                margin: 0 1rem;
            }
            
            .user-actions {
                gap: 0.5rem;
            }
            
            .user-actions .btn {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }
            
            .logo-img {
                height: 4rem;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .main-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
                margin: 0;
                flex: none;
            }
            
            .user-actions {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .user-info {
                margin-right: 0;
                margin-bottom: 0.5rem;
                width: 100%;
                justify-content: center;
            }
            
            .user-actions .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.7rem;
            }
            
            .logo-img {
                height: 3.5rem;
            }
            
            .user-info {
                background-color: transparent;
                border: none;
                padding: 0;
            }
        }
        
        @media (max-width: 480px) {
            .user-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .user-actions .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
        
        .footer {
            background: var(--primary-green);
            color: white;
            padding: 2rem 0;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
        }
        
        .footer a:hover {
            color: #ccc;
        }
        

    </style>
    
    <!-- Sistema de notificaciones toast -->
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }
        
        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            margin-bottom: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid;
            animation: slideInRight 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }
        
        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-green), var(--secondary-green));
        }
        
        .toast-success {
            border-left-color: #28a745;
        }
        
        .toast-error {
            border-left-color: #dc3545;
        }
        
        .toast-warning {
            border-left-color: #ffc107;
        }
        
        .toast-info {
            border-left-color: #17a2b8;
        }
        
        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .toast-success .toast-icon {
            background: #28a745;
        }
        
        .toast-error .toast-icon {
            background: #dc3545;
        }
        
        .toast-warning .toast-icon {
            background: #ffc107;
        }
        
        .toast-info .toast-icon {
            background: #17a2b8;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            color: #333;
            margin: 0 0 4px 0;
            font-size: 14px;
        }
        
        .toast-message {
            color: #666;
            margin: 0;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .toast-close:hover {
            background: #f5f5f5;
            color: #666;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast.fade-out {
            animation: slideOutRight 0.3s ease-in forwards;
        }
        
        @media (max-width: 768px) {
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }
            
            .toast {
                padding: 14px 16px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container-fluid">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logos/logo1 sin fondo.png') }}" alt="FaltaUno" class="logo-img">
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="main-nav">
                    <a href="{{ route('buscar-partidos') }}">Buscar Partidos</a>
                    <a href="{{ route('reservar-canchas') }}">Reservar Canchas</a>
                    <a href="{{ route('mis-partidos') }}">Mis Partidos</a>
                    @auth
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                    @endauth
                </nav>
                
                <!-- User Actions -->
                <div class="user-actions">
                    @auth
                        <div class="user-info">
                            <i class="fas fa-user"></i>
                            <span class="username">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        </div>
                        <a href="{{ route('perfil') }}" class="btn btn-outline-success">
                            <i class="fas fa-user me-1"></i>Perfil
                        </a>
                        <a href="{{ route('mis-reservas') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-check me-1"></i>Reservas
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt me-1"></i>Salir
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-success">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-success">Crear cuenta</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Contenedor de notificaciones toast -->
    <div id="toast-container" class="toast-container"></div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">© 2023 FaltaUno</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex gap-3 justify-content-md-end">
                        <a href="#terminos">Términos</a>
                        <a href="#privacidad">Privacidad</a>
                        <a href="#soporte">Soporte</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Sistema de notificaciones toast -->
    <script>
        class ToastNotification {
            constructor() {
                this.container = document.getElementById('toast-container');
                if (!this.container) {
                    console.error('Toast container not found');
                    return;
                }
            }
            
            show(message, type = 'info', title = null, duration = 5000) {
                if (!this.container) {
                    console.error('Toast container not available');
                    return;
                }
                
                const toast = this.createToast(message, type, title);
                this.container.appendChild(toast);
                
                // Auto remove after duration
                setTimeout(() => {
                    this.remove(toast);
                }, duration);
                
                return toast;
            }
            
            createToast(message, type, title) {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                const icons = {
                    success: 'fas fa-check',
                    error: 'fas fa-times',
                    warning: 'fas fa-exclamation-triangle',
                    info: 'fas fa-info-circle'
                };
                
                const titles = {
                    success: title || 'Éxito',
                    error: title || 'Error',
                    warning: title || 'Advertencia',
                    info: title || 'Información'
                };
                
                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="${icons[type]}"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${titles[type]}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close" onclick="toastNotification.remove(this.parentElement)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                return toast;
            }
            
            remove(toast) {
                toast.classList.add('fade-out');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.parentElement.removeChild(toast);
                    }
                }, 300);
            }
            
            success(message, title = null, duration = 5000) {
                return this.show(message, 'success', title, duration);
            }
            
            error(message, title = null, duration = 7000) {
                return this.show(message, 'error', title, duration);
            }
            
            warning(message, title = null, duration = 6000) {
                return this.show(message, 'warning', title, duration);
            }
            
            info(message, title = null, duration = 5000) {
                return this.show(message, 'info', title, duration);
            }
        }
        
        // Inicializar sistema de notificaciones cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            const toastNotification = new ToastNotification();
            window.showToast = toastNotification;
            
            // Test function para verificar que funciona
            window.testToast = function() {
                showToast.success('¡Notificación de prueba funcionando!');
            };
        });
    </script>
    
    @stack('scripts')
    
    <!-- Popper.js y Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
</body>
</html>
