<?php
namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * MedicoDashboardController
 * Controla el panel principal del médico
 * Muestra agenda del día, estadísticas y accesos rápidos
 */
class MedicoDashboardController extends Controller
{
    /**
     * Muestra el dashboard del médico
     * @return View
     */
    public function index(): View
    {
        // Obtener el médico autenticado
        $user = Auth::user();
        $medico = $user->medico;

        // Obtener citas de hoy
        $citasHoy = $medico->citasHoy()->get();

        // Estadísticas generales
        $totalCitas = $medico->citas()->count();
        $citasAtendidas = $medico->citas()->where('estado', 'Atendida')->count();
        $citasPendientes = $medico->citas()
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->where('fecha', '>=', now())
            ->count();

        // Próximas citas (siguiente semana)
        $proximasCitas = $medico->citas()
            ->with('paciente')
            ->whereBetween('fecha', [now(), now()->addWeek()])
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

        return view('medico.dashboard', compact(
            'medico',
            'citasHoy',
            'totalCitas',
            'citasAtendidas',
            'citasPendientes',
            'proximasCitas',
            'recetasEsteMes'
        ));
    }
}