@extends('layouts.dashboard')

@section('title', 'Usuarios - FaltaUno')
@section('page-title', 'Usuarios y roles')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Gestionar usuarios</h2>
        <p class="text-muted mb-0">Suspendé cuentas problemáticas. Las altas de dueños se gestionan con invitación desde su sección.</p>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Usuario</th><th>Email</th><th>Rol</th><th>Cuenta</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->isPlatformAdmin())
                                    <span class="badge text-bg-dark">Admin de plataforma</span>
                                @elseif ($user->isCourtOwner())
                                    <span class="badge text-bg-primary">Dueño de cancha</span>
                                    <a class="d-block small mt-1" href="{{ route('admin.duenos.edit', $user) }}">Gestionar dueño</a>
                                @else
                                    <form method="POST" action="{{ route('admin.moderacion.usuarios.role', $user) }}" class="d-flex gap-2">
                                        @csrf @method('PUT')
                                        <select name="role" class="form-select form-select-sm" aria-label="Rol de {{ $user->name }}">
                                            <option value="jugador" @selected($user->role === 'jugador')>Jugador</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary">Guardar</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->is_suspended ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $user->is_suspended ? 'Suspendida' : 'Activa' }}
                                </span>
                            </td>
                            <td>
                                @if (!$user->isPlatformAdmin() && !$user->isCourtOwner())
                                    <form method="POST" action="{{ route('admin.moderacion.usuarios.suspension', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $user->is_suspended ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                            {{ $user->is_suspended ? 'Reactivar' : 'Suspender' }}
                                        </button>
                                    </form>
                                @elseif ($user->isPlatformAdmin())
                                    <span class="text-muted">Protegida</span>
                                @else
                                    <span class="text-muted">Ver Gestión de dueños</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $users->links() }}</div>
    </div>
</div>
@endsection
