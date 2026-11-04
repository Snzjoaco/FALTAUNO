@extends('layouts.app')

@section('title', 'Mis reservas - FaltaUno')

@section('content')
<main class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Mis reservas</h1>
            <p class="text-muted mb-0">Consultá el estado y los datos de tus solicitudes.</p>
        </div>
        <a href="{{ route('reservar-canchas') }}" class="btn btn-success">Buscar canchas</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table align-middle mb-0">
            <thead><tr><th>Código</th><th>Cancha</th><th>Fecha</th><th>Horario</th><th>Monto</th><th>Estado</th></tr></thead>
            <tbody>
                @forelse ($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->codigo_reserva }}</td>
                        <td>{{ $reserva->cancha->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                        <td>{{ substr($reserva->hora_inicio, 0, 5) }}–{{ substr($reserva->hora_fin, 0, 5) }}</td>
                        <td>${{ number_format($reserva->monto_final, 0, ',', '.') }}</td>
                        <td><span class="badge text-bg-secondary">{{ ucfirst(str_replace('_', ' ', $reserva->estado)) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Todavía no hiciste reservas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $reservas->links() }}</div>
</main>
@endsection
