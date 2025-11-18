<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * AdminRecetaController
 * Ubicación: app/Http/Controllers/Admin/AdminRecetaController.php
 * 
 * Visualización y descarga de recetas (no creación)
 * ACTUALIZADO: Usa medico->especialidad
 */
class AdminRecetaController extends Controller
{
    /**
     * Lista todas las recetas del sistema
     */
    public function index(Request $request): View
    {
        $query = Receta::with(['paciente', 'medico.especialidad', 'medicamentos']);

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->input('fecha_desde'));
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->input('fecha_hasta'));
        }

        // Filtro por mes actual (por defecto)
        if (!$request->filled('fecha_desde') && !$request->filled('fecha_hasta')) {
            $query->whereMonth('fecha_emision', now()->month)
                  ->whereYear('fecha_emision', now()->year);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro por médico
        if ($request->filled('medico_id')) {
            $query->where('medico_id', $request->input('medico_id'));
        }

        // Búsqueda por paciente
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereHas('paciente', function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $recetas = $query->orderBy('fecha_emision', 'desc')->paginate(20);

        // Para filtros
        $medicos = \App\Models\Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.recetas.index', compact('recetas', 'medicos'));
    }

    /**
     * Muestra los detalles de una receta
     */
    public function show(Receta $receta): View
    {
        $receta->load([
            'paciente',
            'medico.especialidad',
            'cita',
            'medicamentos'
        ]);

        return view('admin.recetas.show', compact('receta'));
    }

    /**
     * Descarga una receta en PDF
     */
    public function descargarPDF(Receta $receta)
    {
        $receta->load([
            'medico.especialidad',
            'paciente',
            'medicamentos',
            'cita'
        ]);

        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.receta', compact('receta', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'receta_' . $receta->paciente->ci . '_' . $receta->fecha_emision->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Marca una receta como dispensada
     */
    public function marcarDispensada(Receta $receta): \Illuminate\Http\RedirectResponse
    {
        if ($receta->estado === 'Dispensada') {
            return back()->with('info', 'La receta ya está dispensada');
        }

        if ($receta->estado === 'Cancelada') {
            return back()->withErrors([
                'error' => 'No se puede dispensar una receta cancelada'
            ]);
        }

        $receta->marcarComoDispensada();

        return redirect()->route('admin.recetas.show', $receta)
            ->with('success', 'Receta marcada como dispensada y stock actualizado');
    }

    /**
     * Cancela una receta
     */
    public function cancelar(Receta $receta): \Illuminate\Http\RedirectResponse
    {
        if ($receta->estado === 'Dispensada') {
            return back()->withErrors([
                'error' => 'No se puede cancelar una receta ya dispensada'
            ]);
        }

        $receta->cancelar();

        return redirect()->route('admin.recetas.index')
            ->with('success', 'Receta cancelada exitosamente');
    }
}