@extends('layouts.dashboard')

@section('title', 'Moderación de canchas - FaltaUno')
@section('page-title', 'Moderación de canchas')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Publicaciones de canchas</h2>
        <p class="text-muted mb-0">Los dueños publican sus canchas automáticamente. Desde acá podés suspender una publicación si hace falta.</p>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" class="filters-section d-flex flex-wrap gap-2 mb-4">
        <input name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por cancha, ciudad o dueño" aria-label="Buscar cancha o dueño">
        <button class="btn btn-primary">Buscar</button>
        <a href="{{ route('admin.moderacion.canchas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Cancha / complejo</th><th>Dueño</th><th>Ubicación</th><th>Publicación</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach ($canchas as $cancha)
                        <tr>
                            <td>{{ $cancha->nombre }}</td>
                            <td>{{ $cancha->propietario->name }}<br><small class="text-muted">{{ $cancha->propietario->email }}</small></td>
                            <td>{{ $cancha->ciudad }} · {{ $cancha->direccion }}</td>
                            <td>
                                <span class="badge {{ $cancha->activa ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $cancha->activa ? 'Publicada' : 'Suspendida' }}
                                </span>
                                @if ($cancha->verificada)
                                    <span class="badge text-bg-primary">Revisada</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.moderacion.canchas.update', $cancha) }}" class="d-flex flex-wrap gap-2">
                                    @csrf @method('PUT')
                                    <select name="activa" class="form-select form-select-sm" aria-label="Estado de {{ $cancha->nombre }}">
                                        <option value="1" @selected($cancha->activa)>Publicada</option>
                                        <option value="0" @selected(!$cancha->activa)>Suspender</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Guardar</button>
                                </form>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.canchas.edit', $cancha) }}">Editar datos</a>
                                    <form method="POST" action="{{ route('admin.moderacion.canchas.destacada', $cancha) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $cancha->destacada ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $cancha->destacada ? 'Quitar de destacadas' : 'Destacar en home' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $canchas->links() }}</div>
    </div>
</div>
@endsection
