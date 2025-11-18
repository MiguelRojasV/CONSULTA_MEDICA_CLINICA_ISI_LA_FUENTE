<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * AdminCitaController
 * Ubicación: app/Http/Controllers/Admin/AdminCitaController.php
 * 
 * ACTUALIZADO: Campos nuevos tipo_cita, duracion_estimada, costo
 */
class AdminCitaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Cita::with(['paciente', 'medico.especialidad']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->input('fecha'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('medico_id')) {
            $query->where('medico_id', $request->input('medico_id'));
        }

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereHas('paciente', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $citas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20);

        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.citas.index', compact('citas', 'medicos'));
    }

    public function create(): View
    {
        $pacientes = Paciente::select('id', 'nombre', 'apellido', 'ci')
            ->orderBy('nombre')
            ->get();
        
        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.citas.create', compact('pacientes', 'medicos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'nullable|string|max:500',
            'tipo_cita' => 'nullable|in:Primera Vez,Control,Emergencia',
            'duracion_estimada' => 'nullable|integer|min:15|max:480',
            'costo' => 'nullable|numeric|min:0',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita = new Cita([
            'paciente_id' => $validated['paciente_id'],
            'medico_id' => $validated['medico_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['fecha'] . ' ' . $validated['hora'],
            'motivo' => $validated['motivo'] ?? null,
            'tipo_cita' => $validated['tipo_cita'] ?? 'Primera Vez',
            'duracion_estimada' => $validated['duracion_estimada'] ?? 30,
            'costo' => $validated['costo'] ?? 0,
            'estado' => $validated['estado'],
        ]);

        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora'
            ]);
        }

        $cita->save();

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita creada exitosamente');
    }

    public function show(Cita $cita): View
    {
        $cita->load(['paciente', 'medico.especialidad', 'recetas.medicamentos', 'historialMedico']);
        return view('admin.citas.show', compact('cita'));
    }

    public function edit(Cita $cita): View
    {
        $pacientes = Paciente::select('id', 'nombre', 'apellido', 'ci')
            ->orderBy('nombre')
            ->get();
        
        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.citas.edit', compact('cita', 'pacientes', 'medicos'));
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'nullable|string|max:500',
            'diagnostico' => 'nullable|string|max:1000',
            'tratamiento' => 'nullable|string|max:1000',
            'observaciones' => 'nullable|string|max:500',
            'tipo_cita' => 'nullable|in:Primera Vez,Control,Emergencia',
            'duracion_estimada' => 'nullable|integer|min:15|max:480',
            'costo' => 'nullable|numeric|min:0',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita->fill([
            'paciente_id' => $validated['paciente_id'],
            'medico_id' => $validated['medico_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['fecha'] . ' ' . $validated['hora'],
            'motivo' => $validated['motivo'],
            'diagnostico' => $validated['diagnostico'],
            'tratamiento' => $validated['tratamiento'],
            'observaciones' => $validated['observaciones'],
            'tipo_cita' => $validated['tipo_cita'] ?? 'Primera Vez',
            'duracion_estimada' => $validated['duracion_estimada'] ?? 30,
            'costo' => $validated['costo'] ?? 0,
            'estado' => $validated['estado'],
        ]);

        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora'
            ]);
        }

        $cita->save();

        if ($validated['estado'] === 'Atendida' && $validated['diagnostico']) {
            $cita->marcarComoAtendida();
        }

        return redirect()->route('admin.citas.show', $cita)
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        if ($cita->estado === 'Atendida') {
            return back()->withErrors([
                'error' => 'No se puede eliminar una cita ya atendida'
            ]);
        }

        $cita->delete();

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }
}