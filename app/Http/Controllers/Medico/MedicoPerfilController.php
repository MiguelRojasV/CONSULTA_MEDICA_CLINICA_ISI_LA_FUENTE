<?php
namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * MedicoPerfilController
 * Permite al médico gestionar su perfil personal
 * - Ver información del perfil
 * - Editar datos personales
 * - Cambiar contraseña
 * - Actualizar especialidad y datos profesionales
 */
class MedicoPerfilController extends Controller
{
    /**
     * Muestra el perfil del médico autenticado
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Cargar especialidad
        $medico->load('especialidad');

        // Estadísticas del médico
        $totalPacientes = $medico->citas()
            ->distinct('paciente_id')
            ->count('paciente_id');

        $totalCitas = $medico->citas()->count();
        
        $citasAtendidas = $medico->citas()
            ->where('estado', 'Atendida')
            ->count();

        $recetasEmitidas = $medico->recetas()->count();

        return view('medico.perfil.index', compact(
            'medico',
            'user',
            'totalPacientes',
            'totalCitas',
            'citasAtendidas',
            'recetasEmitidas'
        ));
    }

    /**
     * Muestra el formulario para editar el perfil
     * @return View
     */
    public function edit(): View
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Cargar especialidad
        $medico->load('especialidad');

        return view('medico.perfil.edit', compact('medico', 'user', 'especialidades'));
    }

    /**
     * Actualiza la información del perfil del médico
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Validar datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
                'unique:users,ci,' . $user->id
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email,' . $user->id
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
                'max:15'
            ],
            'direccion' => 'nullable|string|max:200',
            'fecha_nacimiento' => 'required|date|before:today',
            
            // Datos profesionales del médico
            'especialidad_id' => 'required|exists:especialidades,id',
            'matricula' => [
                'required',
                'string',
                'max:50',
                'unique:medicos,matricula,' . $medico->id
            ],
            'años_experiencia' => 'nullable|integer|min:0|max:70',
            'consultorio' => 'nullable|string|max:100',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'ci.required' => 'El CI es obligatorio',
            'ci.regex' => 'El CI debe tener entre 7 y 8 dígitos',
            'ci.unique' => 'Este CI ya está registrado',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo válido',
            'email.unique' => 'Este correo ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener entre 7 y 8 dígitos',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'especialidad_id.required' => 'Debe seleccionar una especialidad',
            'especialidad_id.exists' => 'La especialidad seleccionada no existe',
            'matricula.required' => 'La matrícula profesional es obligatoria',
            'matricula.unique' => 'Esta matrícula ya está registrada',
            'años_experiencia.min' => 'Los años de experiencia no pueden ser negativos',
        ]);

        // Actualizar datos del usuario
        $user->nombre = $validated['nombre'];
        $user->apellido = $validated['apellido'];
        $user->ci = $validated['ci'];
        $user->email = $validated['email'];
        $user->telefono = $validated['telefono'];
        $user->direccion = $validated['direccion'] ?? null;
        $user->fecha_nacimiento = $validated['fecha_nacimiento'];
        $user->save();

        // Actualizar datos del médico
        $medico->especialidad_id = $validated['especialidad_id'];
        $medico->matricula = $validated['matricula'];
        $medico->años_experiencia = $validated['años_experiencia'] ?? null;
        $medico->consultorio = $validated['consultorio'] ?? null;
        $medico->save();

        return redirect()->route('medico.perfil.index')
            ->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Muestra el formulario para cambiar contraseña
     * @return View
     */
    public function editPassword(): View
    {
        return view('medico.perfil.change-password');
    }

    /**
     * Actualiza la contraseña del médico
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Validar datos
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
        ], [
            'current_password.required' => 'Debe ingresar su contraseña actual',
            'password.required' => 'La nueva contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta'
            ])->withInput();
        }

        // Actualizar contraseña
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('medico.perfil.index')
            ->with('success', 'Contraseña actualizada exitosamente');
    }

    /**
     * Muestra la vista de horarios de atención del médico
     * @return View
     */
    public function horarios(): View
    {
        $medico = Auth::user()->medico;

        // Obtener horarios configurados (si existen en tu sistema)
        // Esta funcionalidad depende de si tienes una tabla de horarios
        // Por ahora retornamos vista básica
        
        return view('medico.perfil.horarios', compact('medico'));
    }
}