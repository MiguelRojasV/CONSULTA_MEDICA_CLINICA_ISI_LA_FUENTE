{{-- ============================================ --}}
{{-- resources/views/admin/reportes/medicamentos.blade.php --}}
{{-- Reporte de Inventario de Medicamentos --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Reporte de Medicamentos')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Inventario de Medicamentos</h1>
            <p class="text-gray-600 mt-2">Control de stock, alertas y valorización</p>
        </div>
        <a href="{{ route('admin.reportes.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

{{-- Alertas Críticas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm">Sin Stock</p>
                <p class="text-4xl font-bold mt-2">{{ $sinStock }}</p>
                <p class="text-red-100 text-xs mt-2">Requieren reabastecimiento</p>
            </div>
            <div class="bg-red-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-times-circle text-4xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Stock Bajo</p>
                <p class="text-4xl font-bold mt-2">{{ $stockBajo }}</p>
                <p class="text-orange-100 text-xs mt-2">Por debajo del mínimo</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-exclamation-triangle text-4xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Por Vencer</p>
                <p class="text-4xl font-bold mt-2">{{ $porVencer }}</p>
                <p class="text-yellow-100 text-xs mt-2">Próximos 30 días</p>
            </div>
            <div class="bg-yellow-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-times text-4xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-100 text-sm">Vencidos</p>
                <p class="text-4xl font-bold mt-2">{{ $vencidos }}</p>
                <p class="text-gray-100 text-xs mt-2">Dar de baja</p>
            </div>
            <div class="bg-gray-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-ban text-4xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Valorización del Inventario --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
        Valorización del Inventario
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r">
            <p class="text-green-800 font-semibold text-sm mb-2">VALOR TOTAL DEL INVENTARIO</p>
            <p class="text-3xl font-bold text-green-600">Bs. {{ number_format($valorInventario, 2) }}</p>
            <p class="text-xs text-green-600 mt-2">Basado en precio unitario × stock disponible</p>
        </div>
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r">
            <p class="text-blue-800 font-semibold text-sm mb-2">TOTAL MEDICAMENTOS</p>
            <p class="text-3xl font-bold text-blue-600">{{ $medicamentos->count() }}</p>
            <p class="text-xs text-blue-600 mt-2">Productos diferentes en inventario</p>
        </div>
        
        <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded-r">
            <p class="text-purple-800 font-semibold text-sm mb-2">UNIDADES TOTALES</p>
            <p class="text-3xl font-bold text-purple-600">{{ $medicamentos->sum('disponibilidad') }}</p>
            <p class="text-xs text-purple-600 mt-2">Stock total disponible</p>
        </div>
    </div>
</div>

{{-- Medicamentos Sin Stock --}}
@if($sinStock > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-times-circle text-red-600 mr-2"></i>
        Medicamentos Sin Stock ({{ $sinStock }})
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Presentación</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock Mínimo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($medicamentos->where('disponibilidad', 0) as $medicamento)
                <tr class="bg-red-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_generico }}</p>
                        @if($medicamento->nombre_comercial)
                        <p class="text-xs text-gray-500">({{ $medicamento->nombre_comercial }})</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $medicamento->tipo }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $medicamento->presentacion }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-800">
                            {{ $medicamento->stock_minimo }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">
                            SIN STOCK
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Medicamentos con Stock Bajo --}}
@if($stockBajo > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
        Medicamentos con Stock Bajo ({{ $stockBajo }})
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock Actual</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock Mínimo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Faltante</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($medicamentos->filter(function($m) { return $m->stockBajo(); }) as $medicamento)
                <tr class="bg-orange-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_generico }}</p>
                        @if($medicamento->nombre_comercial)
                        <p class="text-xs text-gray-500">({{ $medicamento->nombre_comercial }})</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $medicamento->tipo }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-orange-200 text-orange-800">
                            {{ $medicamento->disponibilidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-800">
                            {{ $medicamento->stock_minimo }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-200 text-red-800">
                            {{ $medicamento->stock_minimo - $medicamento->disponibilidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-orange-100 text-orange-800">
                            STOCK BAJO
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Medicamentos Por Vencer --}}
@if($porVencer > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-calendar-times text-yellow-600 mr-2"></i>
        Medicamentos Por Vencer ({{ $porVencer }})
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Caducidad</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Días Restantes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($medicamentos->filter(function($m) { return $m->estaPorVencer(); }) as $medicamento)
                <tr class="bg-yellow-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_generico }}</p>
                        @if($medicamento->nombre_comercial)
                        <p class="text-xs text-gray-500">({{ $medicamento->nombre_comercial }})</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $medicamento->lote ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-blue-200 text-blue-800">
                            {{ $medicamento->disponibilidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                        {{ $medicamento->caducidad->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-200 text-yellow-800">
                            {{ $medicamento->caducidad->diffInDays(now()) }} días
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Medicamentos Vencidos --}}
@if($vencidos > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-ban text-gray-600 mr-2"></i>
        Medicamentos Vencidos ({{ $vencidos }})
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Caducidad</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($medicamentos->filter(function($m) { return $m->estaVencido(); }) as $medicamento)
                <tr class="bg-gray-100">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $medicamento->nombre_generico }}</p>
                        @if($medicamento->nombre_comercial)
                        <p class="text-xs text-gray-500">({{ $medicamento->nombre_comercial }})</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $medicamento->lote ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-gray-300 text-gray-800">
                            {{ $medicamento->disponibilidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium text-red-600">
                        {{ $medicamento->caducidad->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">
                            VENCIDO
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Resumen por Tipo de Medicamento --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
        Distribución por Tipo de Medicamento
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($medicamentos->groupBy('tipo') as $tipo => $meds)
        <div class="border border-gray-200 p-4 rounded-lg hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $tipo ?? 'Sin clasificar' }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $meds->count() }} medicamentos</p>
                    <p class="text-xs text-gray-600">Stock: {{ $meds->sum('disponibilidad') }} unidades</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-pills text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection