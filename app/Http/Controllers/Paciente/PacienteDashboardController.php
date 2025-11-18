<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * PacienteDashboardController
 * Ubicación: app/Http/Controllers/Paciente/PacienteDashboardController.php
 * 
 * Controla el panel principal del paciente
 * Muestra resumen de citas, recetas y accesos rápidos
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Usa relación medico->especialidad
 * - Métodos de modelos actualizados
 */
class PacienteDashboardController extends Controller
{
    /**
     * Muestra el dashboard del paciente
     * @return View
     */
    public function index(): View
    {
        // Obtener el paciente autenticado
        $user = Auth::user();
        $paciente = $user->paciente;

        // Verificar que el perfil esté completo
        if (!$paciente) {
            return redirect()->route('home')
                ->with('error', 'No se encontró su perfil de paciente. Contacte al administrador.');
        }

        // Obtener próximas citas con médico y especialidad
        $proximasCitas = $paciente->citasProximas()
            ->with('medico.especialidad')
            ->take(5)
            ->get();

        // Estadísticas generales
        $totalCitas = $paciente->citas()->count();
        $citasAtendidas = $paciente->citas()->where('estado', 'Atendida')->count();
        $citasPendientes = $paciente->citas()
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->where('fecha', '>=', today())
            ->count();

        // Obtener recetas recientes con medicamentos
        $recetasRecientes = $paciente->recetas()
            ->with(['medico.especialidad', 'medicamentos'])
            ->orderBy('fecha_emision', 'desc')
            ->take(3)
            ->get();

        // Última consulta
        $ultimaConsulta = $paciente->ultimaCitaAtendida();

        return view('paciente.dashboard', compact(
            'paciente',
            'proximasCitas',
            'totalCitas',
            'citasAtendidas',
            'citasPendientes',
            'recetasRecientes',
            'ultimaConsulta'
        ));
    }
}