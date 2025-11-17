<?php 
// Controlador de Registro (Solo para Pacientes)
// ============================================
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * RegisterController
 * Gestiona el registro de nuevos pacientes
 * Solo los pacientes pueden auto-registrarse
 * Los médicos y administradores son creados por el admin
 */
class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo paciente
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // Validar todos los datos del formulario
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
                'regex:/^[0-9]+$/', // Solo números
                'unique:pacientes'
            ],
            'edad' => 'required|integer|min:0|max:150',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'contacto_emergencia' => 'nullable|string|max:100',
            'grupo_sanguineo' => 'nullable|string|max:5'
        ], [
            // Mensajes personalizados de validación
            'name.required' => 'El nombre completo es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo no es válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'ci.required' => 'El CI es obligatorio',
            'ci.min' => 'El CI debe tener al menos 7 dígitos',
            'ci.max' => 'El CI no puede tener más de 8 dígitos',
            'ci.regex' => 'El CI solo debe contener números',
            'ci.unique' => 'Este CI ya está registrado',
            'edad.required' => 'La edad es obligatoria',
            'edad.min' => 'La edad no puede ser negativa',
            'edad.max' => 'La edad no es válida'
        ]);

        // Iniciar transacción de base de datos
        // Si algo falla, se revierte todo
        DB::beginTransaction();

        try {
            // 1. Crear el usuario con rol 'paciente'
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'paciente' // Asignar rol de paciente
            ]);

            // 2. Crear el registro de paciente vinculado al usuario
            Paciente::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['name'],
                'edad' => $validated['edad'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'genero' => $validated['genero'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null
            ]);

            // Confirmar la transacción
            DB::commit();

            // Autenticar automáticamente al usuario
            Auth::login($user);

            // Redirigir al dashboard del paciente
            return redirect()->route('paciente.dashboard')
                ->with('success', '¡Registro exitoso! Bienvenido a Clínica ISI La Fuente.');

        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            // Regresar con error
            return back()->withInput()->withErrors([
                'error' => 'Error al procesar el registro. Por favor, inténtelo nuevamente.'
            ]);
        }
    }
}
