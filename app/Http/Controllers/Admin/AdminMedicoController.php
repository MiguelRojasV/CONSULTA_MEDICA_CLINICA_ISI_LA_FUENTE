<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * AdminMedicoController
 * CRUD completo de médicos
 */
class AdminMedicoController extends Controller
{
    /**
     * Lista todos los médicos
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Medico::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%")
                  ->orWhere('especialidad', 'like', "%{$buscar}%");
            });
        }

        // Filtro por especialidad
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->input('especialidad'));
        }

        $medicos = $query->orderBy('nombre')->paginate(15);

        // Obtener especialidades únicas para el filtro
        $especialidades = Medico::distinct()->pluck('especialidad');

        return view('admin.medicos.index', compact('medicos', 'especialidades'));
    }

    /**
     * Muestra el formulario para crear un nuevo médico
     * @return View
     */
    public function create(): View
    {
        return view('admin.medicos.create');
    }

    /**
     * Guarda un nuevo médico
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'ci' => [
                'required',
                'string',
                'min:7',
                'max:8',
                'regex:/^[0-9]+$/',
                'unique:medicos'
            ],
            'especialidad' => 'required|string|max:100',
            'registro_profesional' => 'nullable|string|max:50',
            'turno' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'formacion_continua' => 'nullable|string'
        ], [
            'ci.regex' => 'El CI solo debe contener números',
            'ci.min' => 'El CI debe tener al menos 7 dígitos',
            'ci.max' => 'El CI no puede tener más de 8 dígitos'
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario con rol médico
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'medico'
            ]);

            // Crear médico
            Medico::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['name'],
                'especialidad' => $validated['especialidad'],
                'registro_profesional' => $validated['registro_profesional'] ?? null,
                'turno' => $validated['turno'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'formacion_continua' => $validated['formacion_continua'] ?? null
            ]);

            DB::commit();

            return redirect()->route('admin.medicos.index')
                ->with('success', 'Médico creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el médico']);
        }
    }

    /**
     * Muestra los detalles de un médico
     * @param Medico $medico
     * @return View
     */
    public function show(Medico $medico): View
    {
        $medico->load(['citas.paciente', 'recetas']);

        // Estadísticas
        $totalCitas = $medico->citas()->count();
        $citasAtendidas = $medico->citas()->where('estado', 'Atendida')->count();
        $totalRecetas = $medico->recetas()->count();
        $totalPacientes = $medico->citas()->distinct('paciente_id')->count('paciente_id');

        return view('admin.medicos.show', compact(
            'medico',
            'totalCitas',
            'citasAtendidas',
            'totalRecetas',
            'totalPacientes'
        ));
    }

    /**
     * Muestra el formulario para editar un médico
     * @param Medico $medico
     * @return View
     */
    public function edit(Medico $medico): View
    {
        $user = $medico->user;
        return view('admin.medicos.edit', compact('medico', 'user'));
    }

    /**
     * Actualiza un médico
     * @param Request $request
     * @param Medico $medico
     * @return RedirectResponse
     */
    public function update(Request $request, Medico $medico): RedirectResponse
    {
        $user = $medico->user;

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
                'unique:medicos,ci,' . $medico->id
            ],
            'especialidad' => 'required|string|max:100',
            'registro_profesional' => 'nullable|string|max:50',
            'turno' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'formacion_continua' => 'nullable|string'
        ]);

        // Actualizar usuario
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Actualizar médico
        $medico->update([
            'ci' => $validated['ci'],
            'nombre' => $validated['name'],
            'especialidad' => $validated['especialidad'],
            'registro_profesional' => $validated['registro_profesional'] ?? null,
            'turno' => $validated['turno'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'formacion_continua' => $validated['formacion_continua'] ?? null
        ]);

        return redirect()->route('admin.medicos.show', $medico)
            ->with('success', 'Médico actualizado exitosamente');
    }

    /**
     * Elimina un médico
     * @param Medico $medico
     * @return RedirectResponse
     */
    public function destroy(Medico $medico): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $user = $medico->user;
            
            // Verificar si tiene citas pendientes
            $citasPendientes = $medico->citas()
                ->whereIn('estado', ['Programada', 'Confirmada'])
                ->where('fecha', '>=', now())
                ->count();

            if ($citasPendientes > 0) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el médico porque tiene citas pendientes'
                ]);
            }

            $medico->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.medicos.index')
                ->with('success', 'Médico eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el médico']);
        }
    }
}