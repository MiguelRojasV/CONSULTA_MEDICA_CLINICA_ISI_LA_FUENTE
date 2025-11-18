<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * AdminMedicamentoController
 * Ubicación: app/Http/Controllers/Admin/AdminMedicamentoController.php
 * 
 * CRUD completo de medicamentos (inventario)
 * ACTUALIZADO: Campos nuevos según 3FN
 * - concentracion, via_administracion, stock_minimo
 * - precio_unitario, requiere_receta, contraindicaciones
 */
class AdminMedicamentoController extends Controller
{
    /**
     * Lista todos los medicamentos
     */
    public function index(Request $request): View
    {
        $query = Medicamento::query();

        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre_generico', 'like', "%{$buscar}%")
                  ->orWhere('nombre_comercial', 'like', "%{$buscar}%")
                  ->orWhere('laboratorio', 'like', "%{$buscar}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Filtro por stock
        if ($request->filled('stock')) {
            $filtro = $request->input('stock');
            if ($filtro === 'bajo') {
                $query->stockBajo();
            } elseif ($filtro === 'sin_stock') {
                $query->sinStock();
            } elseif ($filtro === 'critico') {
                $query->where('disponibilidad', '>', 0)
                      ->where('disponibilidad', '<', 5);
            }
        }

        // Filtro por vencimiento
        if ($request->filled('vencimiento')) {
            $filtro = $request->input('vencimiento');
            if ($filtro === 'vencidos') {
                $query->vencidos();
            } elseif ($filtro === 'por_vencer') {
                $query->porVencer();
            }
        }

        // Filtro por requiere receta
        if ($request->filled('requiere_receta')) {
            $query->where('requiere_receta', $request->input('requiere_receta'));
        }

        $medicamentos = $query->orderBy('nombre_generico')->paginate(20);

        // Para los filtros
        $tipos = Medicamento::distinct()->pluck('tipo')->filter();

        return view('admin.medicamentos.index', compact('medicamentos', 'tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo medicamento
     */
    public function create(): View
    {
        return view('admin.medicamentos.create');
    }

    /**
     * Guarda un nuevo medicamento
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|string|max:200',
            'nombre_comercial' => 'nullable|string|max:200',
            'tipo' => 'nullable|string|max:100',
            'presentacion' => 'nullable|string|max:100',
            'dosis' => 'nullable|string|max:100',
            'concentracion' => 'nullable|string|max:50',
            'via_administracion' => 'nullable|string|max:50',
            'disponibilidad' => 'required|integer|min:0|max:999999',
            'stock_minimo' => 'nullable|integer|min:0|max:999',
            'precio_unitario' => 'nullable|numeric|min:0|max:99999.99',
            'caducidad' => 'nullable|date|after:today',
            'lote' => 'nullable|string|max:50',
            'laboratorio' => 'nullable|string|max:100',
            'requiere_receta' => 'nullable|boolean',
            'contraindicaciones' => 'nullable|string|max:1000',
        ], [
            'disponibilidad.min' => 'La disponibilidad no puede ser negativa',
            'disponibilidad.max' => 'La disponibilidad no puede exceder 999,999',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo',
            'precio_unitario.min' => 'El precio no puede ser negativo',
            'precio_unitario.max' => 'El precio no puede exceder 99,999.99',
            'caducidad.after' => 'La fecha de caducidad debe ser futura',
        ]);

        // Valores por defecto
        $validated['stock_minimo'] = $validated['stock_minimo'] ?? 10;
        $validated['requiere_receta'] = $request->has('requiere_receta');

        Medicamento::create($validated);

        return redirect()->route('admin.medicamentos.index')
            ->with('success', 'Medicamento agregado exitosamente al inventario');
    }

    /**
     * Muestra los detalles de un medicamento
     */
    public function show(Medicamento $medicamento): View
    {
        // Obtener recetas que incluyen este medicamento
        $recetas = $medicamento->recetas()
            ->with(['paciente', 'medico'])
            ->orderBy('fecha_emision', 'desc')
            ->take(10)
            ->get();

        // Estadísticas
        $totalRecetas = $medicamento->recetas()->count();
        $totalDispensado = $medicamento->recetas()
            ->where('estado', 'Dispensada')
            ->sum('receta_medicamento.cantidad');

        return view('admin.medicamentos.show', compact(
            'medicamento',
            'recetas',
            'totalRecetas',
            'totalDispensado'
        ));
    }

    /**
     * Muestra el formulario para editar un medicamento
     */
    public function edit(Medicamento $medicamento): View
    {
        return view('admin.medicamentos.edit', compact('medicamento'));
    }

    /**
     * Actualiza un medicamento
     */
    public function update(Request $request, Medicamento $medicamento): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|string|max:200',
            'nombre_comercial' => 'nullable|string|max:200',
            'tipo' => 'nullable|string|max:100',
            'presentacion' => 'nullable|string|max:100',
            'dosis' => 'nullable|string|max:100',
            'concentracion' => 'nullable|string|max:50',
            'via_administracion' => 'nullable|string|max:50',
            'disponibilidad' => 'required|integer|min:0|max:999999',
            'stock_minimo' => 'nullable|integer|min:0|max:999',
            'precio_unitario' => 'nullable|numeric|min:0|max:99999.99',
            'caducidad' => 'nullable|date',
            'lote' => 'nullable|string|max:50',
            'laboratorio' => 'nullable|string|max:100',
            'requiere_receta' => 'nullable|boolean',
            'contraindicaciones' => 'nullable|string|max:1000',
        ]);

        $validated['stock_minimo'] = $validated['stock_minimo'] ?? 10;
        $validated['requiere_receta'] = $request->has('requiere_receta');

        $medicamento->update($validated);

        return redirect()->route('admin.medicamentos.show', $medicamento)
            ->with('success', 'Medicamento actualizado exitosamente');
    }

    /**
     * Elimina un medicamento
     */
    public function destroy(Medicamento $medicamento): RedirectResponse
    {
        // Verificar si está en recetas pendientes
        $enUso = $medicamento->recetas()
            ->where('estado', 'Pendiente')
            ->count();

        if ($enUso > 0) {
            return back()->withErrors([
                'error' => "No se puede eliminar el medicamento porque está en {$enUso} recetas pendientes"
            ]);
        }

        // Guardar nombre para el mensaje
        $nombre = $medicamento->nombre_completo;

        $medicamento->delete();

        return redirect()->route('admin.medicamentos.index')
            ->with('success', "Medicamento '{$nombre}' eliminado exitosamente");
    }

    /**
     * Ajusta el stock de un medicamento (entrada/salida)
     */
    public function ajustarStock(Request $request, Medicamento $medicamento): RedirectResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:200',
        ]);

        $stockAnterior = $medicamento->disponibilidad;

        if ($validated['tipo'] === 'entrada') {
            $medicamento->aumentarStock($validated['cantidad']);
            $mensaje = "Stock aumentado de {$stockAnterior} a {$medicamento->disponibilidad} unidades";
        } else {
            if ($medicamento->disponibilidad < $validated['cantidad']) {
                return back()->withErrors([
                    'error' => 'No hay suficiente stock disponible'
                ]);
            }
            $medicamento->reducirStock($validated['cantidad']);
            $mensaje = "Stock reducido de {$stockAnterior} a {$medicamento->disponibilidad} unidades";
        }

        return redirect()->route('admin.medicamentos.show', $medicamento)
            ->with('success', $mensaje);
    }
}