<?php 
namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * MedicoPacienteController
 * Permite al médico consultar información de sus pacientes
 * - Ver lista de pacientes atendidos
 * - Ver perfil del paciente
 * - Ver historial médico del paciente
 */
class MedicoPacienteController extends Controller
{
    /**
     * Lista los pacientes que ha atendido el médico
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $medico = Auth::user()->medico;

        // Obtener IDs de pacientes únicos que ha atendido
        $pacientesIds = $medico->citas()
            ->distinct()
            ->pluck('paciente_id');

        // Query builder para pacientes
        $query = Paciente::whereIn('id', $pacientesIds);

        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        // Obtener pacientes paginados
        $pacientes = $query->orderBy('nombre')->paginate(15);

        return view('medico.pacientes.index', compact('pacientes'));
    }

    /**
     * Muestra el perfil completo de un paciente
     * @param Paciente $paciente
     * @return View
     */
    public function show(Paciente $paciente): View
    {
        $medico = Auth::user()->medico;

        // Verificar que el médico haya atendido a este paciente
        $haAtendido = $medico->citas()
            ->where('paciente_id', $paciente->id)
            ->exists();

        if (!$haAtendido) {
            abort(403, 'No tiene permisos para ver este paciente');
        }

        // Obtener citas con este médico
        $citasConMedico = $paciente->citas()
            ->where('medico_id', $medico->id)
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        // Obtener última receta emitida
        $ultimaReceta = $paciente->recetas()
            ->where('medico_id', $medico->id)
            ->orderBy('fecha_emision', 'desc')
            ->first();

        return view('medico.pacientes.show', compact(
            'paciente',
            'citasConMedico',
            'ultimaReceta'
        ));
    }

    /**
     * Muestra el historial médico completo del paciente
     * @param Paciente $paciente
     * @return View
     */
    public function historial(Paciente $paciente): View
    {
        $medico = Auth::user()->medico;

        // Verificar que el médico haya atendido a este paciente
        $haAtendido = $medico->citas()
            ->where('paciente_id', $paciente->id)
            ->exists();

        if (!$haAtendido) {
            abort(403, 'No tiene permisos para ver el historial de este paciente');
        }

        // Obtener historial médico completo
        $historial = $paciente->historialMedico()
            ->with(['medico', 'cita'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('medico.pacientes.historial', compact('paciente', 'historial'));
    }
}
