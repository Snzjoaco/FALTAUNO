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
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Cancha</th><th>Dueño</th><th>Ubicación</th><th>Publicación</th><th>Moderación</th></tr></thead>
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
