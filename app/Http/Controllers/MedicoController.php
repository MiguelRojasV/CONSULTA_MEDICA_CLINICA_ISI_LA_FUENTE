<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::orderBy('nombre')->paginate(15);
        return view('medicos.index', compact('medicos'));
    }

    public function create()
    {
        return view('medicos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci' => 'required|unique:medicos|max:20',
            'nombre' => 'required|max:100',
            'especialidad' => 'required|max:100',
            'turno' => 'nullable|max:50',
            'formacion_continua' => 'nullable'
        ]);

        Medico::create($validated);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico registrado exitosamente');
    }

    public function show(Medico $medico)
    {
        $medico->load('citas.paciente');
        return view('medicos.show', compact('medico'));
    }

    public function edit(Medico $medico)
    {
        return view('medicos.edit', compact('medico'));
    }

    public function update(Request $request, Medico $medico)
    {
        $validated = $request->validate([
            'ci' => 'required|max:20|unique:medicos,ci,' . $medico->id,
            'nombre' => 'required|max:100',
            'especialidad' => 'required|max:100',
            'turno' => 'nullable|max:50',
            'formacion_continua' => 'nullable'
        ]);

        $medico->update($validated);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico actualizado exitosamente');
    }

    public function destroy(Medico $medico)
    {
        $medico->delete();
        
        return redirect()->route('medicos.index')
            ->with('success', 'Médico eliminado exitosamente');
    }
}