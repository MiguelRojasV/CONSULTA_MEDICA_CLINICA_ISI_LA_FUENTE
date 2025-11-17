<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * AdminRecetaController
 * Visualización y descarga de recetas (no creación)
 */
class AdminRecetaController extends Controller
{
    /**
     * Lista todas las recetas del sistema
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Receta::with(['paciente', 'medico', 'medicamentos']);

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_emision', $request->input('fecha'));
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Búsqueda por paciente
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereHas('paciente', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $recetas = $query->orderBy('fecha_emision', 'desc')->paginate(20);

        return view('admin.recetas.index', compact('recetas'));
    }

    /**
     * Muestra los detalles de una receta
     * @param Receta $receta
     * @return View
     */
    public function show(Receta $receta): View
    {
        $receta->load(['paciente', 'medico', 'cita', 'medicamentos']);
        return view('admin.recetas.show', compact('receta'));
    }

    /**
     * Descarga una receta en PDF
     * @param Receta $receta
     * @return \Illuminate\Http\Response
     */
    public function descargarPDF(Receta $receta)
    {
        $receta->load(['medico', 'paciente', 'medicamentos', 'cita']);
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.receta', compact('receta', 'clinica'));
        $nombreArchivo = 'receta_' . $receta->id . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}