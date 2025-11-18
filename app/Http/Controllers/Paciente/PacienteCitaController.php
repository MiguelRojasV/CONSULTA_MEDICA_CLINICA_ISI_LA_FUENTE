<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\HorarioAtencion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * PacienteCitaController
 * Ubicación: app/Http/Controllers/Paciente/PacienteCitaController.php
 * 
 * Permite al paciente gestionar sus citas médicas
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Usa medico->especialidad
 * - Campos actualizados: tipo_cita, duracion_estimada, costo
 * - Método marcarComoAtendida() actualizado
 */
class PacienteCitaController extends Controller
{
    /**
     * Lista todas las citas del paciente
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $paciente = Auth::user()->paciente;

        // Query builder para citas
        $query = $paciente->citas()->with('medico.especialidad');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro por fecha
        if ($request->filled('mes')) {
            $mes = $request->input('mes');
            $query->whereMonth('fecha', Carbon::parse($mes)->month)
                  ->whereYear('fecha', Carbon::parse($mes)->year);
        }

        // Obtener citas ordenadas por fecha
        $citas = $query->orderBy('fecha', 'desc')
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
        // Obtener especialidades activas
        $especialidades = Especialidad::activas()
            ->orderBy('nombre')
            ->get();

        // Obtener médicos activos agrupados por especialidad
        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('especialidad_id');

        return view('paciente.citas.create', compact('especialidades', 'medicos'));
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
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|string|max:500',
            'tipo_cita' => 'nullable|in:Primera Vez,Control,Emergencia',
        ], [
            'medico_id.required' => 'Debe seleccionar un médico',
            'medico_id.exists' => 'El médico seleccionado no existe',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora.required' => 'La hora es obligatoria',
            'hora.date_format' => 'El formato de hora no es válido',
            'motivo.required' => 'Debe indicar el motivo de la consulta',
            'motivo.max' => 'El motivo no puede exceder 500 caracteres',
        ]);

        // Verificar que el médico esté activo
        $medico = Medico::find($validated['medico_id']);
        if ($medico->estado !== 'Activo') {
            return back()->withInput()->withErrors([
                'medico_id' => 'El médico seleccionado no está disponible actualmente.',
            ]);
        }

        // Crear la cita
        $cita = new Cita([
            'paciente_id' => $paciente->id,
            'medico_id' => $validated['medico_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['fecha'] . ' ' . $validated['hora'], // Combinar fecha y hora
            'motivo' => $validated['motivo'],
            'estado' => 'Programada',
            'tipo_cita' => $validated['tipo_cita'] ?? 'Primera Vez',
            'duracion_estimada' => 30, // 30 minutos por defecto
        ]);

        // Validar que no haya conflicto de horario
        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora. Por favor, seleccione otro horario.',
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

        // Cargar relaciones
        $cita->load(['medico.especialidad', 'recetas.medicamentos']);

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
                'error' => 'No se puede cancelar una cita ya atendida',
            ]);
        }

        // Verificar que la cita sea futura (al menos 24 horas antes)
        $fechaHoraCita = Carbon::parse($cita->fecha->format('Y-m-d') . ' ' . $cita->hora->format('H:i:s'));
        
        if ($fechaHoraCita->isPast()) {
            return back()->withErrors([
                'error' => 'No se puede cancelar una cita que ya pasó',
            ]);
        }

        if ($fechaHoraCita->diffInHours(now()) < 24) {
            return back()->withErrors([
                'error' => 'Solo puede cancelar citas con al menos 24 horas de anticipación',
            ]);
        }

        // Cancelar la cita
        $cita->cancelar();

        return redirect()->route('paciente.citas.index')
            ->with('success', 'Cita cancelada exitosamente');
    }

    /**
     * Obtiene horarios disponibles para un médico en una fecha (AJAX)
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

        // Mapeo de días en español
        $diasMap = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];

        // Obtener el día de la semana
        $fechaCarbon = Carbon::parse($fecha);
        $diaIngles = $fechaCarbon->format('l');
        $diaSemana = $diasMap[$diaIngles] ?? 'Lunes';

        // Obtener horario del médico para ese día
        $horario = HorarioAtencion::where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->first();

        if (!$horario) {
            return response()->json([
                'disponible' => false,
                'mensaje' => 'El médico no atiende este día',
            ]);
        }

        // Generar slots de 30 minutos
        $slots = $horario->generarSlots(30);

        // Filtrar slots ya ocupados
        $citasOcupadas = Cita::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->whereNotIn('estado', ['Cancelada'])
            ->get()
            ->map(function($cita) {
                return Carbon::parse($cita->hora)->format('H:i');
            })
            ->toArray();

        $slotsDisponibles = array_diff($slots, $citasOcupadas);

        return response()->json([
            'disponible' => true,
            'horarios' => array_values($slotsDisponibles),
            'horario_atencion' => $horario->horarioFormateado(),
        ]);
    }
}