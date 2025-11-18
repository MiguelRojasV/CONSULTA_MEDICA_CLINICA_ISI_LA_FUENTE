<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * MedicoCitaController
 * Ubicación: app/Http/Controllers/Medico/MedicoCitaController.php
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class MedicoCitaController extends Controller
{
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;
        $fecha = $request->input('fecha', now()->format('Y-m-d'));

        $citas = $medico->citas()
            ->with('paciente')
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('medico.citas.index', compact('citas', 'fecha', 'medico'));
    }

    public function show(Cita $cita): View
    {
        $medico = Auth::user()->medico;

        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para ver esta cita');
        }

        $cita->load(['paciente', 'recetas.medicamentos']);

        return view('medico.citas.show', compact('cita'));
    }

    public function edit(Cita $cita): View
    {
        $medico = Auth::user()->medico;

        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta cita');
        }

        $paciente = $cita->paciente;
        $historial = $paciente->historialMedico()
            ->with('medico.especialidad')
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        return view('medico.citas.edit', compact('cita', 'paciente', 'historial'));
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $medico = Auth::user()->medico;

        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta cita');
        }

        $validated = $request->validate([
            'diagnostico' => 'required|string|max:1000',
            'tratamiento' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'sintomas' => 'nullable|string|max:500',
            'signos_vitales' => 'nullable|string|max:300',
            'estado' => 'required|in:En Consulta,Atendida'
        ], [
            'diagnostico.required' => 'El diagnóstico es obligatorio',
            'tratamiento.required' => 'El tratamiento es obligatorio',
            'estado.required' => 'Debe seleccionar el estado de la cita'
        ]);

        $cita->update([
            'diagnostico' => $validated['diagnostico'],
            'tratamiento' => $validated['tratamiento'],
            'observaciones' => $validated['observaciones'] ?? null,
            'estado' => $validated['estado'],
        ]);

        if ($validated['estado'] === 'Atendida') {
            $cita->marcarComoAtendida();
        }

        return redirect()->route('medico.citas.show', $cita)
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function marcarAtendida(Cita $cita): RedirectResponse
    {
        $medico = Auth::user()->medico;

        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para modificar esta cita');
        }

        if (!$cita->diagnostico) {
            return back()->withErrors([
                'error' => 'Debe registrar el diagnóstico antes de marcar como atendida'
            ]);
        }

        $cita->marcarComoAtendida();

        return redirect()->route('medico.citas.index')
            ->with('success', 'Cita marcada como atendida');
    }
}