{{-- ============================================ --}}
{{-- resources/views/paciente/recetas/index.blade.php --}}
{{-- Vista: Lista de Recetas del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Mis Recetas Médicas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Mis Recetas Médicas</h1>
    <p class="text-gray-600 mt-2">Consulte y descargue sus recetas emitidas</p>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form action="{{ route('paciente.recetas.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Todos los estados</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Dispensada" {{ request('estado') == 'Dispensada' ? 'selected' : '' }}>Dispensada</option>
                <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-filter mr-2"></i>Filtrar
        </button>
        <a href="{{ route('paciente.recetas.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-redo mr-2"></i>Limpiar
        </a>
    </form>
</div>

{{-- Estadísticas rápidas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-prescription text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Recetas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetas->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pendientes</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetas->where('estado', 'Pendiente')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Dispensadas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetas->where('estado', 'Dispensada')->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Lista de recetas --}}
@if($recetas->count() > 0)
    <div class="space-y-4">
        @foreach($recetas as $receta)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <span class="text-lg font-bold text-gray-800">
                                <i class="fas fa-calendar mr-2 text-purple-600"></i>
                                {{ $receta->fecha_emision->format('d/m/Y') }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $receta->estado == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($receta->estado == 'Dispensada' ? 'bg-green-100 text-green-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $receta->estado }}
                            </span>
                            @if($receta->valida_hasta)
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-hourglass-half mr-1"></i>
                                    Válida hasta: {{ $receta->valida_hasta->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Médico que prescribe</p>
                                <p class="font-semibold text-gray-800">
                                    <i class="fas fa-user-md mr-2 text-blue-600"></i>
                                    Dr(a). {{ $receta->medico->nombre }} {{ $receta->medico->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $receta->medico->especialidad->nombre }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600 mb-1">Medicamentos</p>
                                <p class="font-semibold text-gray-800">
                                    <i class="fas fa-pills mr-2 text-green-600"></i>
                                    {{ $receta->medicamentos->count() }} medicamento(s)
                                </p>
                                @if($receta->medicamentos->count() > 0)
                                    <p class="text-sm text-gray-600">
                                        {{ $receta->medicamentos->first()->nombre_generico }}
                                        @if($receta->medicamentos->count() > 1)
                                            <span class="text-blue-600">+{{ $receta->medicamentos->count() - 1 }} más</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($receta->indicaciones)
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <p class="text-sm font-semibold text-blue-900 mb-1">
                                <i class="fas fa-info-circle mr-2"></i>Indicaciones
                            </p>
                            <p class="text-sm text-gray-800">{{ Str::limit($receta->indicaciones, 150) }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-2 ml-4">
                        <a href="{{ route('paciente.recetas.show', $receta) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm text-center whitespace-nowrap">
                            <i class="fas fa-eye mr-2"></i>Ver
                        </a>
                        <a href="{{ route('paciente.recetas.pdf', $receta) }}" 
                           target="_blank"
                           class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm text-center whitespace-nowrap">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {{ $recetas->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-prescription text-gray-300 text-6xl mb-4"></i>
        <p class="text-gray-500 text-lg mb-2">No tiene recetas médicas registradas</p>
        <p class="text-gray-400 text-sm">Las recetas se generan después de sus consultas médicas</p>
    </div>
@endif

{{-- Información adicional --}}
<div class="mt-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
    <p class="text-sm text-gray-700">
        <i class="fas fa-info-circle text-purple-600 mr-2"></i>
        <strong>Importante:</strong> Las recetas tienen una validez limitada. Verifique la fecha de vencimiento antes de acudir a farmacia.
    </p>
</div>

@endsection

{{-- 
CARACTERÍSTICAS:
1. Lista completa de recetas del paciente
2. Filtros por estado
3. Estadísticas visuales (total, pendientes, dispensadas)
4. Información del médico y medicamentos
6. Fecha de validez destacada
7. Botones para ver detalles y descargar PDF
8. Indicaciones resumidas
9. Paginación
10. Mensaje cuando no hay recetas
--}}