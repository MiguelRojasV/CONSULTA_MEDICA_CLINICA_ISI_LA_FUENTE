<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * PacienteRecetaController
 * Ubicación: app/Http/Controllers/Paciente/PacienteRecetaController.php
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class PacienteRecetaController extends Controller
{
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        $recetas = $paciente->recetas()
            ->with(['medico.especialidad', 'cita', 'medicamentos'])
            ->orderBy('fecha_emision', 'desc')
            ->paginate(10);

        return view('paciente.recetas.index', compact('recetas'));
    }

    public function show(Receta $receta): View
    {
        $paciente = Auth::user()->paciente;

        if ($receta->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para ver esta receta');
        }

        $receta->load(['medico.especialidad', 'cita', 'medicamentos']);

        return view('paciente.recetas.show', compact('receta'));
    }

    public function descargarPDF(Receta $receta)
    {
        $paciente = Auth::user()->paciente;

        if ($receta->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para descargar esta receta');
        }

        $receta->load(['medico.especialidad', 'paciente', 'medicamentos', 'cita']);
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.receta', compact('receta', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'receta_' . $paciente->ci . '_' . $receta->fecha_emision->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}