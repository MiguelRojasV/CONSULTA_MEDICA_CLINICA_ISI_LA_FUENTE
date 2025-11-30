<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;  // ← AGREGAR ESTE IMPORT
use Illuminate\Support\Facades\Auth;

/**
 * MedicoDashboardController

 * 
 * Controla el panel principal del médico
 * Muestra agenda del día, estadísticas y accesos rápidos
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Usa medico->especialidad (relación)
 * - Campos actualizados según nueva BD
 */
class MedicoDashboardController extends Controller
{
    /**
     * Muestra el dashboard del médico
     * @return View|RedirectResponse  // ← ACTUALIZAR AQUÍ TAMBIÉN
     */
    public function index(): View|RedirectResponse  // ← CAMBIO PRINCIPAL
    {
        // Obtener el médico autenticado
        $user = Auth::user();
        $medico = $user->medico;

        // Verificar que el perfil esté completo
        if (!$medico) {
            return redirect()->route('home')
                ->with('error', 'No se encontró su perfil de médico. Contacte al administrador.');
        }

        // Cargar especialidad
        $medico->load('especialidad');

        // Obtener citas de hoy con pacientes
        $citasHoy = $medico->citasHoy()
            ->with('paciente')
            ->get();

        // Estadísticas generales
        $totalCitas = $medico->citas()->count();
        $citasAtendidas = $medico->citas()->where('estado', 'Atendida')->count();
        $citasPendientes = $medico->citas()
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->where('fecha', '>=', today())
            ->count();

        // Próximas citas (siguiente semana)
        $proximasCitas = $medico->citas()
            ->with('paciente')
            ->whereBetween('fecha', [today(), today()->addWeek()])
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->take(5)
            ->get();

        // Recetas emitidas este mes
        $recetasEsteMes = $medico->recetas()
            ->whereMonth('fecha_emision', now()->month)
            ->whereYear('fecha_emision', now()->year)
            ->count();

        // Total de pacientes atendidos
        $totalPacientes = $medico->contarPacientesAtendidos();

        return view('medico.dashboard', compact(
            'medico',
            'citasHoy',
            'totalCitas',
            'citasAtendidas',
            'citasPendientes',
            'proximasCitas',
            'recetasEsteMes',
            'totalPacientes'
        ));
    }
}