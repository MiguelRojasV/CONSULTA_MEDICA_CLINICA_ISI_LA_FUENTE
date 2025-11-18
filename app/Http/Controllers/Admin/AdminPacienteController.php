<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * AdminPacienteController
 * Ubicación: app/Http/Controllers/Admin/AdminPacienteController.php
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class AdminPacienteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Paciente::query();

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->input('genero'));
        }

        $pacientes = $query->orderBy('nombre')->paginate(15);

        return view('admin.pacientes.index', compact('pacientes'));
    }

    public function create(): View
    {
        return view('admin.pacientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|regex:/^\d{7,8}$/|unique:pacientes,ci',
            'fecha_nacimiento' => 'required|date|before:today',
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
        ]);

        $edad = Carbon::parse($validated['fecha_nacimiento'])->age;

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'paciente'
            ]);

            Paciente::create([
                'user_id' => $user->id,
                'ci' => $validated['ci'],
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'edad' => $edad,
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'genero' => $validated['genero'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'] ?? null,
                'email' => $validated['email_paciente'] ?? $validated['email'],
                'contacto_emergencia' => $validated['contacto_emergencia'] ?? null,
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
                'grupo_sanguineo' => $validated['grupo_sanguineo'] ?? null,
                'estado_civil' => $validated['estado_civil'] ?? null,
                'ocupacion' => $validated['ocupacion'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'antecedentes' => $validated['antecedentes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.pacientes.index')
                ->with('success', 'Paciente creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear paciente: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Error al crear el paciente']);
        }
    }

    public function show(Paciente $paciente): View
    {
        $paciente->load(['citas.medico.especialidad', 'recetas.medicamentos', 'historialMedico.medico.especialidad']);

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

    // edit() y update() similares, actualizando campos

    public function destroy(Paciente $paciente): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = $paciente->user;
            $paciente->delete();
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.pacientes.index')
                ->with('success', 'Paciente eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el paciente']);
        }
    }

    public function descargarHistorialPDF(Paciente $paciente)
    {
        $historial = $paciente->historialMedico()
            ->with(['medico.especialidad', 'cita'])
            ->orderBy('fecha', 'desc')
            ->get();

        $clinica = \App\Models\InformacionClinica::obtenerInfo();

        $pdf = Pdf::loadView('pdf.historial-medico', compact('paciente', 'historial', 'clinica'));
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'historial_medico_' . $paciente->ci . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}