@extends('layouts.app')

@section('title', 'Reservar canchas - FaltaUno')

@section('content')
<main class="container py-5">
    <div class="mb-4">
        <p class="text-uppercase text-success fw-semibold mb-2">Encontrá tu próximo partido</p>
        <h1 class="fw-bold">Reservar una cancha</h1>
        <p class="text-muted">Elegí una cancha publicada y solicitá un horario disponible.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="GET" action="{{ route('reservar-canchas') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <label for="buscar" class="visually-hidden">Buscar por cancha o ciudad</label>
            <input id="buscar" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar por cancha o ciudad">
        </div>
        <div class="col-md-3">
            <label for="tipo" class="visually-hidden">Tipo de cancha</label>
            <select id="tipo" name="tipo" class="form-select">
                <option value="">Todos los tipos</option>
                @foreach (['f5' => 'Fútbol 5', 'f7' => 'Fútbol 7', 'f11' => 'Fútbol 11', 'mixto' => 'Mixta'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('tipo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-grid"><button class="btn btn-success">Buscar</button></div>
    </form>

    <div class="row g-4">
        @forelse ($canchas as $cancha)
            <div class="col-lg-6">
                <article class="card h-100 border-0 shadow-sm">
                    @if ($cancha->imagen_principal)
                        <img src="{{ asset('storage/'.$cancha->imagen_principal) }}" class="card-img-top" alt="{{ $cancha->nombre }}" style="height:220px;object-fit:cover">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <h2 class="h5 fw-bold mb-1">{{ $cancha->nombre }}</h2>
                                <p class="text-muted mb-2">{{ $cancha->ciudad }} · {{ $cancha->direccion }}</p>
                            </div>
                            <span class="badge text-bg-success align-self-start">{{ strtoupper($cancha->tipo_cancha) }}</span>
                        </div>
                        <p class="mb-3">{{ $cancha->descripcion ?: 'Cancha disponible para reservas.' }}</p>
                        <p class="fw-semibold">Desde ${{ number_format($cancha->precio_base, 0, ',', '.') }} por hora</p>

                        @auth
                            <form method="POST" action="{{ route('reservas.store') }}" class="row g-2">
                                @csrf
                                <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">
                                <div class="col-sm-6">
                                    <label class="form-label" for="fecha-{{ $cancha->id }}">Fecha</label>
                                    <input id="fecha-{{ $cancha->id }}" type="date" name="fecha" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('fecha') }}" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="inicio-{{ $cancha->id }}">Desde</label>
                                    <input id="inicio-{{ $cancha->id }}" type="time" name="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="fin-{{ $cancha->id }}">Hasta</label>
                                    <input id="fin-{{ $cancha->id }}" type="time" name="hora_fin" class="form-control" value="{{ old('hora_fin') }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label" for="personas-{{ $cancha->id }}">Personas</label>
                                    <input id="personas-{{ $cancha->id }}" type="number" name="cantidad_personas" class="form-control" min="1" max="{{ $cancha->capacidad_maxima }}" value="{{ old('cantidad_personas', 1) }}" required>
                                </div>
                                <div class="col-sm-8">
                                    <label class="form-label" for="comentarios-{{ $cancha->id }}">Comentario (opcional)</label>
                                    <input id="comentarios-{{ $cancha->id }}" name="comentarios_cliente" class="form-control" maxlength="1000" value="{{ old('comentarios_cliente') }}">
                                </div>
                                <div class="col-12 d-grid mt-3">
                                    <button class="btn btn-success">Solicitar reserva</button>
                                </div>
                            </form>
                        @else
                            <a class="btn btn-success" href="{{ route('login') }}">Iniciá sesión para reservar</a>
                        @endauth
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">No hay canchas publicadas disponibles con esos filtros.</div>
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $canchas->links() }}</div>
</main>
@endsection
