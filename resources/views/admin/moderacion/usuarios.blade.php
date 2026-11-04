@extends('layouts.dashboard')

@section('title', 'Usuarios - FaltaUno')
@section('page-title', 'Usuarios y roles')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Gestionar usuarios</h2>
        <p class="text-muted mb-0">Asigná el rol de dueño y suspendé cuentas cuando sea necesario.</p>
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
                                @else
                                    <form method="POST" action="{{ route('admin.moderacion.usuarios.role', $user) }}" class="d-flex gap-2">
                                        @csrf @method('PUT')
                                        <select name="role" class="form-select form-select-sm" aria-label="Rol de {{ $user->name }}">
                                            <option value="jugador" @selected($user->role === 'jugador')>Jugador</option>
                                            <option value="dueno_cancha" @selected($user->role === 'dueno_cancha')>Dueño de cancha</option>
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
                                @unless ($user->isPlatformAdmin())
                                    <form method="POST" action="{{ route('admin.moderacion.usuarios.suspension', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $user->is_suspended ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                            {{ $user->is_suspended ? 'Reactivar' : 'Suspender' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Protegida</span>
                                @endunless
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
