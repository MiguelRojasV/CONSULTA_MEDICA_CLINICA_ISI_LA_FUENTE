<?php 
namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * MedicoCitaController
 * Permite al médico gestionar su agenda de citas
 * - Ver agenda filtrada por fecha
 * - Atender pacientes
 * - Registrar diagnósticos y tratamientos
 */
class MedicoCitaController extends Controller
{
    /**
     * Muestra la agenda del médico
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;

        // Obtener fecha del filtro o usar hoy por defecto
        $fecha = $request->input('fecha', now()->format('Y-m-d'));

        // Obtener citas del médico para la fecha seleccionada
        $citas = $medico->citas()
            ->with('paciente')
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('medico.citas.index', compact('citas', 'fecha', 'medico'));
    }

    /**
     * Muestra los detalles de una cita
     * @param Cita $cita
     * @return View
     */
    public function show(Cita $cita): View
    {
        $medico = Auth::user()->medico;

        // Verificar que la cita pertenezca al médico
        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para ver esta cita');
        }

        $cita->load(['paciente', 'recetas.medicamentos']);

        return view('medico.citas.show', compact('cita'));
    }

    /**
     * Muestra el formulario para editar/atender una cita
     * @param Cita $cita
     * @return View
     */
    public function edit(Cita $cita): View
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta cita');
        }

        // Cargar información del paciente con su historial
        $paciente = $cita->paciente;
        $historial = $paciente->historialMedico()
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        return view('medico.citas.edit', compact('cita', 'paciente', 'historial'));
    }

    /**
     * Actualiza la cita con diagnóstico y tratamiento
     * @param Request $request
     * @param Cita $cita
     * @return RedirectResponse
     */
    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para editar esta cita');
        }

        // Validar datos
        $validated = $request->validate([
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:En Consulta,Atendida'
        ], [
            'diagnostico.required' => 'El diagnóstico es obligatorio',
            'tratamiento.required' => 'El tratamiento es obligatorio',
            'estado.required' => 'Debe seleccionar el estado de la cita'
        ]);

        // Actualizar la cita
        $cita->diagnostico = $validated['diagnostico'];
        $cita->tratamiento = $validated['tratamiento'];
        $cita->observaciones = $validated['observaciones'] ?? null;
        $cita->estado = $validated['estado'];
        $cita->save();

        // Si se marca como atendida, crear entrada en historial médico
        if ($validated['estado'] === 'Atendida') {
            $cita->marcarComoAtendida();
        }

        return redirect()->route('medico.citas.show', $cita)
            ->with('success', 'Cita actualizada exitosamente');
    }

    /**
     * Marca una cita como atendida rápidamente
     * @param Cita $cita
     * @return RedirectResponse
     */
    public function marcarAtendida(Cita $cita): RedirectResponse
    {
        $medico = Auth::user()->medico;

        // Verificar permisos
        if ($cita->medico_id !== $medico->id) {
            abort(403, 'No tiene permisos para modificar esta cita');
        }

        // Verificar que tenga diagnóstico
        if (!$cita->diagnostico) {
            return back()->withErrors([
                'error' => 'Debe registrar el diagnóstico antes de marcar como atendida'
            ]);
        }

        // Marcar como atendida
        $cita->marcarComoAtendida();

        return redirect()->route('medico.citas.index')
            ->with('success', 'Cita marcada como atendida');
    }
}
