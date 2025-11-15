<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CitaController extends Controller
{
    /**
     * Muestra el listado de citas
     */
    public function index(): View
    {
        $citas = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20);
            
        return view('citas.index', compact('citas'));
    }

    /**
     * Muestra el formulario para crear una nueva cita
     */
    public function create(): View
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $medicos = Medico::orderBy('nombre')->get();
        
        return view('citas.create', compact('pacientes', 'medicos'));
    }

    /**
     * Almacena una nueva cita en la base de datos
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'motivo' => 'nullable|string',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita = new Cita($validated);
        
        if (!$cita->validarCita()) {
            return back()->withInput()
                ->withErrors(['error' => 'Ya existe una cita para este médico en esa fecha y hora']);
        }

        $cita->save();

        return redirect()->route('citas.index')
            ->with('success', 'Cita programada exitosamente');
    }

    /**
     * Muestra los detalles de una cita específica
     */
    public function show(Cita $cita): View
    {
        $cita->load(['paciente', 'medico', 'recetas.medicamentos']);
        return view('citas.show', compact('cita'));
    }

    /**
     * Muestra el formulario para editar una cita
     */
    public function edit(Cita $cita): View
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $medicos = Medico::orderBy('nombre')->get();
        
        return view('citas.edit', compact('cita', 'pacientes', 'medicos'));
    }

    /**
     * Actualiza una cita en la base de datos
     */
    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'motivo' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita->fill($validated);
        
        if (!$cita->validarCita()) {
            return back()->withInput()
                ->withErrors(['error' => 'Ya existe una cita para este médico en esa fecha y hora']);
        }

        $cita->save();

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente');
    }

    /**
     * Elimina una cita de la base de datos
     */
    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }

    /**
     * Genera reporte de citas diarias
     */
    public function reporteDiario(): View
    {
        $fecha = request('fecha', now()->format('Y-m-d'));
        
        $citas = Cita::with(['paciente', 'medico'])
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();
            
        return view('citas.reporte-diario', compact('citas', 'fecha'));
    }

    /**
     * Genera reporte de citas semanales
     */
    public function reporteSemanal(): View
    {
        $fechaInicio = request('fecha_inicio', now()->startOfWeek()->format('Y-m-d'));
        $fechaFin = request('fecha_fin', now()->endOfWeek()->format('Y-m-d'));
        
        $citas = Cita::with(['paciente', 'medico'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
            
        return view('citas.reporte-semanal', compact('citas', 'fechaInicio', 'fechaFin'));
    }
}