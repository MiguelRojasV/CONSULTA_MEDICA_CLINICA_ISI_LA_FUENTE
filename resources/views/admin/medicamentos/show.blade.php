{{-- ============================================ --}}
{{-- resources/views/admin/medicamentos/show.blade.php --}}
{{-- Detalles del Medicamento --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Detalles del Medicamento')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $medicamento->nombre_completo }}</h1>
            <p class="text-gray-600 mt-2">{{ $medicamento->laboratorio }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.medicamentos.edit', $medicamento) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.medicamentos.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

{{-- Estado del Stock --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Stock Disponible</p>
                <p class="text-3xl font-bold mt-2
                    {{ $medicamento->disponibilidad == 0 ? 'text-red-600' : 
                       ($medicamento->stockBajo() ? 'text-orange-600' : 'text-green-600') }}">
                    {{ $medicamento->disponibilidad }}
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ $medicamento->nivelStock() }}</p>
            </div>
            <div class="bg-{{ $medicamento->disponibilidad == 0 ? 'red' : ($medicamento->stockBajo() ? 'orange' : 'green') }}-100 rounded-full p-4">
                <i class="fas fa-boxes text-{{ $medicamento->disponibilidad == 0 ? 'red' : ($medicamento->stockBajo() ? 'orange' : 'green') }}-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Stock Mínimo</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $medicamento->stock_minimo }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Precio Unitario</p>
                <p class="text-3xl font-bold text-green-600 mt-2">Bs. {{ number_format($medicamento->precio_unitario, 2) }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Valor Total</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">Bs. {{ number_format($medicamento->valorInventario(), 2) }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-calculator text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Información del Medicamento --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-pills text-orange-600 mr-2"></i>
            Información del Medicamento
        </h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Nombre Genérico:</span>
                <span class="text-gray-800">{{ $medicamento->nombre_generico }}</span>
            </div>
            @if($medicamento->nombre_comercial)
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Nombre Comercial:</span>
                <span class="text-gray-800">{{ $medicamento->nombre_comercial }}</span>
            </div>
            @endif
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Tipo:</span>
                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">{{ $medicamento->tipo }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Presentación:</span>
                <span class="text-gray-800">{{ $medicamento->presentacion }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Concentración:</span>
                <span class="text-gray-800">{{ $medicamento->concentracion }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Vía Administración:</span>
                <span class="text-gray-800">{{ $medicamento->via_administracion }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Laboratorio:</span>
                <span class="text-gray-800">{{ $medicamento->laboratorio }}</span>
            </div>
            @if($medicamento->lote)
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Lote:</span>
                <span class="text-gray-800">{{ $medicamento->lote }}</span>
            </div>
            @endif
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Requiere Receta:</span>
                <span class="px-2 py-1 {{ $medicamento->requiere_receta ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded text-xs font-bold">
                    {{ $medicamento->requiere_receta ? 'SÍ' : 'NO' }}
                </span>
            </div>
            @if($medicamento->caducidad)
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="font-semibold text-gray-600">Caducidad:</span>
                <span class="font-bold
                    {{ $medicamento->estaVencido() ? 'text-red-600' : ($medicamento->estaPorVencer() ? 'text-yellow-600' : 'text-green-600') }}">
                    {{ $medicamento->caducidad->format('d/m/Y') }}
                    ({{ $medicamento->estadoCaducidad() }})
                </span>
            </div>
            @endif
        </div>

        @if($medicamento->contraindicaciones)
        <div class="mt-4 bg-red-50 border border-red-200 p-4 rounded">
            <p class="font-semibold text-red-800 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>Contraindicaciones
            </p>
            <p class="text-sm text-red-700">{{ $medicamento->contraindicaciones }}</p>
        </div>
        @endif
    </div>

    {{-- Estadísticas de Uso --}}
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Estadísticas de Uso
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="text-sm text-gray-600">Total Recetas:</span>
                    <span class="text-lg font-bold text-gray-800">{{ $totalRecetas }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="text-sm text-gray-600">Total Dispensado:</span>
                    <span class="text-lg font-bold text-gray-800">{{ $totalDispensado }} unidades</span>
                </div>
            </div>
        </div>

        {{-- Recetas Recientes --}}
        @if($recetas->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-prescription text-purple-600 mr-2"></i>
                Recetas Recientes
            </h2>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($recetas as $receta)
                <div class="border-b border-gray-200 pb-2">
                    <p class="text-sm font-medium text-gray-800">
                        {{ $receta->fecha_emision->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-600">
                        Paciente: {{ $receta->paciente->nombre_completo }}
                    </p>
                    <p class="text-xs text-gray-600">
                        Dr(a). {{ $receta->medico->nombre_completo }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection