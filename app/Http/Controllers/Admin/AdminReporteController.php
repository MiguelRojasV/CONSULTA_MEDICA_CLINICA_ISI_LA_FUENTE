<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * AdminReporteController
 * Genera reportes y búsquedas avanzadas del sistema
 */
class AdminReporteController extends Controller
{
    /**
     * Vista principal de reportes
     * @return View
     */
    public function index(): View
    {
        return view('admin.reportes.index');
    }

    /**
     * Reporte de citas por fecha/rango
     * @param Request $request
     * @return View
     */
    public function citasPorFecha(Request $request): View
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        // Obtener citas en el rango
        $citas = Cita::with(['paciente', 'medico'])
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
            return [
                'medico' => $grupo->first()->medico->nombre,
                'total' => $grupo->count(),
                'atendidas' => $grupo->where('estado', 'Atendida')->count()
            ];
        });

        return view('admin.reportes.citas', compact(
            'citas',
            'fechaInicio',
            'fechaFin',
            'totalCitas',
            'citasAtendidas',
            'citasCanceladas',
            'citasPendientes',
            'citasPorMedico'
        ));
    }

    /**
     * Reporte de pacientes
     * @param Request $request
     * @return View
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

        $pacientes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas
        $totalPacientes = Paciente::count();
        $pacientesEsteMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.reportes.pacientes', compact(
            'pacientes',
            'totalPacientes',
            'pacientesEsteMes'
        ));
    }

    /**
     * Búsqueda avanzada en el sistema
     * @param Request $request
     * @return View
     */
    public function busquedaAvanzada(Request $request): View
    {
        $resultados = [];
        $tipo = $request->input('tipo'); // 'paciente', 'medico', 'cita'
        $criterio = $request->input('criterio'); // 'nombre', 'ci', 'fecha', 'mes'
        $valor = $request->input('valor');

        if ($request->filled('tipo') && $request->filled('criterio') && $request->filled('valor')) {
            
            if ($tipo === 'paciente') {
                $query = Paciente::query();
                
                if ($criterio === 'nombre') {
                    $query->where('nombre', 'like', "%{$valor}%");
                } elseif ($criterio === 'ci') {
                    $query->where('ci', 'like', "%{$valor}%");
                } elseif ($criterio === 'mes') {
                    $fecha = Carbon::parse($valor);
                    $query->whereMonth('created_at', $fecha->month)
                          ->whereYear('created_at', $fecha->year);
                }
                
                $resultados = $query->with('citas')->get();
                
            } elseif ($tipo === 'medico') {
                $query = Medico::query();
                
                if ($criterio === 'nombre') {
                    $query->where('nombre', 'like', "%{$valor}%");
                } elseif ($criterio === 'especialidad') {
                    $query->where('especialidad', 'like', "%{$valor}%");
                }
                
                $resultados = $query->with('citas')->get();
                
            } elseif ($tipo === 'cita') {
                $query = Cita::with(['paciente', 'medico']);
                
                if ($criterio === 'fecha') {
                    $query->whereDate('fecha', $valor);
                } elseif ($criterio === 'mes') {
                    $fecha = Carbon::parse($valor);
                    $query->whereMonth('fecha', $fecha->month)
                          ->whereYear('fecha', $fecha->year);
                }
                
                $resultados = $query->get();
            }
        }

        return view('admin.reportes.busqueda', compact('resultados', 'tipo', 'criterio', 'valor'));
    }
}
