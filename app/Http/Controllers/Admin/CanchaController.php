<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CanchaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar todas las canchas del usuario
     */
    public function index(Request $request)
    {
        $query = $this->managedCanchas();
        
        // Filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'activas') {
                $query->where('activa', true);
            } elseif ($request->estado === 'inactivas') {
                $query->where('activa', false);
            } elseif ($request->estado === 'verificadas') {
                $query->where('verificada', true);
            }
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo_cancha', $request->tipo);
        }
        
        if ($request->filled('ciudad')) {
            $query->where('ciudad', 'like', '%' . $request->ciudad . '%');
        }
        
        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        
        $canchas = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.canchas.index', compact('canchas'));
    }

    /**
     * Mostrar formulario para crear nueva cancha
     */
    public function create()
    {
        return view('admin.canchas.create');
    }

    /**
     * Almacenar nueva cancha
     */
    public function store(Request $request)
    {
        // Debug: Log de los datos recibidos
        \Log::info('=== INICIO STORE CANCHA ===');
        \Log::info('Usuario autenticado:', ['id' => Auth::id(), 'name' => Auth::user()->name ?? 'No name']);
        \Log::info('Datos recibidos en store:', $request->all());
        
        // Verificar autenticación
        if (!Auth::check()) {
            \Log::error('Usuario no autenticado');
            return redirect()
                ->route('login')
                ->with('error', 'Debes iniciar sesión para crear una cancha');
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad' => 'required|string|max:100',
            'barrio' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'codigo_postal' => 'nullable|string|max:20',
            'tipo_superficie' => 'required|in:cesped_natural,cesped_sintetico,cemento,parquet',
            'tipo_cancha' => 'required|in:f5,f7,f11,mixto',
            'capacidad_maxima' => 'required|integer|min:1|max:50',
            'tiene_vestuarios' => 'boolean',
            'tiene_estacionamiento' => 'boolean',
            'tiene_iluminacion' => 'boolean',
            'tiene_techado' => 'boolean',
            'precio_base' => 'required|numeric|min:0|max:99999999.99',
            'precio_pico' => 'nullable|numeric|min:0|max:99999999.99',
            'precio_valle' => 'nullable|numeric|min:0|max:99999999.99',
            'anticipacion_minima_horas' => 'required|integer|min:1|max:168',
            'anticipacion_maxima_dias' => 'required|integer|min:1|max:365',
            'permite_cancelacion' => 'boolean',
            'horas_cancelacion_gratuita' => 'required|integer|min:0|max:168',
            'porcentaje_penalidad_cancelacion' => 'required|numeric|min:0|max:100',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'instagram_url' => 'nullable|url|max:255',
            'horario_atencion' => 'nullable|string|max:1000',
            'horarios_semana' => 'nullable|array',
            'horarios_fin_semana' => 'nullable|array',
        ]);

        $data = $validated;
        $data['propietario_id'] = Auth::id();
        $data['activa'] = true;
        $data['verificada'] = false;
        $data['suspendida_admin'] = false;
        $data['instagram_url'] = $request->input('instagram_url');
        $data['horario_atencion'] = $request->input('horario_atencion');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('complejos/logos', 'public');
        }
        
        // Debug: Log de los datos procesados
        \Log::info('Datos procesados antes de crear:', $data);
        
        // Procesar amenities
        $amenities = [];
        if ($request->has('amenities')) {
            $amenities = $request->amenities;
        }
        $data['amenities'] = $amenities;
        
        // Procesar horarios
        $data['horarios_semana'] = $request->horarios_semana ?? [];
        $data['horarios_fin_semana'] = $request->horarios_fin_semana ?? [];
        
        // Procesar imágenes
        if ($request->hasFile('imagenes')) {
            $imagenes = [];
            foreach ($request->file('imagenes') as $imagen) {
                $nombreArchivo = Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('canchas', $nombreArchivo, 'public');
                $imagenes[] = $ruta;
            }
            $data['imagenes'] = $imagenes;
            
            // La primera imagen es la principal
            if (!empty($imagenes)) {
                $data['imagen_principal'] = $imagenes[0];
            }
        }
        
        try {
            $cancha = Cancha::create($data);
            AdminActivity::record('court.created', $cancha, null, [
                'owner_id' => $cancha->propietario_id,
                'name' => $cancha->nombre,
            ]);
            \Log::info('Cancha creada exitosamente:', ['id' => $cancha->id, 'nombre' => $cancha->nombre]);
            
            return redirect()
                ->route('admin.canchas.show', $cancha->id)
                ->with('success', '¡Cancha "' . $cancha->nombre . '" creada exitosamente!');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error de base de datos al crear cancha:', [
                'error' => $e->getMessage(), 
                'data' => $data,
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error de base de datos: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error general al crear cancha:', ['error' => $e->getMessage(), 'data' => $data]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una cancha
     */
    public function show($id)
    {
        $cancha = $this->managedCanchas()->findOrFail($id);
        return view('admin.canchas.show', compact('cancha'));
    }

    /**
     * Mostrar formulario para editar cancha
     */
    public function edit($id)
    {
        $cancha = $this->managedCanchas()->findOrFail($id);
        return view('admin.canchas.edit', compact('cancha'));
    }

    /**
     * Actualizar cancha
     */
    public function update(Request $request, $id)
    {
        $cancha = $this->managedCanchas()->findOrFail($id);
        $oldValues = $cancha->only([
            'nombre', 'direccion', 'ciudad', 'precio_base', 'activa', 'destacada',
            'instagram_url', 'horario_atencion',
        ]);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'direccion' => 'required|string|max:500',
            'ciudad' => 'required|string|max:100',
            'barrio' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'codigo_postal' => 'nullable|string|max:20',
            'tipo_superficie' => 'required|in:cesped_natural,cesped_sintetico,cemento,parquet',
            'tipo_cancha' => 'required|in:f5,f7,f11,mixto',
            'capacidad_maxima' => 'required|integer|min:1|max:50',
            'tiene_vestuarios' => 'boolean',
            'tiene_estacionamiento' => 'boolean',
            'tiene_iluminacion' => 'boolean',
            'tiene_techado' => 'boolean',
            'precio_base' => 'required|numeric|min:0|max:99999999.99',
            'precio_pico' => 'nullable|numeric|min:0|max:99999999.99',
            'precio_valle' => 'nullable|numeric|min:0|max:99999999.99',
            'anticipacion_minima_horas' => 'required|integer|min:1|max:168',
            'anticipacion_maxima_dias' => 'required|integer|min:1|max:365',
            'permite_cancelacion' => 'boolean',
            'horas_cancelacion_gratuita' => 'required|integer|min:0|max:168',
            'porcentaje_penalidad_cancelacion' => 'required|numeric|min:0|max:100',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'instagram_url' => 'nullable|url|max:255',
            'horario_atencion' => 'nullable|string|max:1000',
            'horarios_semana' => 'nullable|array',
            'horarios_fin_semana' => 'nullable|array',
        ]);

        $data = $validated;
        
        // Procesar amenities
        $amenities = [];
        if ($request->has('amenities')) {
            $amenities = $request->amenities;
        }
        $data['amenities'] = $amenities;
        
        // Procesar horarios
        $data['horarios_semana'] = $request->horarios_semana ?? [];
        $data['horarios_fin_semana'] = $request->horarios_fin_semana ?? [];
        $data['instagram_url'] = $request->input('instagram_url');
        $data['horario_atencion'] = $request->input('horario_atencion');
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('complejos/logos', 'public');
        }
        
        // Procesar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            $imagenesExistentes = $cancha->imagenes ?? [];
            $nuevasImagenes = [];
            
            foreach ($request->file('imagenes') as $imagen) {
                $nombreArchivo = Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $imagen->storeAs('canchas', $nombreArchivo, 'public');
                $nuevasImagenes[] = $ruta;
            }
            
            $data['imagenes'] = array_merge($imagenesExistentes, $nuevasImagenes);
            
            // Si no hay imagen principal, usar la primera
            if (!$cancha->imagen_principal && !empty($nuevasImagenes)) {
                $data['imagen_principal'] = $nuevasImagenes[0];
            }
        }
        
        $cancha->update($data);
        AdminActivity::record('court.updated', $cancha, $oldValues, $cancha->only([
            'nombre', 'direccion', 'ciudad', 'precio_base', 'activa', 'destacada',
            'instagram_url', 'horario_atencion',
        ]));
        
        return redirect()
            ->route('admin.canchas.show', $cancha->id)
            ->with('success', 'Cancha actualizada exitosamente');
    }

    /**
     * Eliminar cancha
     */
    public function destroy($id)
    {
        $cancha = $this->managedCanchas()->findOrFail($id);
        $oldValues = $cancha->only(['activa', 'suspendida_admin']);

        $cancha->update([
            'activa' => false,
            'suspendida_admin' => Auth::user()->isPlatformAdmin(),
        ]);
        AdminActivity::record('court.deactivated', $cancha, $oldValues, $cancha->only(['activa', 'suspendida_admin']));
        
        return redirect()
            ->route('admin.canchas.index')
            ->with('success', 'Cancha desactivada exitosamente');
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleEstado($id)
    {
        $cancha = $this->managedCanchas()->findOrFail($id);

        if ($cancha->suspendida_admin && !Auth::user()->isPlatformAdmin()) {
            abort(403, 'Solo un administrador de plataforma puede reactivar una cancha suspendida.');
        }

        $oldActive = $cancha->activa;
        $cancha->update(['activa' => !$cancha->activa]);
        AdminActivity::record('court.status_toggled', $cancha, ['activa' => $oldActive], [
            'activa' => $cancha->activa,
        ]);
        
        $estado = $cancha->activa ? 'activada' : 'desactivada';
        
        return redirect()
            ->back()
            ->with('success', "Cancha {$estado} exitosamente");
    }

    /**
     * Get detailed information about a cancha for the modal
     */
    public function getDetails(Cancha $cancha)
    {
        abort_unless($this->managedCanchas()->whereKey($cancha->id)->exists(), 404);

        try {
            // Cargar la cancha con sus relaciones
            $cancha->load('propietario');
            
            $reservas = $cancha->reservas()->with('usuario')->latest('fecha_hora_inicio')->limit(10)->get();

            return response()->json([
                'cancha' => $cancha,
                'reservas' => $reservas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener detalles de cancha:', ['error' => $e->getMessage(), 'cancha_id' => $cancha->id]);
            
            return response()->json(['error' => 'Error al cargar la información de la cancha'], 500);
        }

    }

    private function managedCanchas(): Builder
    {
        $query = Cancha::query();

        if (!Auth::user()->isPlatformAdmin()) {
            $query->where('propietario_id', Auth::id());
        }

        return $query;
    }
}
