<?php
namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Cita;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecetaController extends Controller
{
    public function index()
    {
        $recetas = Receta::with(['cita.paciente', 'cita.medico'])
            ->orderBy('fecha_emision', 'desc')
            ->paginate(20);
            
        return view('recetas.index', compact('recetas'));
    }

    public function create()
    {
        $citas = Cita::with(['paciente', 'medico'])
            ->where('estado', 'En Consulta')
            ->orWhere('estado', 'Atendida')
            ->get();
            
        $medicamentos = Medicamento::where('disponibilidad', '>', 0)
            ->orderBy('nombre_generico')
            ->get();
            
        return view('recetas.create', compact('citas', 'medicamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'fecha_emision' => 'required|date',
            'indicaciones' => 'nullable',
            'dosis' => 'nullable',
            'medicamentos' => 'required|array',
            'medicamentos.*.id' => 'required|exists:medicamentos,id',
            'medicamentos.*.cantidad' => 'required|integer|min:1'
        ]);

        $receta = Receta::create([
            'cita_id' => $validated['cita_id'],
            'fecha_emision' => $validated['fecha_emision'],
            'indicaciones' => $validated['indicaciones'],
            'dosis' => $validated['dosis']
        ]);

        foreach ($validated['medicamentos'] as $med) {
            $receta->medicamentos()->attach($med['id'], ['cantidad' => $med['cantidad']]);
        }

        return redirect()->route('recetas.show', $receta)
            ->with('success', 'Receta creada exitosamente');
    }

    public function show(Receta $receta)
    {
        $receta->load(['cita.paciente', 'cita.medico', 'medicamentos']);
        return view('recetas.show', compact('receta'));
    }
}