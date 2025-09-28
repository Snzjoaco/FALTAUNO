@extends('layouts.app')

@section('title', 'Crear Cuenta - FaltaUno')

@section('content')
<style>
    .auth-page {
        background: linear-gradient(135deg, var(--light-green), #e8f5e8);
        /* Para usar imagen de fondo, descomenta las siguientes líneas y agrega tu imagen en public/images/backgrounds/ */
        background-image: url('/images/backgrounds/register-bg.jpg'); 
        background-size: cover; 
        background-position: center; 
        background-attachment: fixed; 
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 120px 0 80px 0;
        position: relative;
    }
    
    /* Overlay para mejorar legibilidad si usas imagen de fondo */
    .auth-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(45, 90, 39, 0.1);
        z-index: 0;
    }
    
    .auth-container {
        position: relative;
        z-index: 1;
    }
    
    .auth-container {
        max-width: 450px;
        width: 100%;
        margin: 0 auto;
    }
    
    .auth-card {
        background: white;
        border-radius: 20px;
        padding: 3rem 2.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(45, 90, 39, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .auth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-green), var(--secondary-green), var(--accent-orange));
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(45, 90, 39, 0.3);
    }
    
    .auth-logo i {
        font-size: 2rem;
        color: white;
    }
    
    .auth-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }
    
    .auth-subtitle {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-green);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: block;
    }
    
    .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
    }
    
    .form-control:focus {
        border-color: var(--primary-green);
        background: white;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.15);
        outline: none;
        transform: translateY(-2px);
    }
    
    .form-control:hover {
        border-color: var(--secondary-green);
        background: white;
    }
    
    .form-control::placeholder {
        color: #adb5bd;
        font-weight: 400;
    }
    
    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.1rem;
        pointer-events: none;
    }
    
    .form-control:focus + .input-icon {
        color: var(--primary-green);
    }
    
    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #666;
    }
    
    .strength-bar {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 0.25rem;
    }
    
    .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-weak .strength-fill {
        width: 33%;
        background: #dc3545;
    }
    
    .strength-medium .strength-fill {
        width: 66%;
        background: #ffc107;
    }
    
    .strength-strong .strength-fill {
        width: 100%;
        background: var(--primary-green);
    }
    
    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        transition: all 0.3s ease;
        margin-top: 0.1rem;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .form-check-label {
        font-size: 0.9rem;
        color: #666;
        cursor: pointer;
        line-height: 1.4;
    }
    
    .form-check-label a {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 600;
    }
    
    .form-check-label a:hover {
        text-decoration: underline;
    }
    
    .btn-register {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.3);
    }
    
    .btn-register:active {
        transform: translateY(0);
    }
    
    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-register:hover::before {
        left: 100%;
    }
    
    .divider {
        text-align: center;
        margin: 2rem 0;
        position: relative;
        color: #666;
        font-size: 0.9rem;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e9ecef;
    }
    
    .divider span {
        background: white;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
    }
    
    .social-login {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .btn-social {
        flex: 1;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        background: white;
        color: #666;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-social:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(45, 90, 39, 0.1);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 2rem;
    }
    
    .auth-footer p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .auth-footer a {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .auth-footer a:hover {
        color: var(--secondary-green);
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .auth-page {
            padding: 100px 0 60px 0;
        }
        
        .auth-card {
            padding: 2rem 1.5rem;
            margin: 0 1rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .social-login {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .auth-page {
            padding: 80px 0 40px 0;
        }
        
        .auth-card {
            padding: 1.5rem 1rem;
        }
    }
</style>

<div class="auth-page">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h1 class="auth-title">¡Únete a FaltaUno!</h1>
                    <p class="auth-subtitle">Crea tu cuenta y comienza a jugar</p>
                </div>
                
                <!-- Formulario -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="Tu nombre" value="{{ old('first_name') }}" required>
                            <i class="fas fa-user input-icon"></i>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Tu apellido" value="{{ old('last_name') }}" required>
                            <i class="fas fa-user input-icon"></i>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="tu_usuario" value="{{ old('name') }}" required>
                        <i class="fas fa-at input-icon"></i>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="tu@correo.com" value="{{ old('email') }}" required>
                        <i class="fas fa-envelope input-icon"></i>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+54 11 1234-5678" value="{{ old('phone') }}">
                        <i class="fas fa-phone input-icon"></i>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder="Buenos Aires" value="{{ old('city') }}">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <i class="fas fa-lock input-icon"></i>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill"></div>
                            </div>
                            <small>Usa al menos 8 caracteres con letras y números</small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Acepto los <a href="#">términos y condiciones</a> y la <a href="#">política de privacidad</a>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newsletter">
                        <label class="form-check-label" for="newsletter">
                            Quiero recibir notificaciones sobre partidos y ofertas
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus me-2"></i>
                        Crear Cuenta
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>o regístrate con</span>
                </div>
                
                <!-- Social Login -->
                <div class="social-login">
                    <a href="#" class="btn-social">
                        <i class="fab fa-google"></i>
                        Google
                    </a>
                    <a href="#" class="btn-social">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                </div>
                
                <!-- Footer -->
                <div class="auth-footer">
                    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength indicator
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.querySelector('input[type="password"]');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthFill = document.querySelector('.strength-fill');
    const strengthText = document.querySelector('.password-strength small');
    
    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = getPasswordStrength(password);
            
            // Remove existing strength classes
            strengthBar.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
            
            // Add appropriate strength class
            if (password.length > 0) {
                strengthBar.classList.add(strength.class);
                strengthText.textContent = strength.text;
            } else {
                strengthText.textContent = 'Usa al menos 8 caracteres con letras y números';
            }
        });
    }
    
    function getPasswordStrength(password) {
        if (password.length < 6) {
            return { class: 'strength-weak', text: 'Muy débil' };
        } else if (password.length < 8 || !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
            return { class: 'strength-medium', text: 'Media' };
        } else {
            return { class: 'strength-strong', text: 'Fuerte' };
        }
    }
});
</script>
@endsection
