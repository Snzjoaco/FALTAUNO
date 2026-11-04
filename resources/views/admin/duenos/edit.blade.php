@extends('layouts.dashboard')

@section('title', 'Editar dueño - FaltaUno')
@section('page-title', 'Editar dueño y complejo')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">{{ $owner->name }}</h2>
        <p class="text-muted mb-0">Estado: {{ ucfirst($owner->owner_status ?? 'invitado') }} · {{ $owner->canchas->count() }} canchas asociadas.</p>
    </div>
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('admin.duenos.update', $owner) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.duenos._form', ['court' => $owner->canchas->first()])
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-primary">Guardar cambios</button>
            @if ($owner->owner_status === 'invitado')
                <button form="resend-invitation" class="btn btn-outline-secondary">Reenviar invitación</button>
            @endif
            <a href="{{ route('admin.duenos.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </form>
    @if ($owner->owner_status === 'invitado')
        <form id="resend-invitation" method="POST" action="{{ route('admin.duenos.invitation', $owner) }}">@csrf</form>
    @endif
</div>
@endsection
