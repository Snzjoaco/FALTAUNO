@extends('layouts.dashboard')

@section('title', 'Moderación de partidos - FaltaUno')
@section('page-title', 'Moderación de partidos')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Moderar publicaciones</h2>
        <p class="text-muted mb-0">Los partidos cancelados o suspendidos dejan de estar disponibles para los jugadores.</p>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Partido</th><th>Organizador</th><th>Cancha</th><th>Fecha</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @foreach ($partidos as $partido)
                        <tr>
                            <td>{{ $partido->titulo }}</td>
                            <td>{{ $partido->organizador->name }}</td>
                            <td>{{ $partido->cancha->nombre }}</td>
                            <td>{{ $partido->fecha->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.moderacion.partidos.update', $partido) }}" class="d-flex gap-2">
                                    @csrf @method('PUT')
                                    <select name="estado" class="form-select form-select-sm" aria-label="Estado de {{ $partido->titulo }}">
                                        @foreach (['borrador', 'publicado', 'completo', 'en_progreso', 'finalizado', 'cancelado', 'suspendido'] as $estado)
                                            <option value="{{ $estado }}" @selected($partido->estado === $estado)>{{ ucfirst(str_replace('_', ' ', $estado)) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Guardar</button>
                                </form>
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $partidos->links() }}</div>
    </div>
</div>
@endsection
