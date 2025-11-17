<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * AdminPacienteController
 * CRUD completo de pacientes
 * Solo accesible para administradores
 */
class AdminPacienteController extends Controller
{
    /**
     * Lista todos los pacientes
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Paciente::query();

        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        // Filtro por género
        if ($request->filled('genero')) {
            $query->where('genero', $request->input('genero'));
        }

        $pacientes = $query->orderBy('nombre')->paginate(15);

        return view('admin.pacientes.index', compact('pacientes'));
    }

    /**
     * Muestra el formulario para crear un nuevo paciente
     * @return View
     */
    public function create(): View
    {
        return view('admin.pacientes.create');
    }

    /**
     * Guarda un nuevo paciente
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar datos
        $validated = $request->validate([
            // Datos de usuario
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            
            // Datos de paciente
            'ci' => [
                'required',
                'string',
                'min:7',
                'max:8',
                'regex:/^[0-9]+$/',
                'unique:pacientes'
            ],
            'edad' => 'required|integer|min:0|max:150',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'antecedentes' => 'nullable|string',
            'alergias' => 'nullable|string',
            'contacto_emergencia' => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:5'
        ], [
            'ci.regex' => 'El CI solo debe contener números',
            'ci.min' => 'El CI debe tener al menos 7 dígitos',
            'ci.max' => 'El CI no puede tener más de 8 dígitos',
            'edad.min' => 'La edad no puede ser negativa'
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'paciente'
            ]);

            // Crear paciente
            Paciente::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['name'],
                'edad' => $validated['edad'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'genero' => $validated['genero'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'antecedentes' => $validated['antecedentes'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null
            ]);

            DB::commit();

            return redirect()->route('admin.pacientes.index')
                ->with('success', 'Paciente creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el paciente']);
        }
    }

    /**
     * Muestra los detalles de un paciente
     * @param Paciente $paciente
     * @return View
     */
    public function show(Paciente $paciente): View
    {
        $paciente->load(['citas.medico', 'recetas.medicamentos', 'historialMedico.medico']);

        // Estadísticas
        $totalCitas = $paciente->citas()->count();
        $citasAtendidas = $paciente->citas()->where('estado', 'Atendida')->count();
        $totalRecetas = $paciente->recetas()->count();

        return view('admin.pacientes.show', compact(
            'paciente',
            'totalCitas',
            'citasAtendidas',
            'totalRecetas'
        ));
    }

    /**
     * Muestra el formulario para editar un paciente
     * @param Paciente $paciente
     * @return View
     */
    public function edit(Paciente $paciente): View
    {
        $user = $paciente->user;
        return view('admin.pacientes.edit', compact('paciente', 'user'));
    }

    /**
     * Actualiza un paciente
     * @param Request $request
     * @param Paciente $paciente
     * @return RedirectResponse
     */
    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $user = $paciente->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'ci' => [
                'required',
                'string',
                'min:7',
                'max:8',
                'regex:/^[0-9]+$/',
                'unique:pacientes,ci,' . $paciente->id
            ],
            'edad' => 'required|integer|min:0|max:150',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'antecedentes' => 'nullable|string',
            'alergias' => 'nullable|string',
            'contacto_emergencia' => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:5'
        ]);

        // Actualizar usuario
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Actualizar paciente
        $paciente->update([
            'ci' => $validated['ci'],
            'nombre' => $validated['name'],
            'edad' => $validated['edad'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'genero' => $validated['genero'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'antecedentes' => $validated['antecedentes'] ?? null,
            'alergias' => $validated['alergias'] ?? null,
            'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
            'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null
        ]);

        return redirect()->route('admin.pacientes.show', $paciente)
            ->with('success', 'Paciente actualizado exitosamente');
    }

    /**
     * Elimina un paciente
     * @param Paciente $paciente
     * @return RedirectResponse
     */
    public function destroy(Paciente $paciente): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $user = $paciente->user;
            
            // Eliminar paciente (cascade eliminará citas, recetas, etc.)
            $paciente->delete();
            
            // Eliminar usuario
            $user->delete();

            DB::commit();

            return redirect()->route('admin.pacientes.index')
                ->with('success', 'Paciente eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el paciente']);
        }
    }

    /**
     * Descarga el historial médico del paciente en PDF
     * @param Paciente $paciente
     * @return \Illuminate\Http\Response
     */
    public function descargarHistorialPDF(Paciente $paciente)
    {
        // Obtener historial completo
        $historial = $paciente->historialMedico()
            ->with(['medico', 'cita'])
            ->orderBy('fecha', 'desc')
            ->get();

        // Información de la clínica
        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        // Generar PDF
        $pdf = Pdf::loadView('pdf.historial-medico', compact('paciente', 'historial', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'historial_medico_' . $paciente->ci . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
