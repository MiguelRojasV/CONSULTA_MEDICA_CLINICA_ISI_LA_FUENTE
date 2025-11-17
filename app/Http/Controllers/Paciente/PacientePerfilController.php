<?php 
namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * PacientePerfilController
 * Permite al paciente ver y editar su información personal
 */
class PacientePerfilController extends Controller
{
    /**
     * Muestra el perfil del paciente
     * @return View
     */
    public function show(): View
    {
        $paciente = Auth::user()->paciente;
        $user = Auth::user();

        return view('paciente.perfil.show', compact('paciente', 'user'));
    }

    /**
     * Muestra el formulario para editar el perfil
     * @return View
     */
    public function edit(): View
    {
        $paciente = Auth::user()->paciente;
        $user = Auth::user();

        return view('paciente.perfil.edit', compact('paciente', 'user'));
    }

    /**
     * Actualiza el perfil del paciente
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        // Validar datos
        $validated = $request->validate([
            // Datos de usuario
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            
            // Datos de paciente
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'contacto_emergencia' => 'nullable|string|max:100',
            'edad' => 'required|integer|min:0|max:150',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'grupo_sanguineo' => 'nullable|string|max:5',
            'antecedentes' => 'nullable|string',
            'alergias' => 'nullable|string'
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.unique' => 'Este correo ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'edad.required' => 'La edad es obligatoria',
            'edad.min' => 'La edad no puede ser negativa'
        ]);

        // Actualizar usuario
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Si se proporcionó nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Actualizar paciente
        $paciente->nombre = $validated['name'];
        $paciente->telefono = $validated['telefono'] ?? null;
        $paciente->direccion = $validated['direccion'] ?? null;
        $paciente->contacto_emergencia = $validated['contacto_emergencia'] ?? null;
        $paciente->edad = $validated['edad'];
        $paciente->fecha_nacimiento = $validated['fecha_nacimiento'] ?? null;
        $paciente->genero = $validated['genero'] ?? null;
        $paciente->grupo_sanguineo = $validated['grupo_sanguineo'] ?? null;
        $paciente->antecedentes = $validated['antecedentes'] ?? null;
        $paciente->alergias = $validated['alergias'] ?? null;

        $paciente->save();

        return redirect()->route('paciente.perfil.show')
            ->with('success', 'Perfil actualizado exitosamente');
    }
}
