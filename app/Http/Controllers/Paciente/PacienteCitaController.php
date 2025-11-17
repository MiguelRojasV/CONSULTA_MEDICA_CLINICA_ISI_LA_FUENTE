<?php 
namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\HorarioAtencion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * PacienteCitaController
 * Permite al paciente gestionar sus citas médicas
 * - Ver sus citas
 * - Agendar nuevas citas
 * - Cancelar citas
 */
class PacienteCitaController extends Controller
{
    /**
     * Lista todas las citas del paciente
     * @return View
     */
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        // Obtener citas ordenadas por fecha
        $citas = $paciente->citas()
            ->with('medico')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(10);

        return view('paciente.citas.index', compact('citas'));
    }

    /**
     * Muestra el formulario para agendar una nueva cita
     * @return View
     */
    public function create(): View
    {
        // Obtener lista de médicos disponibles
        $medicos = Medico::select('id', 'nombre', 'especialidad')
            ->orderBy('nombre')
            ->get();

        return view('paciente.citas.create', compact('medicos'));
    }

    /**
     * Guarda una nueva cita
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $paciente = Auth::user()->paciente;

        // Validar datos
        $validated = $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'motivo' => 'required|string|max:500'
        ], [
            'medico_id.required' => 'Debe seleccionar un médico',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora.required' => 'La hora es obligatoria',
            'motivo.required' => 'Debe indicar el motivo de la consulta',
            'motivo.max' => 'El motivo no puede exceder 500 caracteres'
        ]);

        // Crear la cita
        $cita = new Cita([
            'paciente_id' => $paciente->id,
            'medico_id' => $validated['medico_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'motivo' => $validated['motivo'],
            'estado' => 'Programada'
        ]);

        // Validar que no haya conflicto de horario
        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora. Por favor, seleccione otro horario.'
            ]);
        }

        // Guardar la cita
        $cita->save();

        return redirect()->route('paciente.citas.index')
            ->with('success', '¡Cita agendada exitosamente! Recibirá una confirmación próximamente.');
    }

    /**
     * Muestra los detalles de una cita
     * @param Cita $cita
     * @return View
     */
    public function show(Cita $cita): View
    {
        $paciente = Auth::user()->paciente;

        // Verificar que la cita pertenezca al paciente autenticado
        if ($cita->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para ver esta cita');
        }

        $cita->load('medico', 'recetas.medicamentos');

        return view('paciente.citas.show', compact('cita'));
    }

    /**
     * Cancela una cita
     * @param Cita $cita
     * @return RedirectResponse
     */
    public function cancelar(Cita $cita): RedirectResponse
    {
        $paciente = Auth::user()->paciente;

        // Verificar que la cita pertenezca al paciente
        if ($cita->paciente_id !== $paciente->id) {
            abort(403, 'No tiene permisos para cancelar esta cita');
        }

        // Verificar que la cita no esté atendida
        if ($cita->estado === 'Atendida') {
            return back()->withErrors([
                'error' => 'No se puede cancelar una cita ya atendida'
            ]);
        }

        // Verificar que la cita sea futura (al menos 24 horas antes)
        $fechaCita = Carbon::parse($cita->fecha . ' ' . $cita->hora->format('H:i'));
        if ($fechaCita->isPast() || $fechaCita->diffInHours(now()) < 24) {
            return back()->withErrors([
                'error' => 'Solo puede cancelar citas con al menos 24 horas de anticipación'
            ]);
        }

        // Cancelar la cita
        $cita->estado = 'Cancelada';
        $cita->save();

        return redirect()->route('paciente.citas.index')
            ->with('success', 'Cita cancelada exitosamente');
    }

    /**
     * Obtiene horarios disponibles para un médico en una fecha
     * API endpoint para llamadas AJAX
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function horariosDisponibles(Request $request)
    {
        $medicoId = $request->input('medico_id');
        $fecha = $request->input('fecha');

        // Validar
        if (!$medicoId || !$fecha) {
            return response()->json(['error' => 'Datos incompletos'], 400);
        }

        $medico = Medico::find($medicoId);
        if (!$medico) {
            return response()->json(['error' => 'Médico no encontrado'], 404);
        }

        // Obtener el día de la semana
        $diaSemana = Carbon::parse($fecha)->locale('es')->dayName;
        $diaSemana = ucfirst($diaSemana); // Capitalizar primera letra

        // Obtener horario del médico para ese día
        $horario = HorarioAtencion::where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->first();

        if (!$horario) {
            return response()->json([
                'disponible' => false,
                'mensaje' => 'El médico no atiende este día'
            ]);
        }

        // Generar slots de 30 minutos
        $slots = $horario->generarSlots(30);

        // Filtrar slots ya ocupados
        $citasOcupadas = Cita::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->whereNotIn('estado', ['Cancelada'])
            ->pluck('hora')
            ->map(function($hora) {
                return Carbon::parse($hora)->format('H:i');
            })
            ->toArray();

        $slotsDisponibles = array_diff($slots, $citasOcupadas);

        return response()->json([
            'disponible' => true,
            'horarios' => array_values($slotsDisponibles)
        ]);
    }
}