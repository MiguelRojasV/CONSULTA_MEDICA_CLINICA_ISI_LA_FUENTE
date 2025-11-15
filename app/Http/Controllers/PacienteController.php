<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::orderBy('nombre')->paginate(15);
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci' => 'required|unique:pacientes|max:20',
            'nombre' => 'required|max:100',
            'edad' => 'required|integer|min:0|max:150',
            'antecedentes' => 'nullable',
            'alergias' => 'nullable',
            'contacto_emergencia' => 'nullable|max:100'
        ]);

        Paciente::create($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente registrado exitosamente');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['citas.medico', 'citas.recetas.medicamentos']);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'ci' => 'required|max:20|unique:pacientes,ci,' . $paciente->id,
            'nombre' => 'required|max:100',
            'edad' => 'required|integer|min:0|max:150',
            'antecedentes' => 'nullable',
            'alergias' => 'nullable',
            'contacto_emergencia' => 'nullable|max:100'
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        
        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado exitosamente');
    }
}