<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * AdminCitaController
 * CRUD completo de citas
 */
class AdminCitaController extends Controller
{
    /**
     * Lista todas las citas
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Cita::with(['paciente', 'medico']);

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->input('fecha'));
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
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        $citas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20);

        // Para los filtros
        $medicos = Medico::select('id', 'nombre')->orderBy('nombre')->get();

        return view('admin.citas.index', compact('citas', 'medicos'));
    }

    /**
     * Muestra el formulario para crear una nueva cita
     * @return View
     */
    public function create(): View
    {
        $pacientes = Paciente::select('id', 'nombre', 'ci')->orderBy('nombre')->get();
        $medicos = Medico::select('id', 'nombre', 'especialidad')->orderBy('nombre')->get();

        return view('admin.citas.create', compact('pacientes', 'medicos'));
    }

    /**
     * Guarda una nueva cita
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'motivo' => 'nullable|string',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita = new Cita($validated);

        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora'
            ]);
        }

        $cita->save();

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita creada exitosamente');
    }

    /**
     * Muestra los detalles de una cita
     * @param Cita $cita
     * @return View
     */
    public function show(Cita $cita): View
    {
        $cita->load(['paciente', 'medico', 'recetas.medicamentos']);
        return view('admin.citas.show', compact('cita'));
    }

    /**
     * Muestra el formulario para editar una cita
     * @param Cita $cita
     * @return View
     */
    public function edit(Cita $cita): View
    {
        $pacientes = Paciente::select('id', 'nombre', 'ci')->orderBy('nombre')->get();
        $medicos = Medico::select('id', 'nombre', 'especialidad')->orderBy('nombre')->get();

        return view('admin.citas.edit', compact('cita', 'pacientes', 'medicos'));
    }

    /**
     * Actualiza una cita
     * @param Request $request
     * @param Cita $cita
     * @return RedirectResponse
     */
    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'motivo' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:Programada,Confirmada,En Consulta,Atendida,Cancelada'
        ]);

        $cita->fill($validated);

        if (!$cita->validarCita()) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya existe una cita para este médico en esa fecha y hora'
            ]);
        }

        $cita->save();

        return redirect()->route('admin.citas.show', $cita)
            ->with('success', 'Cita actualizada exitosamente');
    }

    /**
     * Elimina una cita
     * @param Cita $cita
     * @return RedirectResponse
     */
    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }
}