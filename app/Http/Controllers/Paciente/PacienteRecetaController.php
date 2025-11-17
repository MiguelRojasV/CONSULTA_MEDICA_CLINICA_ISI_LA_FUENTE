<?php 
namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * PacienteRecetaController
 * Permite al paciente ver y descargar sus recetas médicas
 */
class PacienteRecetaController extends Controller
{
    /**
     * Lista todas las recetas del paciente
     * @return View
     */
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        // Obtener recetas ordenadas por fecha
        $recetas = $paciente->recetas()
            ->with(['medico', 'cita', 'medicamentos'])
            ->orderBy('fecha_emision', 'desc')
            ->paginate(10);

        return view('paciente.recetas.index', compact('recetas'));
    }

    /**
     * Muestra los detalles de una receta
     * @param Receta $receta
     * @return View
     */
    public function show(Receta $receta): View
    {
        $paciente = Auth::user()->paciente;

        // Verificar que la receta pertenezca al paciente
        if ($receta->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para ver esta receta');
        }

        $receta->load(['medico', 'cita', 'medicamentos']);

        return view('paciente.recetas.show', compact('receta'));
    }

    /**
     * Descarga la receta en formato PDF
     * @param Receta $receta
     * @return \Illuminate\Http\Response
     */
    public function descargarPDF(Receta $receta)
    {
        $paciente = Auth::user()->paciente;

        // Verificar permisos
        if ($receta->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para descargar esta receta');
        }

        // Cargar relaciones necesarias
        $receta->load(['medico', 'paciente', 'medicamentos', 'cita']);

        // Obtener información de la clínica
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.receta', compact('receta', 'clinica'));

        // Nombre del archivo
        $nombreArchivo = 'receta_' . $receta->id . '_' . now()->format('Ymd') . '.pdf';

        // Descargar el PDF
        return $pdf->download($nombreArchivo);
    }
}