@extends('layouts.dashboard')

@section('title', 'Invitar dueño - FaltaUno')
@section('page-title', 'Crear e invitar dueño')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="h4 mb-1">Alta de dueño y complejo</h2>
        <p class="text-muted mb-0">Se enviará un enlace seguro para que el dueño elija su contraseña. No se crea ni se comparte una contraseña manual.</p>
    </div>
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('admin.duenos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.duenos._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Crear y enviar invitación</button>
            <a href="{{ route('admin.duenos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
