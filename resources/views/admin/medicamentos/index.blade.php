{{-- ============================================ --}}
{{-- resources/views/admin/medicamentos/index.blade.php --}}
{{-- Lista de Medicamentos (Inventario) --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Inventario de Medicamentos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Inventario de Medicamentos</h1>
        <p class="text-gray-600 mt-2">Administrar stock y medicamentos disponibles</p>
    </div>
    <a href="{{ route('admin.medicamentos.create') }}" 
       class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Nuevo Medicamento
    </a>
</div>

{{-- Alertas de Stock --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
        <div class="flex items-center">
            <i class="fas fa-times-circle text-red-600 text-2xl mr-3"></i>
            <div>
                <p class="font-semibold text-red-800">Sin Stock</p>
                <p class="text-sm text-red-600">Ver medicamentos agotados</p>
            </div>
        </div>
    </div>
    
    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-orange-600 text-2xl mr-3"></i>
            <div>
                <p class="font-semibold text-orange-800">Stock Bajo</p>
                <p class="text-sm text-orange-600">Ver medicamentos críticos</p>
            </div>
        </div>
    </div>
    
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
        <div class="flex items-center">
            <i class="fas fa-calendar-times text-yellow-600 text-2xl mr-3"></i>
            <div>
                <p class="font-semibold text-yellow-800">Por Vencer</p>
                <p class="text-sm text-yellow-600">Próximos 30 días</p>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded-r">
        <div class="flex items-center">
            <i class="fas fa-ban text-gray-600 text-2xl mr-3"></i>
            <div>
                <p class="font-semibold text-gray-800">Vencidos</p>
                <p class="text-sm text-gray-600">Dar de baja</p>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.medicamentos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar
            </label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Nombre o laboratorio"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tag mr-1"></i>Tipo
            </label>
            <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                <option value="">Todos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-boxes mr-1"></i>Stock
            </label>
            <select name="stock" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                <option value="">Todos</option>
                <option value="sin_stock" {{ request('stock') == 'sin_stock' ? 'selected' : '' }}>Sin Stock</option>
                <option value="critico" {{ request('stock') == 'critico' ? 'selected' : '' }}>Crítico (&lt; 5)</option>
                <option value="bajo" {{ request('stock') == 'bajo' ? 'selected' : '' }}>Stock Bajo</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>Vencimiento
            </label>
            <select name="vencimiento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                <option value="">Todos</option>
                <option value="vencidos" {{ request('vencimiento') == 'vencidos' ? 'selected' : '' }}>Vencidos</option>
                <option value="por_vencer" {{ request('vencimiento') == 'por_vencer' ? 'selected' : '' }}>Por Vencer (30 días)</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition flex-1">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.medicamentos.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presentación</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caducidad</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($medicamentos as $medicamento)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-orange-100 rounded-full p-2 mr-3">
                                <i class="fas fa-pills text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_generico }}</p>
                                @if($medicamento->nombre_comercial)
                                <p class="text-xs text-gray-500">({{ $medicamento->nombre_comercial }})</p>
                                @endif
                                <p class="text-xs text-gray-500">{{ $medicamento->laboratorio }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $medicamento->tipo ?? 'No especificado' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $medicamento->presentacion }}<br>
                        <span class="text-xs text-gray-500">{{ $medicamento->concentracion }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($medicamento->disponibilidad == 0)
                                <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">
                                    SIN STOCK
                                </span>
                            @elseif($medicamento->disponibilidad < 5)
                                <span class="px-2 py-1 text-xs font-bold rounded bg-orange-100 text-orange-800">
                                    {{ $medicamento->disponibilidad }} (CRÍTICO)
                                </span>
                            @elseif($medicamento->disponibilidad < $medicamento->stock_minimo)
                                <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">
                                    {{ $medicamento->disponibilidad }} (BAJO)
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">
                                    {{ $medicamento->disponibilidad }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Mín: {{ $medicamento->stock_minimo }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Bs. {{ number_format($medicamento->precio_unitario, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($medicamento->caducidad)
                            @if($medicamento->caducidad->isPast())
                                <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">
                                    VENCIDO
                                </span>
                            @elseif($medicamento->caducidad->diffInDays(now()) <= 30)
                                <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">
                                    {{ $medicamento->caducidad->diffInDays(now()) }} días
                                </span>
                            @else
                                <span class="text-sm text-gray-900">{{ $medicamento->caducidad->format('d/m/Y') }}</span>
                            @endif
                        @else
                            <span class="text-xs text-gray-500">No especificada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.medicamentos.show', $medicamento) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.medicamentos.edit', $medicamento) }}" 
                           class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.medicamentos.destroy', $medicamento) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar este medicamento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-pills text-4xl mb-3 block"></i>
                        No se encontraron medicamentos
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $medicamentos->links() }}
    </div>
</div>
@endsection