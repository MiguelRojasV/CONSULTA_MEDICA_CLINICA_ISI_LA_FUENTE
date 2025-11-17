<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * AdminMedicamentoController
 * CRUD completo de medicamentos (inventario)
 */
class AdminMedicamentoController extends Controller
{
    /**
     * Lista todos los medicamentos
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Medicamento::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre_generico', 'like', "%{$buscar}%")
                  ->orWhere('nombre_comercial', 'like', "%{$buscar}%");
            });
        }

        // Filtro por stock
        if ($request->filled('stock')) {
            $filtro = $request->input('stock');
            if ($filtro === 'bajo') {
                $query->where('disponibilidad', '<', 20)
                      ->where('disponibilidad', '>', 0);
            } elseif ($filtro === 'sin_stock') {
                $query->where('disponibilidad', 0);
            }
        }

        // Filtro por vencimiento
        if ($request->filled('vencimiento')) {
            $filtro = $request->input('vencimiento');
            if ($filtro === 'vencidos') {
                $query->where('caducidad', '<', now());
            } elseif ($filtro === 'por_vencer') {
                $query->whereBetween('caducidad', [now(), now()->addDays(30)]);
            }
        }

        $medicamentos = $query->orderBy('nombre_generico')->paginate(20);

        return view('admin.medicamentos.index', compact('medicamentos'));
    }

    /**
     * Muestra el formulario para crear un nuevo medicamento
     * @return View
     */
    public function create(): View
    {
        return view('admin.medicamentos.create');
    }

    /**
     * Guarda un nuevo medicamento
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|string|max:200',
            'nombre_comercial' => 'nullable|string|max:200',
            'tipo' => 'nullable|string|max:100',
            'presentacion' => 'nullable|string|max:100',
            'dosis' => 'nullable|string|max:100',
            'disponibilidad' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'caducidad' => 'nullable|date|after:today',
            'lote' => 'nullable|string|max:50',
            'laboratorio' => 'nullable|string|max:100'
        ], [
            'disponibilidad.min' => 'La disponibilidad no puede ser negativa',
            'precio_unitario.min' => 'El precio no puede ser negativo'
        ]);

        Medicamento::create($validated);

        return redirect()->route('admin.medicamentos.index')
            ->with('success', 'Medicamento agregado exitosamente');
    }

    /**
     * Muestra los detalles de un medicamento
     * @param Medicamento $medicamento
     * @return View
     */
    public function show(Medicamento $medicamento): View
    {
        return view('admin.medicamentos.show', compact('medicamento'));
    }

    /**
     * Muestra el formulario para editar un medicamento
     * @param Medicamento $medicamento
     * @return View
     */
    public function edit(Medicamento $medicamento): View
    {
        return view('admin.medicamentos.edit', compact('medicamento'));
    }

    /**
     * Actualiza un medicamento
     * @param Request $request
     * @param Medicamento $medicamento
     * @return RedirectResponse
     */
    public function update(Request $request, Medicamento $medicamento): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_generico' => 'required|string|max:200',
            'nombre_comercial' => 'nullable|string|max:200',
            'tipo' => 'nullable|string|max:100',
            'presentacion' => 'nullable|string|max:100',
            'dosis' => 'nullable|string|max:100',
            'disponibilidad' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'caducidad' => 'nullable|date',
            'lote' => 'nullable|string|max:50',
            'laboratorio' => 'nullable|string|max:100'
        ]);

        $medicamento->update($validated);

        return redirect()->route('admin.medicamentos.show', $medicamento)
            ->with('success', 'Medicamento actualizado exitosamente');
    }

    /**
     * Elimina un medicamento
     * @param Medicamento $medicamento
     * @return RedirectResponse
     */
    public function destroy(Medicamento $medicamento): RedirectResponse
    {
        // Verificar si está en recetas activas
        $enUso = $medicamento->recetas()
            ->where('estado', 'Pendiente')
            ->count();

        if ($enUso > 0) {
            return back()->withErrors([
                'error' => 'No se puede eliminar el medicamento porque está en recetas activas'
            ]);
        }

        $medicamento->delete();

        return redirect()->route('admin.medicamentos.index')
            ->with('success', 'Medicamento eliminado exitosamente');
    }
}