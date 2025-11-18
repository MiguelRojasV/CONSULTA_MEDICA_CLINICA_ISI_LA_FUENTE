<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * PacienteHistorialController
 * Ubicación: app/Http/Controllers/Paciente/PacienteHistorialController.php
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class PacienteHistorialController extends Controller
{
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        $historial = $paciente->historialMedico()
            ->with(['medico.especialidad', 'cita'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        $totalConsultas = $paciente->historialMedico()->count();
        $ultimaConsulta = $paciente->historialMedico()
            ->orderBy('fecha', 'desc')
            ->first();

        return view('paciente.historial.index', compact(
            'historial',
            'paciente',
            'totalConsultas',
            'ultimaConsulta'
        ));
    }

    public function descargarPDF()
    {
        $paciente = Auth::user()->paciente;

        $historial = $paciente->historialMedico()
            ->with(['medico.especialidad', 'cita'])
            ->orderBy('fecha', 'desc')
            ->get();

        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.historial-medico', compact('paciente', 'historial', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'historial_medico_' . $paciente->ci . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}