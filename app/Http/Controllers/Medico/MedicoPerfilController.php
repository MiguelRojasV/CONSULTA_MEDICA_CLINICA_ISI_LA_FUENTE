<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

/**
 * MedicoPerfilController
 * Ubicación: app/Http/Controllers/Medico/MedicoPerfilController.php
 * 
 * Permite al médico gestionar su perfil personal y profesional
 * 
 * ACTUALIZADO COMPLETO: Compatible con nueva estructura 3FN
 * - Campos nuevos: apellido, email, años_experiencia, fecha_contratacion, estado
 * - Relación con especialidades
 * - Validaciones mejoradas
 * - Correcciones para compatibilidad con Laravel 10+ y cast 'hashed'
 */
class MedicoPerfilController extends Controller
{
    /**
     * Muestra el perfil del médico
     */
    public function index(): View
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Cargar especialidad
        $medico->load('especialidad');

        // Estadísticas del médico
        $totalPacientes = $medico->contarPacientesAtendidos();
        $totalCitas = $medico->citas()->count();
        $citasAtendidas = $medico->citas()->where('estado', 'Atendida')->count();
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
     */
    public function edit(): View
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Cargar especialidad
        $medico->load('especialidad');

        // Obtener especialidades disponibles
        $especialidades = Especialidad::activas()->orderBy('nombre')->get();

        return view('medico.perfil.edit', compact('medico', 'user', 'especialidades'));
    }

    /**
     * Actualiza la información del perfil del médico
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Validar datos
        $validated = $request->validate([
            // Datos personales
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/',
                'unique:medicos,ci,' . $medico->id
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
            
            // Datos profesionales
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
            'especialidad_id.required' => 'Debe seleccionar una especialidad',
            'especialidad_id.exists' => 'La especialidad seleccionada no existe',
            'matricula.required' => 'La matrícula profesional es obligatoria',
            'matricula.unique' => 'Esta matrícula ya está registrada',
            'años_experiencia.min' => 'Los años de experiencia no pueden ser negativos',
            'años_experiencia.max' => 'Los años de experiencia no pueden exceder 70',
        ]);

        // Usar transacción para asegurar integridad de datos
        DB::beginTransaction();
        
        try {
            // Actualizar datos del usuario
            // Usamos DB::table para evitar problemas con el cast 'hashed'
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                    'email' => $validated['email'],
                    'updated_at' => now(),
                ]);

            // Actualizar datos del médico
            $medico->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'ci' => $validated['ci'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'especialidad_id' => $validated['especialidad_id'],
                'matricula' => $validated['matricula'],
                'registro_profesional' => $validated['registro_profesional'] ?? null,
                'años_experiencia' => $validated['años_experiencia'] ?? 0,
                'turno' => $validated['turno'] ?? null,
                'consultorio' => $validated['consultorio'] ?? null,
                'formacion_continua' => $validated['formacion_continua'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('medico.perfil.index')
                ->with('success', 'Perfil actualizado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Muestra el formulario para cambiar contraseña
     */
    public function editPassword(): View
    {
        return view('medico.perfil.change-password');
    }

    /**
     * Actualiza la contraseña del médico
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
        ]);

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta'
            ])->withInput();
        }

        // Actualizar contraseña
        // Usamos DB::table con Hash::make explícito para evitar problemas con el cast
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        return redirect()->route('medico.perfil.index')
            ->with('success', 'Contraseña actualizada exitosamente');
    }

    /**
     * Muestra la vista de horarios de atención del médico
     */
    public function horarios(): View
    {
        $medico = Auth::user()->medico;

        // Obtener horarios configurados
        $horarios = $medico->horarios()
            ->orderByRaw("CASE dia_semana 
                WHEN 'Lunes' THEN 1
                WHEN 'Martes' THEN 2
                WHEN 'Miércoles' THEN 3
                WHEN 'Jueves' THEN 4
                WHEN 'Viernes' THEN 5
                WHEN 'Sábado' THEN 6
                WHEN 'Domingo' THEN 7
            END")
            ->get();

        return view('medico.perfil.horarios', compact('medico', 'horarios'));
    }
}