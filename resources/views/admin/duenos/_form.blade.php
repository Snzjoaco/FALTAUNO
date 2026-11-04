@php
    $owner = $owner ?? null;
    $court = $court ?? null;
@endphp
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>#owner-location-map{height:320px;border:1px solid #dce5db;border-radius:12px;z-index:1}</style>
@endpush
<div class="form-section">
    <h4>Cuenta y contacto del dueño</h4>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="first_name">Nombre</label><input id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $owner->first_name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="last_name">Apellido</label><input id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $owner->last_name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="email">Email de acceso</label><input id="email" name="email" type="email" class="form-control" value="{{ old('email', $owner->email ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="phone">Teléfono o WhatsApp</label><input id="phone" name="phone" type="tel" class="form-control" value="{{ old('phone', $owner->phone ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="city">Localidad del dueño</label><input id="city" name="city" class="form-control" value="{{ old('city', $owner->city ?? '') }}" required></div>
    </div>
</div>

<div class="form-section">
    <h4>Complejo y publicación</h4>
    @if ($court)<input type="hidden" name="cancha_id" value="{{ $court->id }}">@endif
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label" for="nombre">Nombre del complejo / cancha principal</label><input id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $court->nombre ?? '') }}" required></div>
        <div class="col-md-4"><label class="form-label" for="tipo_cancha">Tipo de cancha</label><select id="tipo_cancha" name="tipo_cancha" class="form-select" required>@foreach (['f5'=>'Fútbol 5','f7'=>'Fútbol 7','f11'=>'Fútbol 11','mixto'=>'Mixto'] as $key=>$label)<option value="{{ $key }}" @selected(old('tipo_cancha', $court->tipo_cancha ?? 'f5') === $key)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label" for="direccion">Dirección</label><input id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $court->direccion ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="ciudad_complejo">Localidad</label><input id="ciudad_complejo" name="ciudad_complejo" class="form-control" value="{{ old('ciudad_complejo', $court->ciudad ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="telefono">Teléfono del complejo</label><input id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $court->telefono ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="email_complejo">Email del complejo (opcional)</label><input id="email_complejo" name="email_complejo" type="email" class="form-control" value="{{ old('email_complejo', $court->email ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label" for="horario_atencion">Horario de atención</label><input id="horario_atencion" name="horario_atencion" class="form-control" placeholder="Lun a dom, 9:00 a 23:00" value="{{ old('horario_atencion', $court->horario_atencion ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="instagram_url">Instagram o redes (URL opcional)</label><input id="instagram_url" name="instagram_url" type="url" class="form-control" value="{{ old('instagram_url', $court->instagram_url ?? '') }}" placeholder="https://instagram.com/"></div>
        <div class="col-md-4"><label class="form-label" for="tipo_superficie">Superficie</label><select id="tipo_superficie" name="tipo_superficie" class="form-select" required>@foreach (['cesped_sintetico'=>'Césped sintético','cesped_natural'=>'Césped natural','cemento'=>'Cemento','parquet'=>'Parquet'] as $key=>$label)<option value="{{ $key }}" @selected(old('tipo_superficie', $court->tipo_superficie ?? 'cesped_sintetico') === $key)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label" for="capacidad_maxima">Capacidad máxima</label><input id="capacidad_maxima" name="capacidad_maxima" type="number" min="1" max="50" class="form-control" value="{{ old('capacidad_maxima', $court->capacidad_maxima ?? 10) }}" required></div>
        <div class="col-md-4"><label class="form-label" for="precio_base">Precio por hora</label><input id="precio_base" name="precio_base" type="number" min="0" step="0.01" class="form-control" value="{{ old('precio_base', $court->precio_base ?? '') }}" required></div>
        <div class="col-12"><label class="form-label" for="descripcion">Descripción (opcional)</label><textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ old('descripcion', $court->descripcion ?? '') }}</textarea></div>
        <div class="col-md-6"><label class="form-label" for="logo">Logo del complejo</label><input id="logo" name="logo" type="file" accept="image/*" class="form-control">@if ($court?->logo_path)<small class="form-text">Ya hay un logo cargado.</small>@endif</div>
        <div class="col-md-6"><label class="form-label" for="imagenes">Fotos del complejo/canchas</label><input id="imagenes" name="imagenes[]" type="file" accept="image/*" class="form-control" multiple>@if ($court?->imagenes)<small class="form-text">{{ count($court->imagenes) }} foto(s) existentes.</small>@endif</div>
        <div class="col-12">
            <label class="form-label">Ubicación en el mapa (hacé clic para colocar o mover el pin)</label>
            <div id="owner-location-map"></div>
        </div>
        <div class="col-md-6"><label class="form-label" for="latitude">Latitud</label><input id="latitude" name="latitude" type="number" step="any" class="form-control" value="{{ old('latitude', $court->latitude ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label" for="longitude">Longitud</label><input id="longitude" name="longitude" type="number" step="any" class="form-control" value="{{ old('longitude', $court->longitude ?? '') }}" required></div>
    </div>
</div>

<div class="form-section">
    <h4>Plan y cobro manual</h4>
    <p class="text-muted">La prueba inicial dura un mes. No se cobra automáticamente; registrá el pago desde el listado de dueños.</p>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="billing_plan">Nombre del plan</label><input id="billing_plan" name="billing_plan" class="form-control" value="{{ old('billing_plan', $owner->billing_plan ?? '') }}" placeholder="Acordar con el dueño"></div>
        <div class="col-md-6"><label class="form-label" for="billing_amount">Importe acordado (opcional)</label><input id="billing_amount" name="billing_amount" type="number" min="0" step="0.01" class="form-control" value="{{ old('billing_amount', $owner->billing_amount ?? '') }}"></div>
    </div>
</div>
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = document.getElementById('latitude');
    const lng = document.getElementById('longitude');
    const map = L.map('owner-location-map').setView(
        [Number(lat.value) || -34.6037, Number(lng.value) || -58.3816],
        lat.value && lng.value ? 15 : 5
    );
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    let marker;
    function setPin(latitude, longitude) {
        lat.value = latitude.toFixed(7);
        lng.value = longitude.toFixed(7);
        if (marker) marker.setLatLng([latitude, longitude]);
        else marker = L.marker([latitude, longitude], {draggable: true}).addTo(map);
        marker.on('dragend', function (event) {
            const point = event.target.getLatLng();
            lat.value = point.lat.toFixed(7);
            lng.value = point.lng.toFixed(7);
        });
    }
    if (lat.value && lng.value) setPin(Number(lat.value), Number(lng.value));
    map.on('click', event => setPin(event.latlng.lat, event.latlng.lng));
    [lat, lng].forEach(input => input.addEventListener('change', function () {
        if (lat.value && lng.value) {
            setPin(Number(lat.value), Number(lng.value));
            map.setView([Number(lat.value), Number(lng.value)], 15);
        }
    }));
    setTimeout(() => map.invalidateSize(), 100);
});
</script>
@endpush
