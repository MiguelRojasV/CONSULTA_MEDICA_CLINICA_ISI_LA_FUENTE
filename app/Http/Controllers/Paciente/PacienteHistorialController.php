<?php 
namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * PacienteHistorialController
 * Permite al paciente ver y descargar su historial médico completo
 */
class PacienteHistorialController extends Controller
{
    /**
     * Muestra el historial médico del paciente
     * @return View
     */
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        // Obtener historial médico ordenado por fecha
        $historial = $paciente->historialMedico()
            ->with(['medico', 'cita'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        // Obtener estadísticas
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

    /**
     * Descarga el historial médico completo en PDF
     * @return \Illuminate\Http\Response
     */
    public function descargarPDF()
    {
        $paciente = Auth::user()->paciente;

        // Obtener todo el historial
        $historial = $paciente->historialMedico()
            ->with(['medico', 'cita'])
            ->orderBy('fecha', 'desc')
            ->get();

        // Información de la clínica
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        // Generar PDF
        $pdf = Pdf::loadView('pdf.historial-medico', compact('paciente', 'historial', 'clinica'));

        // Configurar orientación y tamaño
        $pdf->setPaper('letter', 'portrait');

        // Nombre del archivo
        $nombreArchivo = 'historial_medico_' . $paciente->ci . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}