@extends('layouts.dashboard')

@section('title', 'Gestión de dueños - FaltaUno')
@section('page-title', 'Gestión de dueños')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">Dueños de cancha</h2>
            <p class="text-muted mb-0">Invitaciones, cuentas, complejos y facturación manual.</p>
        </div>
        <a href="{{ route('admin.duenos.create') }}" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Crear dueño</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <form method="GET" action="{{ route('admin.duenos.index') }}" class="filters-section row g-3 align-items-end">
        <div class="col-md-6">
            <label for="buscar" class="form-label">Buscar por nombre, email o teléfono</label>
            <input id="buscar" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Ej. Ana Pérez">
        </div>
        <div class="col-md-3">
            <label for="estado" class="form-label">Estado de cuenta</label>
            <select id="estado" name="estado" class="form-select">
                <option value="">Todos</option>
                @foreach (['invitado' => 'Invitado', 'activo' => 'Activo', 'suspendido' => 'Suspendido'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('estado') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-grow-1">Filtrar</button>
            <a href="{{ route('admin.duenos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Dueño</th><th>Estado</th><th>Complejos/canchas</th><th>Reservas</th><th>Plan y vencimiento</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse ($owners as $owner)
                        <tr>
                            <td>
                                <strong>{{ $owner->name }}</strong><br>
                                <a href="mailto:{{ $owner->email }}">{{ $owner->email }}</a><br>
                                <small class="text-muted">{{ $owner->phone }}</small>
                            </td>
                            <td><span class="badge {{ $owner->owner_status === 'activo' ? 'text-bg-success' : ($owner->owner_status === 'suspendido' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ ucfirst($owner->owner_status ?? 'invitado') }}</span></td>
                            <td>{{ $owner->canchas_count }}</td>
                            <td>{{ $owner->reservas_de_canchas_count }}</td>
                            <td>
                                {{ $owner->billing_plan ?: 'Sin plan' }}
                                @if ($owner->billing_amount !== null)<br><small>${{ number_format($owner->billing_amount, 2, ',', '.') }}</small>@endif
                                @if ($owner->owner_registered_at)<br><small>Alta: {{ $owner->owner_registered_at->format('d/m/Y') }}</small>@endif
                                <br><span class="badge {{ $owner->effective_billing_status === 'al_dia' ? 'text-bg-success' : 'text-bg-danger' }}">{{ $owner->effective_billing_status === 'al_dia' ? 'Al día' : 'Vencido' }}</span>
                                @if ($owner->next_charge_at)<br><small>Próximo cobro: {{ $owner->next_charge_at->format('d/m/Y') }}</small>@endif
                                @if ($owner->trial_ends_at)<br><small>Prueba hasta: {{ $owner->trial_ends_at->format('d/m/Y') }}</small>@endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.duenos.edit', $owner) }}">Editar</a>
                                    @if ($owner->owner_status === 'invitado')
                                        <form method="POST" action="{{ route('admin.duenos.invitation', $owner) }}">@csrf<button class="btn btn-sm btn-outline-secondary">Reenviar invitación</button></form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.duenos.status', $owner) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="owner_status" value="{{ $owner->owner_status === 'suspendido' ? 'activo' : 'suspendido' }}">
                                        <button class="btn btn-sm {{ $owner->owner_status === 'suspendido' ? 'btn-outline-success' : 'btn-outline-danger' }}">{{ $owner->owner_status === 'suspendido' ? 'Reactivar' : 'Suspender' }}</button>
                                    </form>
                                    @if ($owner->owner_status !== 'invitado')
                                        <form method="POST" action="{{ route('admin.duenos.payment', $owner) }}">@csrf<button class="btn btn-sm btn-outline-success">Marcar pagó</button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No hay dueños para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $owners->links() }}</div>
    </div>
</div>
@endsection
