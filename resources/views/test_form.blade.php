<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Form - Crear Cancha</h1>
    
    <form method="POST" action="{{ route('admin.canchas.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="Cancha de Prueba" required>
        </div>
        
        <div>
            <label>Dirección:</label>
            <input type="text" name="direccion" value="Calle de Prueba 123" required>
        </div>
        
        <div>
            <label>Ciudad:</label>
            <input type="text" name="ciudad" value="Buenos Aires" required>
        </div>
        
        <div>
            <label>Latitud:</label>
            <input type="number" step="any" name="latitude" value="-34.6037" required>
        </div>
        
        <div>
            <label>Longitud:</label>
            <input type="number" step="any" name="longitude" value="-58.3816" required>
        </div>
        
        <div>
            <label>Tipo de Superficie:</label>
            <select name="tipo_superficie" required>
                <option value="cesped_sintetico" selected>Césped Sintético</option>
            </select>
        </div>
        
        <div>
            <label>Tipo de Cancha:</label>
            <select name="tipo_cancha" required>
                <option value="f5" selected>F5</option>
            </select>
        </div>
        
        <div>
            <label>Capacidad Máxima:</label>
            <input type="number" name="capacidad_maxima" value="10" required>
        </div>
        
        <div>
            <label>Precio Base:</label>
            <input type="number" name="precio_base" value="5000" required>
        </div>
        
        <div>
            <label>Anticipación Mínima (horas):</label>
            <input type="number" name="anticipacion_minima_horas" value="2" required>
        </div>
        
        <div>
            <label>Anticipación Máxima (días):</label>
            <input type="number" name="anticipacion_maxima_dias" value="30" required>
        </div>
        
        <div>
            <label>Horas Cancelación Gratuita:</label>
            <input type="number" name="horas_cancelacion_gratuita" value="24" required>
        </div>
        
        <div>
            <label>Porcentaje Penalidad:</label>
            <input type="number" name="porcentaje_penalidad_cancelacion" value="0" required>
        </div>
        
        <!-- Campos hidden para checkboxes -->
        <input type="hidden" name="tiene_vestuarios" value="0">
        <input type="hidden" name="tiene_estacionamiento" value="0">
        <input type="hidden" name="tiene_iluminacion" value="1">
        <input type="hidden" name="tiene_techado" value="0">
        <input type="hidden" name="permite_cancelacion" value="1">
        
        <button type="submit">Crear Cancha de Prueba</button>
    </form>
</body>
</html>
