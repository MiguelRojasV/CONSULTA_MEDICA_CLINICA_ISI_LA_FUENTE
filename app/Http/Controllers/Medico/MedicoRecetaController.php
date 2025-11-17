<?php 
namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use App\Models\Cita;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * MedicoRecetaController
 * Permite al médico gestionar recetas médicas
 * - Ver lista de recetas emitidas
 * - Crear nuevas recetas para citas atendidas
 * - Editar recetas existentes
 * - Generar PDF de recetas
 * - Agregar medicamentos a recetas
 */
class MedicoRecetaController extends Controller
{
    /**
     * Muestra la lista de recetas emitidas por el médico
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;

        // Query builder para recetas
        $query = $medico->recetas()->with(['paciente', 'cita']);

        // Filtro por búsqueda (nombre paciente o CI)
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereHas('paciente', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->input('fecha_hasta'));
        }

        // Filtro por mes actual (por defecto)
        if (!$request->filled('fecha_desde') && !$request->filled('fecha_hasta')) {
            $query->whereMonth('fecha_emision', now()->month)
                  ->whereYear('fecha_emision', now()->year);
        }

        // Obtener recetas paginadas
        $recetas = $query->orderBy('fecha_emision', 'desc')
                        ->paginate(15);

        return view('medico.recetas.index', compact('recetas'));
    }

    /**
     * Muestra el formulario para crear una nueva receta
     * @param Request $request (opcional: cita_id)
     * @return View
     */
    public function create(Request $request): View
    {
        $medico = Auth::user()->medico;
        
        // Si viene de una cita específica
        $cita = null;
        if ($request->filled('cita_id')) {
            $cita = Cita::with('paciente')->findOrFail($request->input('cita_id'));
            
            // Verificar que la cita pertenezca al médico
            if ($cita->medico_id !== $medico->id) {
                abort(403, 'No tiene permisos para crear receta para esta cita');
            }
        }

        // Obtener lista de medicamentos disponibles
        $medicamentos = Medicamento::orderBy('nombre')->get();

        // Obtener citas atendidas sin receta (para selector)
        $citasSinReceta = $medico->citas()
            ->with('paciente')
            ->where('estado', 'Atendida')
            ->whereDoesntHave('recetas')
            ->orderBy('fecha', 'desc')
            ->take(20)
            ->get();

        return view('medico.recetas.create', compact(
            'cita',
            'medicamentos',
            'citasSinReceta'
        ));
    }

    /**
     * Almacena una nueva receta en la base de datos
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $medico = Auth::user()->medico;

        // Validar datos
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'indicaciones' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento_id' => 'required|exists:medicamentos,id',
            'medicamentos.*.cantidad' => 'required|integer|min:1|max:999',
            'medicamentos.*.dosis' => 'required|string|max:100',
            'medicamentos.*.frecuencia' => 'required|string|max:100',
            'medicamentos.*.duracion' => 'required|string|max:100',
        ], [
            'cita_id.required' => 'Debe seleccionar una cita',
            'cita_id.exists' => 'La cita seleccionada no existe',
            'indicaciones.required' => 'Las indicaciones son obligatorias',
            'medicamentos.required' => 'Debe agregar al menos un medicamento',
            'medicamentos.*.medicamento_id.required' => 'Debe seleccionar un medicamento',
            'medicamentos.*.cantidad.required' => 'La cantidad es obligatoria',
            'medicamentos.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
            'medicamentos.*.dosis.required' => 'La dosis es obligatoria',
            'medicamentos.*.frecuencia.required' => 'La frecuencia es obligatoria',
            'medicamentos.*.duracion.required' => 'La duración es obligatoria',
        ]);

        // Verificar que la cita pertenezca al médico
        $cita = Cita::findOrFail($validated['cita_id']);
        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para crear receta para esta cita');
        }

        // Verificar que la cita esté atendida
        if ($cita->estado !== 'Atendida') {
            return back()->withErrors([
                'error' => 'Solo puede crear recetas para citas atendidas'
            ])->withInput();
        }

        // Crear la receta
        $receta = new Receta();
        $receta->medico_id = $medico->id;
        $receta->paciente_id = $cita->paciente_id;
        $receta->cita_id = $validated['cita_id'];
        $receta->fecha_emision = now();
        $receta->indicaciones = $validated['indicaciones'];
        $receta->observaciones = $validated['observaciones'] ?? null;
        $receta->save();

        // Agregar medicamentos a la receta
        foreach ($validated['medicamentos'] as $med) {
            $receta->medicamentos()->attach($med['medicamento_id'], [
                'cantidad' => $med['cantidad'],
                'dosis' => $med['dosis'],
                'frecuencia' => $med['frecuencia'],
                'duracion' => $med['duracion'],
            ]);
        }

        return redirect()->route('medico.recetas.show', $receta)
            ->with('success', 'Receta creada exitosamente');
    }

    /**
     * Muestra los detalles de una receta específica
     * @param Receta $receta
     * @return View
     */
    public function show(Receta $receta): View
    {
        $medico = Auth::user()->medico;

        // Verificar que la receta pertenezca al médico
        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para ver esta receta');
        }

        // Cargar relaciones
        $receta->load(['paciente', 'cita', 'medicamentos']);

        return view('medico.recetas.show', compact('receta'));
    }

    /**
     * Muestra el formulario para editar una receta
     * @param Receta $receta
     * @return View
     */
    public function edit(Receta $receta): View
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta receta');
        }

        // Cargar relaciones
        $receta->load(['paciente', 'cita', 'medicamentos']);

        // Obtener lista de medicamentos disponibles
        $medicamentos = Medicamento::orderBy('nombre')->get();

        return view('medico.recetas.edit', compact('receta', 'medicamentos'));
    }

    /**
     * Actualiza una receta existente
     * @param Request $request
     * @param Receta $receta
     * @return RedirectResponse
     */
    public function update(Request $request, Receta $receta): RedirectResponse
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta receta');
        }

        // Validar datos
        $validated = $request->validate([
            'indicaciones' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento_id' => 'required|exists:medicamentos,id',
            'medicamentos.*.cantidad' => 'required|integer|min:1|max:999',
            'medicamentos.*.dosis' => 'required|string|max:100',
            'medicamentos.*.frecuencia' => 'required|string|max:100',
            'medicamentos.*.duracion' => 'required|string|max:100',
        ], [
            'indicaciones.required' => 'Las indicaciones son obligatorias',
            'medicamentos.required' => 'Debe agregar al menos un medicamento',
            'medicamentos.*.medicamento_id.required' => 'Debe seleccionar un medicamento',
            'medicamentos.*.cantidad.required' => 'La cantidad es obligatoria',
            'medicamentos.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
            'medicamentos.*.dosis.required' => 'La dosis es obligatoria',
            'medicamentos.*.frecuencia.required' => 'La frecuencia es obligatoria',
            'medicamentos.*.duracion.required' => 'La duración es obligatoria',
        ]);

        // Actualizar receta
        $receta->indicaciones = $validated['indicaciones'];
        $receta->observaciones = $validated['observaciones'] ?? null;
        $receta->save();

        // Eliminar medicamentos anteriores y agregar los nuevos
        $receta->medicamentos()->detach();
        foreach ($validated['medicamentos'] as $med) {
            $receta->medicamentos()->attach($med['medicamento_id'], [
                'cantidad' => $med['cantidad'],
                'dosis' => $med['dosis'],
                'frecuencia' => $med['frecuencia'],
                'duracion' => $med['duracion'],
            ]);
        }

        return redirect()->route('medico.recetas.show', $receta)
            ->with('success', 'Receta actualizada exitosamente');
    }

    /**
     * Genera el PDF de la receta para descargar/imprimir
     * @param Receta $receta
     * @return \Illuminate\Http\Response
     */
    public function pdf(Receta $receta)
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para descargar esta receta');
        }

        // Cargar todas las relaciones necesarias
        $receta->load(['paciente', 'medico.especialidad', 'medicamentos']);

        // Generar PDF
        $pdf = Pdf::loadView('medico.recetas.pdf', compact('receta'));
        
        // Configurar tamaño y orientación
        $pdf->setPaper('letter', 'portrait');

        // Nombre del archivo
        $nombreArchivo = 'receta_' . $receta->paciente->nombre . '_' . $receta->fecha_emision->format('Y-m-d') . '.pdf';

        // Retornar PDF para descarga
        return $pdf->download($nombreArchivo);
    }

    /**
     * Elimina una receta (soft delete si está configurado)
     * @param Receta $receta
     * @return RedirectResponse
     */
    public function destroy(Receta $receta): RedirectResponse
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para eliminar esta receta');
        }

        // Eliminar medicamentos asociados
        $receta->medicamentos()->detach();

        // Eliminar receta
        $receta->delete();

        return redirect()->route('medico.recetas.index')
            ->with('success', 'Receta eliminada exitosamente');
    }
}