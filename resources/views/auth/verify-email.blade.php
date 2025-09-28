@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="auth-card">
                    <div class="auth-header">
                        <h2 class="auth-title">
                            <i class="fas fa-envelope"></i>
                            Verifica tu email
                        </h2>
                        <p class="auth-subtitle">
                            Hemos enviado un enlace de verificación a tu dirección de correo electrónico.
                        </p>
                    </div>

                    <div class="auth-body">
                        <div class="verification-info">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="info-content">
                                <h4>¿No recibiste el email?</h4>
                                <p>Revisa tu carpeta de spam o correo no deseado. Si no lo encuentras, puedes solicitar un nuevo enlace.</p>
                            </div>
                        </div>

                        <div class="verification-actions">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-paper-plane"></i>
                                    Reenviar email de verificación
                                </button>
                            </form>

                            <div class="auth-links">
                                <a href="{{ route('login') }}" class="auth-link">
                                    <i class="fas fa-arrow-left"></i>
                                    Volver al login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verification-info {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    padding: 2rem;
    margin: 2rem 0;
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    border: 1px solid #dee2e6;
}

.info-icon {
    background: var(--primary-green);
    color: white;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.info-content h4 {
    color: var(--primary-green);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.info-content p {
    color: #6c757d;
    margin: 0;
    line-height: 1.5;
}

.verification-actions {
    text-align: center;
    margin-top: 2rem;
}

.verification-actions .btn {
    margin-bottom: 1.5rem;
}

.auth-links {
    margin-top: 1rem;
}

.auth-link {
    color: var(--primary-green);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: var(--secondary-green);
    transform: translateX(-5px);
}
</style>
@endsection
