<?php 
namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * PacienteDashboardController
 * Controla el panel principal del paciente
 * Muestra resumen de citas, recetas y accesos rápidos
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

        // Obtener estadísticas
        $proximasCitas = $paciente->citasProximas()->take(5)->get();
        $totalCitas = $paciente->citas()->count();
        $citasAtendidas = $paciente->citas()->where('estado', 'Atendida')->count();
        $citasPendientes = $paciente->citas()
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->count();

        // Obtener recetas recientes
        $recetasRecientes = $paciente->recetas()
            ->orderBy('fecha_emision', 'desc')
            ->take(3)
            ->get();

        return view('paciente.dashboard', compact(
            'paciente',
            'proximasCitas',
            'totalCitas',
            'citasAtendidas',
            'citasPendientes',
            'recetasRecientes'
        ));
    }
}