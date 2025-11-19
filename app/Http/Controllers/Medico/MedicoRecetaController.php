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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log; 

/**
 * MedicoRecetaController
 * Ubicación: app/Http/Controllers/Medico/MedicoRecetaController.php
 * 
 * Permite al médico gestionar recetas médicas
 * 
 * ACTUALIZADO COMPLETO: Compatible con nueva estructura 3FN
 * - Tabla pivot: receta_medicamento con campos adicionales
 * - Campos nuevos: observaciones, valida_hasta
 * - Validaciones mejoradas
 */
class MedicoRecetaController extends Controller
{
    /**
     * Muestra la lista de recetas emitidas por el médico
     */
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;

        $query = $medico->recetas()->with(['paciente', 'cita']);

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereHas('paciente', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
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

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Por defecto: mes actual
        if (!$request->filled('fecha_desde') && !$request->filled('fecha_hasta')) {
            $query->whereMonth('fecha_emision', now()->month)
                  ->whereYear('fecha_emision', now()->year);
        }

        $recetas = $query->orderBy('fecha_emision', 'desc')->paginate(15);

        return view('medico.recetas.index', compact('recetas'));
    }

    /**
     * Muestra el formulario para crear una nueva receta
     */
    public function create(Request $request): View|RedirectResponse  // ← CAMBIO AQUÍ
    {
        $medico = Auth::user()->medico;
        
        $cita = null;
        if ($request->filled('cita_id')) {
            $cita = Cita::with('paciente')->findOrFail($request->input('cita_id'));
            
            if ($cita->medico_id !== $medico->id) {
                abort(403, 'No tiene permisos para crear receta para esta cita');
            }
        }

        // Medicamentos disponibles con stock
        $medicamentos = Medicamento::disponibles()
            ->orderBy('nombre_generico')
            ->get();

        // Citas atendidas sin receta
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
     * Almacena una nueva receta
     */
    public function store(Request $request): RedirectResponse
    {
        $medico = Auth::user()->medico;

        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'indicaciones' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'valida_hasta' => 'nullable|date|after:today',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento_id' => 'required|exists:medicamentos,id',
            'medicamentos.*.cantidad' => 'required|integer|min:1|max:999',
            'medicamentos.*.dosis' => 'required|string|max:100',
            'medicamentos.*.frecuencia' => 'required|string|max:100',
            'medicamentos.*.duracion' => 'required|string|max:100',
            'medicamentos.*.instrucciones_especiales' => 'nullable|string|max:200',
        ], [
            'cita_id.required' => 'Debe seleccionar una cita',
            'indicaciones.required' => 'Las indicaciones son obligatorias',
            'medicamentos.required' => 'Debe agregar al menos un medicamento',
            'medicamentos.*.medicamento_id.required' => 'Debe seleccionar un medicamento',
            'medicamentos.*.cantidad.required' => 'La cantidad es obligatoria',
            'medicamentos.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
            'medicamentos.*.dosis.required' => 'La dosis es obligatoria',
            'medicamentos.*.frecuencia.required' => 'La frecuencia es obligatoria',
            'medicamentos.*.duracion.required' => 'La duración es obligatoria',
        ]);

        // Verificar cita
        $cita = Cita::findOrFail($validated['cita_id']);
        if ($cita->medico_id !== $medico->id) {
            abort(403);
        }

        if ($cita->estado !== 'Atendida') {
            return back()->withErrors([
                'error' => 'Solo puede crear recetas para citas atendidas'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Crear receta
            $receta = Receta::create([
                'medico_id' => $medico->id,
                'paciente_id' => $cita->paciente_id,
                'cita_id' => $validated['cita_id'],
                'fecha_emision' => now(),
                'indicaciones' => $validated['indicaciones'],
                'observaciones' => $validated['observaciones'] ?? null,
                'valida_hasta' => $validated['valida_hasta'] ?? now()->addMonths(1),
                'estado' => 'Pendiente',
            ]);

            // Agregar medicamentos
            foreach ($validated['medicamentos'] as $med) {
                $receta->medicamentos()->attach($med['medicamento_id'], [
                    'cantidad' => $med['cantidad'],
                    'dosis' => $med['dosis'],
                    'frecuencia' => $med['frecuencia'],
                    'duracion' => $med['duracion'],
                    'instrucciones_especiales' => $med['instrucciones_especiales'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('medico.recetas.show', $receta)
                ->with('success', 'Receta creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear receta: ' . $e->getMessage());
            
            return back()->withInput()->withErrors([
                'error' => 'Error al crear la receta. Inténtelo nuevamente.'
            ]);
        }
    }

    /**
     * Muestra los detalles de una receta
     */
    public function show(Receta $receta): View|RedirectResponse  // ← CAMBIO AQUÍ
    {
        $medico = Auth::user()->medico;

        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para ver esta receta');
        }

        $receta->load(['paciente', 'cita', 'medicamentos']);

        return view('medico.recetas.show', compact('receta'));
    }

    /**
     * Muestra el formulario para editar una receta
     */
    public function edit(Receta $receta): View|RedirectResponse  // ← CAMBIO AQUÍ
    {
        $medico = Auth::user()->medico;

        if ($receta->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta receta');
        }

        // Solo se pueden editar recetas pendientes
        if ($receta->estado !== 'Pendiente') {
            return redirect()->route('medico.recetas.show', $receta)
                ->with('error', 'No se pueden editar recetas ya dispensadas o canceladas');
        }

        $receta->load(['paciente', 'cita', 'medicamentos']);
        $medicamentos = Medicamento::disponibles()->orderBy('nombre_generico')->get();

        return view('medico.recetas.edit', compact('receta', 'medicamentos'));
    }

    /**
     * Actualiza una receta existente
     */
    public function update(Request $request, Receta $receta): RedirectResponse
    {
        $medico = Auth::user()->medico;

        if ($receta->medico_id !== $medico->id) {
            abort(403);
        }

        if ($receta->estado !== 'Pendiente') {
            return back()->withErrors([
                'error' => 'No se pueden editar recetas ya dispensadas o canceladas'
            ]);
        }

        $validated = $request->validate([
            'indicaciones' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'valida_hasta' => 'nullable|date|after:today',
            'medicamentos' => 'required|array|min:1',
            'medicamentos.*.medicamento_id' => 'required|exists:medicamentos,id',
            'medicamentos.*.cantidad' => 'required|integer|min:1|max:999',
            'medicamentos.*.dosis' => 'required|string|max:100',
            'medicamentos.*.frecuencia' => 'required|string|max:100',
            'medicamentos.*.duracion' => 'required|string|max:100',
            'medicamentos.*.instrucciones_especiales' => 'nullable|string|max:200',
        ]);

        DB::beginTransaction();
        try {
            $receta->update([
                'indicaciones' => $validated['indicaciones'],
                'observaciones' => $validated['observaciones'] ?? null,
                'valida_hasta' => $validated['valida_hasta'],
            ]);

            // Actualizar medicamentos
            $receta->medicamentos()->detach();
            foreach ($validated['medicamentos'] as $med) {
                $receta->medicamentos()->attach($med['medicamento_id'], [
                    'cantidad' => $med['cantidad'],
                    'dosis' => $med['dosis'],
                    'frecuencia' => $med['frecuencia'],
                    'duracion' => $med['duracion'],
                    'instrucciones_especiales' => $med['instrucciones_especiales'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('medico.recetas.show', $receta)
                ->with('success', 'Receta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'error' => 'Error al actualizar la receta'
            ]);
        }
    }

    /**
     * Genera el PDF de la receta
     */
    public function pdf(Receta $receta)  // ← SIN tipo de retorno (retorna Response de PDF)
    {
        $medico = Auth::user()->medico;

        if ($receta->medico_id !== $medico->id) {
            abort(403);
        }

        $receta->load(['paciente', 'medico.especialidad', 'medicamentos']);
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.receta', compact('receta', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'receta_' . $receta->paciente->ci . '_' . $receta->fecha_emision->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Elimina una receta
     */
    public function destroy(Receta $receta): RedirectResponse
    {
        $medico = Auth::user()->medico;

        if ($receta->medico_id !== $medico->id) {
            abort(403);
        }

        if ($receta->estado === 'Dispensada') {
            return back()->withErrors([
                'error' => 'No se pueden eliminar recetas ya dispensadas'
            ]);
        }

        $receta->medicamentos()->detach();
        $receta->delete();

        return redirect()->route('medico.recetas.index')
            ->with('success', 'Receta eliminada exitosamente');
    }
}