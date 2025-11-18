<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

/**
 * AdminMedicoController
 * Ubicación: app/Http/Controllers/Admin/AdminMedicoController.php
 * 
 * CRUD completo de médicos
 * ACTUALIZADO: Usa relación con especialidades (3FN)
 */
class AdminMedicoController extends Controller
{
    /**
     * Lista todos los médicos
     */
    public function index(Request $request): View
    {
        $query = Medico::with('especialidad');

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%")
                  ->orWhere('matricula', 'like', "%{$buscar}%");
            });
        }

        // Filtro por especialidad
        if ($request->filled('especialidad_id')) {
            $query->where('especialidad_id', $request->input('especialidad_id'));
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro por turno
        if ($request->filled('turno')) {
            $query->where('turno', $request->input('turno'));
        }

        $medicos = $query->orderBy('nombre')->paginate(15);

        // Para los filtros
        $especialidades = Especialidad::activas()->orderBy('nombre')->get();

        return view('admin.medicos.index', compact('medicos', 'especialidades'));
    }

    /**
     * Muestra el formulario para crear un nuevo médico
     */
    public function create(): View
    {
        $especialidades = Especialidad::activas()->orderBy('nombre')->get();
        return view('admin.medicos.create', compact('especialidades'));
    }

    /**
     * Guarda un nuevo médico
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Datos de usuario
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Datos personales
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
                'unique:medicos,ci'
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
            ],
            
            // Datos profesionales
            'especialidad_id' => 'required|exists:especialidades,id',
            'matricula' => [
                'required',
                'string',
                'max:50',
                'unique:medicos,matricula'
            ],
            'registro_profesional' => 'nullable|string|max:50',
            'años_experiencia' => 'nullable|integer|min:0|max:70',
            'turno' => 'nullable|in:Mañana,Tarde,Noche,Rotativo',
            'consultorio' => 'nullable|string|max:100',
            'formacion_continua' => 'nullable|string|max:1000',
            'fecha_contratacion' => 'nullable|date|before_or_equal:today',
        ], [
            'ci.regex' => 'El CI debe tener entre 7 y 8 dígitos',
            'ci.unique' => 'Este CI ya está registrado',
            'telefono.regex' => 'El teléfono debe tener entre 7 y 8 dígitos',
            'matricula.unique' => 'Esta matrícula ya está registrada',
            'años_experiencia.min' => 'Los años de experiencia no pueden ser negativos',
            'años_experiencia.max' => 'Los años de experiencia no pueden exceder 70',
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario con rol médico
            $user = User::create([
                'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'medico'
            ]);

            // Crear médico
            Medico::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'especialidad_id' => $validated['especialidad_id'],
                'matricula' => $validated['matricula'],
                'registro_profesional' => $validated['registro_profesional'] ?? null,
                'años_experiencia' => $validated['años_experiencia'] ?? 0,
                'turno' => $validated['turno'] ?? null,
                'consultorio' => $validated['consultorio'] ?? null,
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'formacion_continua' => $validated['formacion_continua'] ?? null,
                'fecha_contratacion' => $validated['fecha_contratacion'] ?? now(),
                'estado' => 'Activo',
            ]);

            DB::commit();

            return redirect()->route('admin.medicos.index')
                ->with('success', 'Médico creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear médico: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Error al crear el médico']);
        }
    }

    /**
     * Muestra los detalles de un médico
     */
    public function show(Medico $medico): View
    {
        $medico->load(['especialidad', 'citas.paciente', 'recetas']);

        // Estadísticas
        $totalCitas = $medico->citas()->count();
        $citasAtendidas = $medico->citas()->where('estado', 'Atendida')->count();
        $totalRecetas = $medico->recetas()->count();
        $totalPacientes = $medico->contarPacientesAtendidos();

        // Próximas citas
        $proximasCitas = $medico->citas()
            ->with('paciente')
            ->where('fecha', '>=', today())
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->take(5)
            ->get();

        return view('admin.medicos.show', compact(
            'medico',
            'totalCitas',
            'citasAtendidas',
            'totalRecetas',
            'totalPacientes',
            'proximasCitas'
        ));
    }

    /**
     * Muestra el formulario para editar un médico
     */
    public function edit(Medico $medico): View
    {
        $user = $medico->user;
        $especialidades = Especialidad::activas()->orderBy('nombre')->get();
        
        return view('admin.medicos.edit', compact('medico', 'user', 'especialidades'));
    }

    /**
     * Actualiza un médico
     */
    public function update(Request $request, Medico $medico): RedirectResponse
    {
        $user = $medico->user;

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
                'unique:medicos,ci,' . $medico->id
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
            ],
            'especialidad_id' => 'required|exists:especialidades,id',
            'matricula' => [
                'required',
                'string',
                'max:50',
                'unique:medicos,matricula,' . $medico->id
            ],
            'registro_profesional' => 'nullable|string|max:50',
            'años_experiencia' => 'nullable|integer|min:0|max:70',
            'turno' => 'nullable|in:Mañana,Tarde,Noche,Rotativo',
            'consultorio' => 'nullable|string|max:100',
            'formacion_continua' => 'nullable|string|max:1000',
            'fecha_contratacion' => 'nullable|date|before_or_equal:today',
            'estado' => 'required|in:Activo,Inactivo,Licencia',
        ]);

        // Actualizar usuario
        $user->update([
            'name' => $validated['nombre'] . ' ' . $validated['apellido'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        // Actualizar médico
        $medico->update([
            'ci' => $validated['ci'],
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'especialidad_id' => $validated['especialidad_id'],
            'matricula' => $validated['matricula'],
            'registro_profesional' => $validated['registro_profesional'] ?? null,
            'años_experiencia' => $validated['años_experiencia'] ?? 0,
            'turno' => $validated['turno'] ?? null,
            'consultorio' => $validated['consultorio'] ?? null,
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'formacion_continua' => $validated['formacion_continua'] ?? null,
            'fecha_contratacion' => $validated['fecha_contratacion'],
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('admin.medicos.show', $medico)
            ->with('success', 'Médico actualizado exitosamente');
    }

    /**
     * Elimina un médico
     */
    public function destroy(Medico $medico): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Verificar si tiene citas pendientes
            $citasPendientes = $medico->citas()
                ->whereIn('estado', ['Programada', 'Confirmada'])
                ->where('fecha', '>=', today())
                ->count();

            if ($citasPendientes > 0) {
                return back()->withErrors([
                    'error' => "No se puede eliminar el médico porque tiene {$citasPendientes} citas pendientes"
                ]);
            }

            $user = $medico->user;
            
            $medico->delete();
            
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.medicos.index')
                ->with('success', 'Médico eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar médico: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al eliminar el médico']);
        }
    }
}