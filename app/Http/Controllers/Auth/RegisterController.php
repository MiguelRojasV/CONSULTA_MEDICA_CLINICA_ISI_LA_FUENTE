<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

/**
 * RegisterController
 * Ubicación: app/Http/Controllers/Auth/RegisterController.php
 * 
 * Gestiona el registro de nuevos pacientes
 * Solo los pacientes pueden auto-registrarse
 * Los médicos y administradores son creados por el admin
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Campos actualizados: apellido, email, telefono_emergencia
 * - Cálculo automático de edad
 * - Validaciones mejoradas
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
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Datos de paciente
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => [
                'required',
                'string',
                'regex:/^\d{7,8}$/', // Solo números, 7-8 dígitos
                'unique:pacientes,ci'
            ],
            'fecha_nacimiento' => 'required|date|before:today|after:1900-01-01',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'telefono' => 'required|string|regex:/^\d{7,8}$/',
            'direccion' => 'nullable|string|max:200',
            'email_paciente' => 'nullable|email|max:100',
            'contacto_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|regex:/^\d{7,8}$/',
            'grupo_sanguineo' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo',
            'ocupacion' => 'nullable|string|max:100',
            'alergias' => 'nullable|string|max:500',
            'antecedentes' => 'nullable|string|max:1000',
        ], [
            // Mensajes personalizados
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo no es válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'ci.required' => 'El CI es obligatorio',
            'ci.regex' => 'El CI debe tener entre 7 y 8 dígitos numéricos',
            'ci.unique' => 'Este CI ya está registrado',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'fecha_nacimiento.after' => 'La fecha de nacimiento no es válida',
            'genero.required' => 'El género es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener entre 7 y 8 dígitos',
            'telefono_emergencia.regex' => 'El teléfono de emergencia debe tener entre 7 y 8 dígitos',
        ]);

        // Calcular edad desde fecha de nacimiento
        $edad = Carbon::parse($validated['fecha_nacimiento'])->age;

        // Iniciar transacción de base de datos
        DB::beginTransaction();

        try {
            // 1. Crear el usuario con rol 'paciente'
            $user = User::create([
                'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'paciente'
            ]);

            // 2. Crear el registro de paciente vinculado al usuario
            Paciente::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'edad' => $edad,
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'genero' => $validated['genero'],
                'direccion' => $validated['direccion'] ?? null,
                'telefono' => $validated['telefono'],
                'email' => $validated['email_paciente'] ?? $validated['email'],
                'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
                'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null,
                'estado_civil' => $validated['estado_civil'] ?? null,
                'ocupacion' => $validated['ocupacion'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'antecedentes' => $validated['antecedentes'] ?? null,
            ]);

            // Confirmar la transacción
            DB::commit();

            // Autenticar automáticamente al usuario
            Auth::login($user);

            // Redirigir al dashboard del paciente
            return redirect()->route('paciente.dashboard')
                ->with('success', '¡Registro exitoso! Bienvenido/a a Clínica ISI La Fuente.');

        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            // Log del error para debugging
            Log::error('Error en registro de paciente: ' . $e->getMessage());

            // Regresar con error
            return back()->withInput()->withErrors([
                'error' => 'Error al procesar el registro. Por favor, inténtelo nuevamente.'
            ]);
        }
    }
}

/**
 * EXPLICACIÓN DEL CONTROLADOR:
 * 
 * PROCESO DE REGISTRO:
 * 1. Valida todos los datos del formulario
 * 2. Calcula automáticamente la edad desde fecha_nacimiento
 * 3. Crea el usuario en la tabla 'users' con rol 'paciente'
 * 4. Crea el registro en 'pacientes' vinculado al usuario
 * 5. Autentica automáticamente al nuevo usuario
 * 6. Redirige al dashboard del paciente
 * 
 * VALIDACIONES IMPLEMENTADAS:
 * - CI: 7-8 dígitos numéricos, único
 * - Email: formato válido, único
 * - Contraseña: mínimo 8 caracteres, confirmación
 * - Teléfonos: 7-8 dígitos numéricos
 * - Fecha nacimiento: debe ser pasada, después de 1900
 * - Campos opcionales: dirección, contacto emergencia, alergias, etc.
 * 
 * TRANSACCIONES:
 * - Usa DB::beginTransaction() para garantizar integridad
 * - Si algo falla, se revierte todo con DB::rollBack()
 * - Solo commits si todo fue exitoso
 * 
 * CAMPOS NUEVOS EN ESTA ACTUALIZACIÓN:
 * - apellido (obligatorio)
 * - email del paciente (separado del login)
 * - telefono_emergencia
 * - estado_civil
 * - ocupacion
 */