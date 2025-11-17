<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Cita;
use App\Models\Receta;
use App\Models\Medicamento;

/**
 * AdminDashboardController
 * Controla el panel principal del administrador
 * Muestra estadísticas generales del sistema
 */
class AdminDashboardController extends Controller
{
    /**
     * Muestra el dashboard del administrador
     * @return View
     */
    public function index(): View
    {
        // Estadísticas generales del sistema
        $totalPacientes = Paciente::count();
        $totalMedicos = Medico::count();
        $totalCitas = Cita::count();
        $totalRecetas = Receta::count();

        // Citas de hoy
        $citasHoy = Cita::with(['paciente', 'medico'])
            ->whereDate('fecha', today())
            ->orderBy('hora')
            ->get();

        // Citas por estado
        $citasProgramadas = Cita::where('estado', 'Programada')->count();
        $citasAtendidas = Cita::where('estado', 'Atendida')->count();
        $citasCanceladas = Cita::where('estado', 'Cancelada')->count();

        // Medicamentos con stock bajo
        $medicamentosStockBajo = Medicamento::where('disponibilidad', '<', 20)
            ->where('disponibilidad', '>', 0)
            ->count();

        // Medicamentos vencidos o por vencer
        $medicamentosVencidos = Medicamento::where('caducidad', '<', now())
            ->count();
        $medicamentosPorVencer = Medicamento::whereBetween('caducidad', [now(), now()->addDays(30)])
            ->count();

        // Pacientes registrados este mes
        $pacientesEsteMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Citas de la semana
        $citasEstaSemana = Cita::whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('admin.dashboard', compact(
            'totalPacientes',
            'totalMedicos',
            'totalCitas',
            'totalRecetas',
            'citasHoy',
            'citasProgramadas',
            'citasAtendidas',
            'citasCanceladas',
            'medicamentosStockBajo',
            'medicamentosVencidos',
            'medicamentosPorVencer',
            'pacientesEsteMes',
            'citasEstaSemana'
        ));
    }
}
