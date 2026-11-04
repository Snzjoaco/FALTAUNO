@extends('layouts.dashboard')

@section('title', 'Registro de actividad - FaltaUno')
@section('page-title', 'Registro de actividad')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Acciones administrativas</h2>
        <p class="text-muted mb-0">Historial de cambios en dueños, canchas, usuarios, partidos y pagos.</p>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Quién</th>
                        <th>Acción</th>
                        <th>Elemento</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td class="text-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $activity->actor?->name ?? 'Cuenta eliminada' }}</td>
                            <td>{{ str($activity->action)->replace('.', ' ')->headline() }}</td>
                            <td>{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id ?? '—' }}</td>
                            <td>
                                @if ($activity->old_values || $activity->new_values)
                                    <details>
                                        <summary>Ver cambios</summary>
                                        <dl class="small mb-0 mt-2">
                                            @foreach (array_unique(array_merge(array_keys($activity->old_values ?? []), array_keys($activity->new_values ?? []))) as $key)
                                                <dt>{{ str($key)->replace('_', ' ')->headline() }}</dt>
                                                <dd>
                                                    {{ json_encode($activity->old_values[$key] ?? null, JSON_UNESCAPED_UNICODE) ?? '—' }}
                                                    →
                                                    {{ json_encode($activity->new_values[$key] ?? null, JSON_UNESCAPED_UNICODE) ?? '—' }}
                                                </dd>
                                            @endforeach
                                        </dl>
                                    </details>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Todavía no hay acciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $activities->links() }}</div>
    </div>
</div>
@endsection
