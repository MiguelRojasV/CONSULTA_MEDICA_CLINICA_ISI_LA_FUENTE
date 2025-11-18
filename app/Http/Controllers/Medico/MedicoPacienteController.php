<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * MedicoPacienteController
 * Ubicación: app/Http/Controllers/Medico/MedicoPacienteController.php
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class MedicoPacienteController extends Controller
{
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;

        $pacientesIds = $medico->citas()
            ->distinct()
            ->pluck('paciente_id');

        $query = Paciente::whereIn('id', $pacientesIds);

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $pacientes = $query->orderBy('nombre')->paginate(15);

        return view('medico.pacientes.index', compact('pacientes'));
    }

    public function show(Paciente $paciente): View
    {
        $medico = Auth::user()->medico;

        $haAtendido = $medico->citas()
            ->where('paciente_id', $paciente->id)
            ->exists();

        if (!$haAtendido) {
            abort(403, 'No tiene permisos para ver este paciente');
        }

        $citasConMedico = $paciente->citas()
            ->where('medico_id', $medico->id)
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $ultimaReceta = $paciente->recetas()
            ->where('medico_id', $medico->id)
            ->with('medicamentos')
            ->orderBy('fecha_emision', 'desc')
            ->first();

        return view('medico.pacientes.show', compact(
            'paciente',
            'citasConMedico',
            'ultimaReceta'
        ));
    }

    public function historial(Paciente $paciente): View
    {
        $medico = Auth::user()->medico;

        $haAtendido = $medico->citas()
            ->where('paciente_id', $paciente->id)
            ->exists();

        if (!$haAtendido) {
            abort(403, 'No tiene permisos para ver el historial de este paciente');
        }

        $historial = $paciente->historialMedico()
            ->with(['medico.especialidad', 'cita'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('medico.pacientes.historial', compact('paciente', 'historial'));
    }
}