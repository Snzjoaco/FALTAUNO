@extends('layouts.dashboard')

@section('title', 'Agregar Cancha - FaltaUno')
@section('page-title', 'Agregar Nueva Cancha')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error en el formulario:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Información de la Cancha
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.canchas.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Información Básica -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Información Básica
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Cancha *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_cancha" class="form-label">Tipo de Cancha *</label>
                            <select class="form-select @error('tipo_cancha') is-invalid @enderror" 
                                    id="tipo_cancha" name="tipo_cancha" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="f5" {{ old('tipo_cancha') === 'f5' ? 'selected' : '' }}>F5 (5 vs 5)</option>
                                <option value="f7" {{ old('tipo_cancha') === 'f7' ? 'selected' : '' }}>F7 (7 vs 7)</option>
                                <option value="f11" {{ old('tipo_cancha') === 'f11' ? 'selected' : '' }}>F11 (11 vs 11)</option>
                                <option value="mixto" {{ old('tipo_cancha') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                            </select>
                            @error('tipo_cancha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3" 
                                      placeholder="Describe las características de tu cancha...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Ubicación -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                            </h6>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="direccion" class="form-label">Dirección Completa *</label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                                   id="direccion" name="direccion" value="{{ old('direccion') }}" 
                                   placeholder="Se completará automáticamente al seleccionar en el mapa..." readonly>
                            <div class="form-text">Se llenará automáticamente al hacer clic en el mapa</div>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="ciudad" class="form-label">Ciudad *</label>
                            <input type="text" class="form-control @error('ciudad') is-invalid @enderror" 
                                   id="ciudad" name="ciudad" value="{{ old('ciudad') }}" 
                                   placeholder="Se completará automáticamente..." readonly required>
                            @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="barrio" class="form-label">Barrio</label>
                            <input type="text" class="form-control @error('barrio') is-invalid @enderror" 
                                   id="barrio" name="barrio" value="{{ old('barrio') }}" 
                                   placeholder="Se completará automáticamente..." readonly>
                            @error('barrio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" 
                                   id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" 
                                   placeholder="Se completará automáticamente..." readonly>
                            @error('codigo_postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitud *</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                   placeholder="Se llenará automáticamente" required readonly>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitud *</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                   placeholder="Se llenará automáticamente" required readonly>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Mapa Interactivo -->
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Selecciona la ubicación en el mapa</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="centerMapOnBuenosAires()">
                                    <i class="fas fa-crosshairs me-1"></i>Centrar en Buenos Aires
                                </button>
                            </div>
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                            <div class="form-text">Haz clic en el mapa para seleccionar la ubicación exacta de tu cancha</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Instrucciones:</strong><br>
                                1. Haz clic en el mapa para seleccionar la ubicación exacta de tu cancha<br>
                                2. Todos los campos de ubicación se llenarán automáticamente<br>
                                3. Puedes arrastrar el marcador para ajustar la posición
                            </div>
                        </div>
                    </div>
                    
                    <!-- Características -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-cogs me-2"></i>Características
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_superficie" class="form-label">Tipo de Superficie *</label>
                            <select class="form-select @error('tipo_superficie') is-invalid @enderror" 
                                    id="tipo_superficie" name="tipo_superficie" required>
                                <option value="">Seleccionar superficie</option>
                                <option value="cesped_natural" {{ old('tipo_superficie') === 'cesped_natural' ? 'selected' : '' }}>Césped Natural</option>
                                <option value="cesped_sintetico" {{ old('tipo_superficie') === 'cesped_sintetico' ? 'selected' : '' }}>Césped Sintético</option>
                                <option value="cemento" {{ old('tipo_superficie') === 'cemento' ? 'selected' : '' }}>Cemento</option>
                                <option value="parquet" {{ old('tipo_superficie') === 'parquet' ? 'selected' : '' }}>Parquet</option>
                            </select>
                            @error('tipo_superficie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima *</label>
                            <input type="number" class="form-control @error('capacidad_maxima') is-invalid @enderror" 
                                   id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima', 10) }}" 
                                   min="1" max="50" required>
                            @error('capacidad_maxima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Amenities</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="tiene_vestuarios" value="0">
                                        <input class="form-check-input" type="checkbox" id="tiene_vestuarios" 
                                               name="tiene_vestuarios" value="1" {{ old('tiene_vestuarios') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tiene_vestuarios">
                                            Vestuarios
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="tiene_estacionamiento" value="0">
                                        <input class="form-check-input" type="checkbox" id="tiene_estacionamiento" 
                                               name="tiene_estacionamiento" value="1" {{ old('tiene_estacionamiento') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tiene_estacionamiento">
                                            Estacionamiento
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="tiene_iluminacion" value="0">
                                        <input class="form-check-input" type="checkbox" id="tiene_iluminacion" 
                                               name="tiene_iluminacion" value="1" {{ old('tiene_iluminacion', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tiene_iluminacion">
                                            Iluminación
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="tiene_techado" value="0">
                                        <input class="form-check-input" type="checkbox" id="tiene_techado" 
                                               name="tiene_techado" value="1" {{ old('tiene_techado') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tiene_techado">
                                            Techado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Precios -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-dollar-sign me-2"></i>Precios (en pesos argentinos)
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="precio_base" class="form-label">Precio Base por Hora *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('precio_base') is-invalid @enderror" 
                                       id="precio_base" name="precio_base" value="{{ old('precio_base') }}" 
                                       min="0" max="99999999" step="0.01" required>
                            </div>
                            <div class="form-text">Máximo: $99,999,999.99</div>
                            @error('precio_base')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="precio_pico" class="form-label">Precio Horario Pico</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('precio_pico') is-invalid @enderror" 
                                       id="precio_pico" name="precio_pico" value="{{ old('precio_pico') }}" 
                                       min="0" max="99999999" step="0.01">
                            </div>
                            <div class="form-text">Máximo: $99,999,999.99</div>
                            @error('precio_pico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="precio_valle" class="form-label">Precio Horario Valle</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('precio_valle') is-invalid @enderror" 
                                       id="precio_valle" name="precio_valle" value="{{ old('precio_valle') }}" 
                                       min="0" max="99999999" step="0.01">
                            </div>
                            <div class="form-text">Máximo: $99,999,999.99</div>
                            @error('precio_valle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Configuración de Reservas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Configuración de Reservas
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="anticipacion_minima_horas" class="form-label">Anticipación Mínima (horas) *</label>
                            <input type="number" class="form-control @error('anticipacion_minima_horas') is-invalid @enderror" 
                                   id="anticipacion_minima_horas" name="anticipacion_minima_horas" 
                                   value="{{ old('anticipacion_minima_horas', 2) }}" min="1" max="168" required>
                            @error('anticipacion_minima_horas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="anticipacion_maxima_dias" class="form-label">Anticipación Máxima (días) *</label>
                            <input type="number" class="form-control @error('anticipacion_maxima_dias') is-invalid @enderror" 
                                   id="anticipacion_maxima_dias" name="anticipacion_maxima_dias" 
                                   value="{{ old('anticipacion_maxima_dias', 30) }}" min="1" max="365" required>
                            @error('anticipacion_maxima_dias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="hidden" name="permite_cancelacion" value="0">
                                <input class="form-check-input" type="checkbox" id="permite_cancelacion" 
                                       name="permite_cancelacion" value="1" {{ old('permite_cancelacion', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permite_cancelacion">
                                    Permite Cancelación
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="horas_cancelacion_gratuita" class="form-label">Horas para Cancelación Gratuita</label>
                            <input type="number" class="form-control @error('horas_cancelacion_gratuita') is-invalid @enderror" 
                                   id="horas_cancelacion_gratuita" name="horas_cancelacion_gratuita" 
                                   value="{{ old('horas_cancelacion_gratuita', 24) }}" min="0" max="168">
                            @error('horas_cancelacion_gratuita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="porcentaje_penalidad_cancelacion" class="form-label">Porcentaje de Penalidad por Cancelación (%)</label>
                            <input type="number" class="form-control @error('porcentaje_penalidad_cancelacion') is-invalid @enderror" 
                                   id="porcentaje_penalidad_cancelacion" name="porcentaje_penalidad_cancelacion" 
                                   value="{{ old('porcentaje_penalidad_cancelacion', 0) }}" min="0" max="100" step="0.01">
                            <div class="form-text">Porcentaje que se cobra por cancelación tardía (0-100%)</div>
                            @error('porcentaje_penalidad_cancelacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Imágenes -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-images me-2"></i>Imágenes de la Cancha
                            </h6>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="imagenes" class="form-label">Subir Imágenes</label>
                            <input type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                                   id="imagenes" name="imagenes[]" multiple accept="image/*">
                            <div class="form-text">Puedes subir múltiples imágenes. La primera será la imagen principal.</div>
                            @error('imagenes.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-phone me-2"></i>Información de Contacto
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="sitio_web" class="form-label">Sitio Web</label>
                            <input type="url" class="form-control @error('sitio_web') is-invalid @enderror" 
                                   id="sitio_web" name="sitio_web" value="{{ old('sitio_web') }}" 
                                   placeholder="https://...">
                            @error('sitio_web')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Botones -->

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.canchas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Crear Cancha
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Geocoding -->
<script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>

<script>
    let map;
    let marker;
    let geocoder;

    // Inicializar el mapa cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });

    function initMap() {
        // Configuración inicial del mapa (Buenos Aires por defecto)
        const defaultLocation = [-34.6037, -58.3816];
        
        // Crear el mapa
        map = L.map('map').setView(defaultLocation, 13);

        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Crear marcador inicial
        marker = L.marker(defaultLocation, {
            draggable: true,
            title: "Ubicación de la cancha"
        }).addTo(map);

        // Configurar geocoder
        geocoder = L.Control.Geocoder.nominatim();

        // No necesitamos autocompletado, todo se llena desde el mapa

        // Cuando se hace clic en el mapa
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            marker.setLatLng([lat, lng]);
            updateCoordinates(lat, lng);
            
            // Geocodificación inversa para obtener la dirección
            reverseGeocode(lat, lng);
        });

        // Cuando se arrastra el marcador
        marker.on('dragend', function(e) {
            const lat = e.target.getLatLng().lat;
            const lng = e.target.getLatLng().lng;
            
            updateCoordinates(lat, lng);
            reverseGeocode(lat, lng);
        });

        // Si hay valores previos, centrar el mapa en esa ubicación
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        if (lat && lng) {
            const location = [parseFloat(lat), parseFloat(lng)];
            map.setView(location, 16);
            marker.setLatLng(location);
        }
    }

    // Ya no necesitamos autocompletado, todo se maneja desde el mapa

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('direccion').value = data.display_name;
                    updateAddressFields(data);
                }
            })
            .catch(error => {
                console.error('Error en geocodificación inversa:', error);
            });
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    }

    function updateAddressFields(data) {
        const address = data.address || {};
        
        // Actualizar campos del formulario con mejor manejo de datos
        let ciudad = address.city || address.town || address.village || address.municipality || address.county;
        if (ciudad) {
            document.getElementById('ciudad').value = ciudad;
        }
        
        let barrio = address.suburb || address.neighbourhood || address.hamlet || address.village;
        if (barrio) {
            document.getElementById('barrio').value = barrio;
        }
        
        if (address.postcode) {
            document.getElementById('codigo_postal').value = address.postcode;
        }
        
        // Mostrar mensaje de éxito
        showLocationSuccess();
    }
    
    function showLocationSuccess() {
        // Crear o actualizar mensaje de éxito
        let successMsg = document.getElementById('location-success');
        if (!successMsg) {
            successMsg = document.createElement('div');
            successMsg.id = 'location-success';
            successMsg.className = 'alert alert-success mt-2';
            successMsg.innerHTML = '<i class="fas fa-check-circle me-2"></i>Ubicación seleccionada correctamente';
            document.getElementById('map').parentNode.appendChild(successMsg);
        }
        
        // Mostrar por 3 segundos
        successMsg.style.display = 'block';
        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 3000);
    }
    
    // Función para centrar el mapa en Buenos Aires
    function centerMapOnBuenosAires() {
        const buenosAires = [-34.6037, -58.3816];
        map.setView(buenosAires, 13);
        marker.setLatLng(buenosAires);
        updateCoordinates(buenosAires[0], buenosAires[1]);
        reverseGeocode(buenosAires[0], buenosAires[1]);
    }

    // Validación en tiempo real para precios
    document.getElementById('precio_base').addEventListener('input', function() {
        const precioBase = parseFloat(this.value);
        const precioPico = document.getElementById('precio_pico');
        const precioValle = document.getElementById('precio_valle');
        
        if (precioPico.value && parseFloat(precioPico.value) < precioBase) {
            precioPico.setCustomValidity('El precio pico debe ser mayor al precio base');
        } else {
            precioPico.setCustomValidity('');
        }
        
        if (precioValle.value && parseFloat(precioValle.value) > precioBase) {
            precioValle.setCustomValidity('El precio valle debe ser menor al precio base');
        } else {
            precioValle.setCustomValidity('');
        }
    });

    // Validación del formulario antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!lat || !lng) {
            e.preventDefault();
            alert('Por favor, selecciona una ubicación en el mapa haciendo clic en él.');
            return false;
        }
    });

</script>
@endpush
