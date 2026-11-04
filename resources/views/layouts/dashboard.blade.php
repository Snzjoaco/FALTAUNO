<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - FaltaUno')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS como respaldo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-green: #2d5a27;
            --secondary-green: #4a7c59;
            --accent-orange: #ff8c42;
            --light-green: #f0f8f0;
            --light-beige: #fdfbf8;
            --sidebar-width: 264px;
            --header-height: 76px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f5f8f5;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(160deg, #244c2a 0%, #2d5a27 55%, #3b6b43 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 8px 0 28px rgba(26, 54, 31, 0.12);
        }
        
        .sidebar-header {
            padding: 1.35rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: left;
        }
        
        .sidebar-header h3 {
            display: flex;
            align-items: center;
            margin: 0;
            gap: 0.75rem;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.24);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.16);
        }
        
        .sidebar-header p {
            margin: 0.65rem 0 0 3.2rem;
            font-size: 0.76rem;
            opacity: 0.8;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        
        .sidebar-nav {
            padding: 1.15rem 0.85rem;
            display: flex;
            min-height: calc(100vh - 102px);
            flex-direction: column;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            min-height: 48px;
            padding: 0.75rem 0.9rem;
            color: rgba(255,255,255,0.86);
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
            border: 1px solid transparent;
            border-radius: 12px;
            font-weight: 550;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(2px);
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.12);
            color: white;
            box-shadow: inset 3px 0 0 var(--accent-orange);
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            width: 22px;
            text-align: center;
            opacity: 0.9;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
        }
        
        .header {
            background: rgba(255,255,255,0.96);
            height: var(--header-height);
            border-bottom: 1px solid #e8eee8;
            box-shadow: 0 3px 16px rgba(31, 54, 35, 0.045);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 900;
            backdrop-filter: blur(12px);
        }
        
        .header-left h1 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--primary-green);
            font-weight: 700;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.65rem 0.4rem 0.45rem;
            background: #f1f6f0;
            border: 1px solid #e4ede3;
            border-radius: 999px;
            transition: background-color 0.2s ease;
        }
        
        .user-menu:hover {
            background: #e8f1e7;
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .content {
            padding: clamp(1.15rem, 2.4vw, 2rem);
        }
        
        /* Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-green);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card .icon.primary {
            background: var(--light-green);
            color: var(--primary-green);
        }
        
        .stats-card .icon.success {
            background: #e8f5e8;
            color: #28a745;
        }
        
        .stats-card .icon.warning {
            background: #fff3cd;
            color: #ffc107;
        }
        
        .stats-card .icon.info {
            background: #d1ecf1;
            color: #17a2b8;
        }
        
        .stats-card h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .stats-card p {
            margin: 0.5rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: min(290px, calc(100vw - 48px));
                z-index: 1100;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content {
                padding: 1rem;
            }

            .header {
                height: 66px;
                padding: 0 1rem;
            }

            .header-left h1 {
                font-size: 1.05rem;
            }

            .user-menu > span,
            .user-menu > .fa-chevron-down {
                display: none;
            }
        }
        
        /* Toggle button for mobile */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 12px;
            font-size: 1.15rem;
            color: var(--primary-green);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
    @stack('styles')
    <style>
        :root {
            color-scheme: light;
        }

        .main-content {
            min-width: 0;
        }

        .content > .container-fluid {
            width: 100%;
            max-width: 1560px;
            padding: 0;
            margin: 0 auto;
        }

        .content h1,
        .content h2,
        .content h3,
        .content h4,
        .content h5,
        .content h6 {
            color: #26352a;
        }

        .content .text-gray-800 {
            color: #26352a !important;
        }

        .content .card,
        .content .form-section,
        .content .filters-section,
        .content .reserva-card,
        .content .turno-card {
            border: 1px solid #e3ebe2;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(38, 53, 42, 0.045);
        }

        .content .card {
            overflow: hidden;
        }

        .content .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e8eee7;
            background: #fff;
            color: #2d5a27;
        }

        .content .card-body {
            padding: 1.25rem;
        }

        .content .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #e8eee7;
            background: #fbfdfb !important;
        }

        .content .btn {
            min-height: 40px;
            border-radius: 9px;
            font-weight: 600;
            transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
        }

        .content .btn:hover {
            transform: translateY(-1px);
        }

        .content .btn-primary,
        .content .btn-filter {
            border-color: #2d5a27;
            background: #2d5a27;
            color: #fff;
            box-shadow: 0 4px 10px rgba(45, 90, 39, 0.12);
        }

        .content .btn-primary:hover,
        .content .btn-filter:hover {
            border-color: #4a7c59;
            background: #4a7c59;
            color: #fff;
        }

        .content .btn-outline-primary,
        .content .btn-outline-filter {
            border-color: #b8cdb5;
            color: #2d5a27;
        }

        .content .btn-outline-primary:hover,
        .content .btn-outline-filter:hover {
            border-color: #2d5a27;
            background: #2d5a27;
            color: #fff;
        }

        .content .btn-outline-secondary,
        .content .btn-secondary {
            border-color: #d9e1d8;
            background: #fff;
            color: #48564a;
        }

        .content .btn-outline-secondary:hover,
        .content .btn-secondary:hover {
            border-color: #b9c9b7;
            background: #f3f7f2;
            color: #2d5a27;
        }

        .content .form-label {
            margin-bottom: 0.45rem;
            color: #36483a;
            font-size: 0.9rem;
            font-weight: 650;
        }

        .content .form-control,
        .content .form-select {
            min-height: 44px;
            border: 1px solid #dce5db;
            border-radius: 9px;
            background-color: #fff;
            color: #26352a;
        }

        .content textarea.form-control {
            min-height: 110px;
        }

        .content .form-control:focus,
        .content .form-select:focus {
            border-color: #6d946c;
            box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.12);
        }

        .content .form-check-input:checked {
            border-color: #2d5a27;
            background-color: #2d5a27;
        }

        .content .table-responsive {
            border-radius: 12px;
        }

        .content .table {
            margin-bottom: 0;
            color: #36443a;
            vertical-align: middle;
        }

        .content .table thead th {
            padding: 0.9rem 0.8rem;
            border-bottom: 1px solid #e2eae1;
            background: #f3f7f2;
            color: #435846;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.035em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .content .table tbody td {
            padding: 0.85rem 0.8rem;
            border-color: #edf1ec;
        }

        .content .table tbody tr:hover > * {
            background-color: #f8fbf7;
        }

        .content .filters-section {
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            background: #fff;
        }

        .content .form-section {
            padding: clamp(1.1rem, 2.2vw, 1.75rem);
            margin-bottom: 1.25rem;
        }

        .content .form-section h4 {
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #e4ebe3;
            color: #2d5a27;
            font-size: 1.05rem;
        }

        .content .precio-section,
        .content .restricciones-section {
            border: 1px solid #e3ebe2;
            border-radius: 12px;
            background: #f7faf6;
        }

        .content #map {
            min-height: 340px;
            overflow: hidden;
            border: 1px solid #dfe8de;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(38, 53, 42, 0.06);
        }

        .content .help-text,
        .content .form-text {
            color: #68746b;
        }

        .content .form-section .form-check {
            min-height: 44px;
            padding: 0.7rem 0.8rem 0.7rem 2.2rem;
            border: 1px solid #e5ece4;
            border-radius: 10px;
            background: #fbfdfb;
        }

        .content .form-section .form-check-input {
            margin-top: 0.15rem;
        }

        .content .cancha-card {
            height: auto;
            min-height: 0;
            border: 1px solid #e3ebe2;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(38, 53, 42, 0.05);
        }

        .content .cancha-card::before {
            display: none;
        }

        .content .cancha-card .card-body {
            height: auto;
            min-height: 170px;
            padding: 1rem !important;
            background: #fff;
        }

        .content .cancha-card .card-title {
            font-size: 1rem;
            line-height: 1.35;
        }

        .content .cancha-card .text-muted {
            font-size: 0.84rem;
        }

        .content .cancha-card .btn-sm {
            min-height: 38px;
            border: 1px solid #b8cdb5;
            background: #fff;
            color: #2d5a27;
            font-size: 0.85rem;
        }

        .content .cancha-card .btn-sm:hover {
            border-color: #2d5a27;
            background: #2d5a27;
            color: #fff;
        }

        .content .cancha-card .card-img-top {
            height: 170px;
        }

        .content .cancha-card img.card-img-top {
            height: 175px !important;
        }

        .content .stats-card {
            min-height: 120px;
            padding: 1.2rem;
            border: 1px solid #e3ebe2;
            border-radius: 16px;
            background: #fff;
            color: #26352a;
            box-shadow: 0 5px 20px rgba(38, 53, 42, 0.045);
        }

        .content .stats-card:hover {
            box-shadow: 0 9px 24px rgba(38, 53, 42, 0.08);
            transform: translateY(-2px);
        }

        .content .stats-card .stats-number {
            color: #2d5a27;
            font-size: 1.75rem;
        }

        .content .stats-card .stats-label {
            color: #68746b;
        }

        .content .badge-estado,
        .content .badge-pago-pendiente {
            border: 1px solid #f4d8b8;
            background: #fff3e7;
            color: #925018;
        }

        .content .badge-confirmada,
        .content .badge-activo,
        .content .badge-pago-pagado {
            border: 1px solid #cce3cc;
            background: #edf6ed;
            color: #2d6535;
        }

        .content .badge-en-curso,
        .content .badge-completada,
        .content .badge-destacado,
        .content .badge-pago-reembolsado {
            border: 1px solid #d5e5d2;
            background: #f1f7ef;
            color: #3e6941;
        }

        .content .badge-cancelada,
        .content .badge-no-show,
        .content .badge-reembolsada,
        .content .badge-pago-disputado,
        .content .badge-inactivo {
            border: 1px solid #e4e6e3;
            background: #f2f3f1;
            color: #5c665d;
        }

        .content .badge-estado {
            display: inline-flex;
            align-items: center;
            padding: 0.32rem 0.65rem;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 650;
        }

        .content .dias-semana {
            gap: 0.35rem;
        }

        .content .dia-badge {
            border: 1px solid #d5e5d2;
            border-radius: 999px;
            background: #f1f7ef;
            color: #3e6941;
            font-size: 0.74rem;
        }

        .content .horario-info {
            border-left-color: #4a7c59;
            border-radius: 10px;
            background: #f7faf6;
        }

        .content .precio-base {
            color: #2d5a27;
        }

        .content .action-buttons {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .content .action-buttons .btn {
            margin: 0;
        }

        .content .pagination {
            gap: 0.25rem;
            margin-bottom: 0;
        }

        .content .pagination .page-link {
            border-color: #e1e9e0;
            border-radius: 8px;
            color: #2d5a27;
        }

        .content .pagination .page-item.active .page-link {
            border-color: #2d5a27;
            background: #2d5a27;
            color: #fff;
        }

        .content .alert {
            border: 1px solid transparent;
            border-radius: 12px;
        }

        .sidebar-backdrop {
            display: none;
        }

        @media (min-width: 769px) {
            .sidebar-nav > .nav-item:nth-last-child(2) {
                margin-top: auto !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar-backdrop.open {
                position: fixed;
                z-index: 1050;
                inset: 0;
                display: block;
                background: rgba(19, 36, 22, 0.48);
                backdrop-filter: blur(2px);
            }

            .content .card-body {
                padding: 1rem;
            }

            .content .filters-section {
                padding: 1rem;
            }

            .content .card-footer > .d-flex {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 0.8rem;
            }

            .content .row > .col-12 > .d-flex.justify-content-between {
                align-items: flex-start !important;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .content .row > .col-12 > .d-flex.justify-content-between > div:last-child {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>
                    <img class="sidebar-logo" src="{{ asset('images/logos/logosmall.png') }}" alt="">
                    <span>FaltaUno</span>
                </h3>
                <p>Panel de Administración</p>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.canchas.index') }}" class="nav-link {{ request()->routeIs('admin.canchas.index', 'admin.canchas.show', 'admin.canchas.edit') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i>
                        Mis Canchas
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.canchas.create') }}" class="nav-link {{ request()->routeIs('admin.canchas.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        Agregar Cancha
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.reservas.index') }}" class="nav-link {{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        Reservas
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.turnos.index') }}" class="nav-link {{ request()->routeIs('admin.turnos.*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        Turnos
                    </a>
                </div>

                @if (auth()->user()->isPlatformAdmin())
                    <div class="nav-item mt-3 pt-3 border-top border-light border-opacity-10">
                        <span class="px-3 small text-uppercase text-white-50">Plataforma</span>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.duenos.index') }}" class="nav-link {{ request()->routeIs('admin.duenos.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            Gestión de dueños
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.moderacion.usuarios.index') }}" class="nav-link {{ request()->routeIs('admin.moderacion.usuarios.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            Usuarios y roles
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.moderacion.canchas.index') }}" class="nav-link {{ request()->routeIs('admin.moderacion.canchas.*') ? 'active' : '' }}">
                            <i class="fas fa-shield-alt"></i>
                            Moderar canchas
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.moderacion.partidos.index') }}" class="nav-link {{ request()->routeIs('admin.moderacion.partidos.*') ? 'active' : '' }}">
                            <i class="fas fa-flag"></i>
                            Moderar partidos
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.moderacion.actividad.index') }}" class="nav-link {{ request()->routeIs('admin.moderacion.actividad.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            Registro de actividad
                        </a>
                    </div>
                @endif
                
                <div class="nav-item mt-4 pt-3 border-top border-light border-opacity-10">
                    <a href="{{ route('welcome') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        Volver al Sitio
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Abrir navegación" aria-controls="sidebar" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function setSidebarOpen(isOpen) {
            sidebar.classList.toggle('open', isOpen);
            sidebarBackdrop.classList.toggle('open', isOpen);
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            sidebarToggle.setAttribute('aria-label', isOpen ? 'Cerrar navegación' : 'Abrir navegación');
        }

        sidebarToggle.addEventListener('click', function() {
            setSidebarOpen(!sidebar.classList.contains('open'));
        });

        sidebarBackdrop.addEventListener('click', function() {
            setSidebarOpen(false);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                setSidebarOpen(false);
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                setSidebarOpen(false);
            }
        });
    </script>
    
    @stack('scripts')
    
    <!-- Bootstrap JS como respaldo -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
