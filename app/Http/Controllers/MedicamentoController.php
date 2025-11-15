<?php

// app/Http/Controllers/MedicamentoController.php
namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class MedicamentoController extends Controller
{
    public function index()
    {
        $medicamentos = Medicamento::orderBy('nombre_generico')->paginate(20);
        return view('medicamentos.index', compact('medicamentos'));
    }

    public function create()
    {
        return view('medicamentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|max:200',
            'tipo' => 'nullable|max:100',
            'dosis' => 'nullable|max:100',
            'disponibilidad' => 'required|integer|min:0',
            'caducidad' => 'nullable|date'
        ]);

        Medicamento::create($validated);

        return redirect()->route('medicamentos.index')
            ->with('success', 'Medicamento registrado exitosamente');
    }

    public function show(Medicamento $medicamento)
    {
        return view('medicamentos.show', compact('medicamento'));
    }

    public function edit(Medicamento $medicamento)
    {
        return view('medicamentos.edit', compact('medicamento'));
    }

    public function update(Request $request, Medicamento $medicamento)
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|max:200',
            'tipo' => 'nullable|max:100',
            'dosis' => 'nullable|max:100',
            'disponibilidad' => 'required|integer|min:0',
            'caducidad' => 'nullable|date'
        ]);

        $medicamento->update($validated);

        return redirect()->route('medicamentos.index')
            ->with('success', 'Medicamento actualizado exitosamente');
    }

    public function destroy(Medicamento $medicamento)
    {
        $medicamento->delete();
        
        return redirect()->route('medicamentos.index')
            ->with('success', 'Medicamento eliminado exitosamente');
    }
}