<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Receta;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * AdminReporteController
 * Ubicación: app/Http/Controllers/Admin/AdminReporteController.php
 * 
 * Genera reportes y búsquedas avanzadas del sistema
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 */
class AdminReporteController extends Controller
{
    /**
     * Vista principal de reportes
     */
    public function index(): View
    {
        // Estadísticas generales para la vista principal
        $totalPacientes = Paciente::count();
        $totalMedicos = Medico::where('estado', 'Activo')->count();
        $totalCitas = Cita::count();
        $totalRecetas = Receta::count();

        return view('admin.reportes.index', compact(
            'totalPacientes',
            'totalMedicos',
            'totalCitas',
            'totalRecetas'
        ));
    }

    /**
     * Reporte de citas por fecha/rango
     */
    public function citasPorFecha(Request $request): View
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        // Obtener citas en el rango
        $citas = Cita::with(['paciente', 'medico.especialidad'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // Estadísticas del período
        $totalCitas = $citas->count();
        $citasAtendidas = $citas->where('estado', 'Atendida')->count();
        $citasCanceladas = $citas->where('estado', 'Cancelada')->count();
        $citasPendientes = $citas->whereIn('estado', ['Programada', 'Confirmada'])->count();

        // Agrupar por médico
        $citasPorMedico = $citas->groupBy('medico_id')->map(function($grupo) {
            $medico = $grupo->first()->medico;
            return [
                'medico' => $medico->nombre_completo,
                'especialidad' => $medico->especialidad->nombre,
                'total' => $grupo->count(),
                'atendidas' => $grupo->where('estado', 'Atendida')->count(),
                'canceladas' => $grupo->where('estado', 'Cancelada')->count(),
            ];
        })->values();

        // Agrupar por día
        $citasPorDia = $citas->groupBy(function($cita) {
            return $cita->fecha->format('Y-m-d');
        })->map(function($grupo, $fecha) {
            return [
                'fecha' => Carbon::parse($fecha)->format('d/m/Y'),
                'total' => $grupo->count(),
                'atendidas' => $grupo->where('estado', 'Atendida')->count(),
            ];
        })->values();

        return view('admin.reportes.citas', compact(
            'citas',
            'fechaInicio',
            'fechaFin',
            'totalCitas',
            'citasAtendidas',
            'citasCanceladas',
            'citasPendientes',
            'citasPorMedico',
            'citasPorDia'
        ));
    }

    /**
     * Reporte de pacientes
     */
    public function pacientes(Request $request): View
    {
        $query = Paciente::with(['citas', 'recetas']);

        // Filtro por fecha de registro
        if ($request->filled('mes')) {
            $mes = $request->input('mes');
            $anio = $request->input('anio', now()->year);
            $query->whereMonth('created_at', $mes)
                  ->whereYear('created_at', $anio);
        }

        // Filtro por género
        if ($request->filled('genero')) {
            $query->where('genero', $request->input('genero'));
        }

        // Filtro por grupo sanguíneo
        if ($request->filled('grupo_sanguineo')) {
            $query->where('grupo_sanguineo', $request->input('grupo_sanguineo'));
        }

        $pacientes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas
        $totalPacientes = Paciente::count();
        $pacientesEsteMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Distribución por género
        $porGenero = Paciente::selectRaw('genero, COUNT(*) as total')
            ->groupBy('genero')
            ->pluck('total', 'genero');

        return view('admin.reportes.pacientes', compact(
            'pacientes',
            'totalPacientes',
            'pacientesEsteMes',
            'porGenero'
        ));
    }

    /**
     * Reporte de médicos
     */
    public function medicos(Request $request): View
    {
        $query = Medico::with(['especialidad', 'citas', 'recetas']);

        if ($request->filled('especialidad_id')) {
            $query->where('especialidad_id', $request->input('especialidad_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $medicos = $query->withCount([
            'citas',
            'citas as citas_atendidas_count' => function($q) {
                $q->where('estado', 'Atendida');
            },
            'recetas'
        ])->get();

        $especialidades = \App\Models\Especialidad::activas()->get();

        return view('admin.reportes.medicos', compact('medicos', 'especialidades'));
    }

    /**
     * Reporte de medicamentos
     */
    public function medicamentos(Request $request): View
{
    $query = Medicamento::query();

    // Alertas con consultas directas
    // Stock bajo: disponibilidad menor al stock mínimo pero mayor a 0
    $stockBajo = Medicamento::whereColumn('disponibilidad', '<', 'stock_minimo')
        ->where('disponibilidad', '>', 0)
        ->count();
    
    // Sin stock: disponibilidad igual a 0
    $sinStock = Medicamento::where('disponibilidad', 0)->count();
    
    // Vencidos: fecha de caducidad menor a hoy
    $vencidos = Medicamento::where('caducidad', '<', now())->count();
    
    // Por vencer: fecha de caducidad entre hoy y 30 días
    $porVencer = Medicamento::where('caducidad', '>', now())
        ->where('caducidad', '<=', now()->addDays(30))
        ->count();

    // Valor total del inventario con manejo seguro de NULL
    $valorInventario = (float) (Medicamento::selectRaw('COALESCE(SUM(disponibilidad * precio_unitario), 0) as total')
        ->value('total') ?? 0);

    $medicamentos = $query->orderBy('nombre_generico')->get();

    return view('admin.reportes.medicamentos', compact(
        'medicamentos',
        'stockBajo',
        'sinStock',
        'vencidos',
        'porVencer',
        'valorInventario'
    ));
}

    /**
     * Búsqueda avanzada en el sistema
     */
    public function busquedaAvanzada(Request $request): View
    {
        $resultados = collect();
        $tipo = $request->input('tipo');
        $criterio = $request->input('criterio');
        $valor = $request->input('valor');

        if ($request->filled(['tipo', 'criterio', 'valor'])) {
            
            if ($tipo === 'paciente') {
                $query = Paciente::query();
                
                if ($criterio === 'nombre') {
                    $query->where(function($q) use ($valor) {
                        $q->where('nombre', 'like', "%{$valor}%")
                          ->orWhere('apellido', 'like', "%{$valor}%");
                    });
                } elseif ($criterio === 'ci') {
                    $query->where('ci', 'like', "%{$valor}%");
                } elseif ($criterio === 'mes') {
                    $fecha = Carbon::parse($valor);
                    $query->whereMonth('created_at', $fecha->month)
                          ->whereYear('created_at', $fecha->year);
                }
                
                $resultados = $query->with('citas')->get();
                
            } elseif ($tipo === 'medico') {
                $query = Medico::with('especialidad');
                
                if ($criterio === 'nombre') {
                    $query->where(function($q) use ($valor) {
                        $q->where('nombre', 'like', "%{$valor}%")
                          ->orWhere('apellido', 'like', "%{$valor}%");
                    });
                } elseif ($criterio === 'especialidad') {
                    $query->whereHas('especialidad', function($q) use ($valor) {
                        $q->where('nombre', 'like', "%{$valor}%");
                    });
                } elseif ($criterio === 'matricula') {
                    $query->where('matricula', 'like', "%{$valor}%");
                }
                
                $resultados = $query->with('citas')->get();
                
            } elseif ($tipo === 'cita') {
                $query = Cita::with(['paciente', 'medico.especialidad']);
                
                if ($criterio === 'fecha') {
                    $query->whereDate('fecha', $valor);
                } elseif ($criterio === 'mes') {
                    $fecha = Carbon::parse($valor);
                    $query->whereMonth('fecha', $fecha->month)
                          ->whereYear('fecha', $fecha->year);
                } elseif ($criterio === 'dia') {
                    $query->whereDate('fecha', $valor);
                }
                
                $resultados = $query->get();
            }
        }

        return view('admin.reportes.busqueda', compact(
            'resultados',
            'tipo',
            'criterio',
            'valor'
        ));
    }
}