<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Cita;
use App\Models\Receta;
use App\Models\Medicamento;
use App\Models\Especialidad;

/**
 * AdminDashboardController
 * Ubicación: app/Http/Controllers/Admin/AdminDashboardController.php
 * 
 * Controla el panel principal del administrador
 * Muestra estadísticas generales del sistema
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Usa medico->especialidad (relación)
 * - Alertas de medicamentos mejoradas
 * - Estadísticas por especialidad
 */
class AdminDashboardController extends Controller
{
    /**
     * Muestra el dashboard del administrador
     * @return View
     */
    public function index(): View
    {
        // ============================================
        // ESTADÍSTICAS GENERALES DEL SISTEMA
        // ============================================
        
        $totalPacientes = Paciente::count();
        $totalMedicos = Medico::where('estado', 'Activo')->count();
        $totalCitas = Cita::count();
        $totalRecetas = Receta::count();
        $totalEspecialidades = Especialidad::activas()->count();

        // ============================================
        // CITAS DE HOY
        // ============================================
        
        $citasHoy = Cita::with(['paciente', 'medico.especialidad'])
            ->whereDate('fecha', today())
            ->orderBy('hora')
            ->get();

        // ============================================
        // DISTRIBUCIÓN DE CITAS POR ESTADO
        // ============================================
        
        $citasProgramadas = Cita::where('estado', 'Programada')
            ->where('fecha', '>=', today())
            ->count();
        
        $citasConfirmadas = Cita::where('estado', 'Confirmada')
            ->where('fecha', '>=', today())
            ->count();
        
        $citasAtendidas = Cita::where('estado', 'Atendida')->count();
        $citasCanceladas = Cita::where('estado', 'Cancelada')->count();

        // ============================================
        // ALERTAS DE MEDICAMENTOS
        // ============================================
        
        // Stock bajo (menos del mínimo)
      $medicamentosStockBajo = Medicamento::whereColumn('disponibilidad', '<', 'stock_minimo')
    ->where('disponibilidad', '>', 0)
    ->count();

// Sin stock
$medicamentosSinStock = Medicamento::where('disponibilidad', 0)->count();

// Vencidos
$medicamentosVencidos = Medicamento::where('caducidad', '<', now())->count();

// Por vencer (30 días)
$medicamentosPorVencer = Medicamento::where('caducidad', '>', now())
    ->where('caducidad', '<=', now()->addDays(30))
    ->count();

        // ============================================
        // MÉTRICAS TEMPORALES
        // ============================================
        
        // Pacientes registrados este mes
        $pacientesEsteMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Citas de esta semana
        $citasEstaSemana = Cita::whereBetween('fecha', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Recetas emitidas este mes
        $recetasEsteMes = Receta::whereMonth('fecha_emision', now()->month)
            ->whereYear('fecha_emision', now()->year)
            ->count();

        // ============================================
        // ESTADÍSTICAS POR ESPECIALIDAD
        // ============================================
        
        $especialidades = Especialidad::activas()
            ->withCount(['medicos' => function($query) {
                $query->where('estado', 'Activo');
            }])
            ->having('medicos_count', '>', 0)
            ->get();

        // ============================================
        // PRÓXIMAS CITAS (3 días)
        // ============================================
        
        $proximasCitas = Cita::with(['paciente', 'medico.especialidad'])
            ->whereBetween('fecha', [today(), today()->addDays(3)])
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->take(10)
            ->get();

        // ============================================
        // MÉDICOS MÁS ACTIVOS (mes actual)
        // ============================================
        
        $medicosActivos = Medico::with('especialidad')
            ->withCount(['citas' => function($query) {
                $query->whereMonth('fecha', now()->month)
                      ->whereYear('fecha', now()->year)
                      ->where('estado', 'Atendida');
            }])
            ->where('estado', 'Activo')
            ->orderByDesc('citas_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            // Estadísticas generales
            'totalPacientes',
            'totalMedicos',
            'totalCitas',
            'totalRecetas',
            'totalEspecialidades',
            
            // Citas
            'citasHoy',
            'citasProgramadas',
            'citasConfirmadas',
            'citasAtendidas',
            'citasCanceladas',
            'proximasCitas',
            
            // Medicamentos
            'medicamentosStockBajo',
            'medicamentosSinStock',
            'medicamentosVencidos',
            'medicamentosPorVencer',
            
            // Métricas temporales
            'pacientesEsteMes',
            'citasEstaSemana',
            'recetasEsteMes',
            
            // Análisis
            'especialidades',
            'medicosActivos'
        ));
    }
}