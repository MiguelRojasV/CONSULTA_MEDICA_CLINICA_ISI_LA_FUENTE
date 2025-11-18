<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * PacientePerfilController
 * Ubicación: app/Http/Controllers/Paciente/PacientePerfilController.php
 * 
 * ACTUALIZADO: Campos nuevos: apellido, email_paciente, telefono_emergencia, etc.
 */
class PacientePerfilController extends Controller
{
    public function show(): View
    {
        $paciente = Auth::user()->paciente;
        $user = Auth::user();

        return view('paciente.perfil.show', compact('paciente', 'user'));
    }

    public function edit(): View
    {
        $paciente = Auth::user()->paciente;
        $user = Auth::user();

        return view('paciente.perfil.edit', compact('paciente', 'user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|regex:/^\d{7,8}$/',
            'direccion' => 'nullable|string|max:200',
            'contacto_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|regex:/^\d{7,8}$/',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'grupo_sanguineo' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo',
            'ocupacion' => 'nullable|string|max:100',
            'antecedentes' => 'nullable|string|max:1000',
            'alergias' => 'nullable|string|max:500',
        ]);

        // Actualizar usuario
        $user->name = $validated['nombre'] . ' ' . $validated['apellido'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Calcular edad
        $edad = Carbon::parse($validated['fecha_nacimiento'])->age;

        // Actualizar paciente
        $paciente->update([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'edad' => $edad,
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'genero' => $validated['genero'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
            'contacto_emergencia' => $validated['contacto_emergencia'],
            'telefono_emergencia' => $validated['telefono_emergencia'],
            'grupo_sanguineo' => $validated['grupo_sanguineo'],
            'estado_civil' => $validated['estado_civil'],
            'ocupacion' => $validated['ocupacion'],
            'antecedentes' => $validated['antecedentes'],
            'alergias' => $validated['alergias'],
        ]);

        return redirect()->route('paciente.perfil.show')
            ->with('success', 'Perfil actualizado exitosamente');
    }
}