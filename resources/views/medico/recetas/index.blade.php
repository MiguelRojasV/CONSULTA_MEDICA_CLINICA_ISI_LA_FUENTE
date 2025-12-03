{{-- ============================================ --}}
{{-- resources/views/medico/recetas/index.blade.php --}}
{{-- Lista de Recetas Emitidas por el Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mis Recetas')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-prescription mr-3"></i>Mis Recetas Médicas
            </h1>
            <p class="text-gray-600 mt-2">Gestione las recetas que ha emitido</p>
        </div>
        <a href="{{ route('medico.recetas.create') }}" 
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
            <i class="fas fa-plus mr-2"></i>Nueva Receta
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="{{ route('medico.recetas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar Paciente
            </label>
            <input type="text" 
                   id="buscar" 
                   name="buscar" 
                   value="{{ request('buscar') }}"
                   placeholder="Nombre, apellido o CI..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>

        <div>
            <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-alt mr-1"></i>Desde
            </label>
            <input type="date" 
                   id="fecha_desde" 
                   name="fecha_desde" 
                   value="{{ request('fecha_desde') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>

        <div>
            <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-check mr-1"></i>Hasta
            </label>
            <input type="date" 
                   id="fecha_hasta" 
                   name="fecha_hasta" 
                   value="{{ request('fecha_hasta') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>

        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-1"></i>Estado
            </label>
            <select id="estado" 
                    name="estado"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Todos</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Dispensada" {{ request('estado') == 'Dispensada' ? 'selected' : '' }}>Dispensada</option>
                <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <div class="md:col-span-4 flex space-x-2">
            <button type="submit" 
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
            <a href="{{ route('medico.recetas.index') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-times mr-2"></i>Limpiar
            </a>
        </div>
    </form>
</div>

{{-- Resumen --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
        <p class="text-blue-800 font-semibold text-lg">{{ $recetas->total() }}</p>
        <p class="text-blue-600 text-sm">Total Recetas</p>
    </div>
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
        <p class="text-yellow-800 font-semibold text-lg">
            {{ $recetas->where('estado', 'Pendiente')->count() }}
        </p>
        <p class="text-yellow-600 text-sm">Pendientes</p>
    </div>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
        <p class="text-green-800 font-semibold text-lg">
            {{ $recetas->where('estado', 'Dispensada')->count() }}
        </p>
        <p class="text-green-600 text-sm">Dispensadas</p>
    </div>
    <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded-r">
        <p class="text-gray-800 font-semibold text-lg">
            {{ $recetas->where('estado', 'Cancelada')->count() }}
        </p>
        <p class="text-gray-600 text-sm">Canceladas</p>
    </div>
</div>

{{-- Lista de Recetas --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        Recetas Emitidas
    </h2>

    @if($recetas->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paciente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Medicamentos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recetas as $receta)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-800">#{{ $receta->id }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-800">
                                    {{ $receta->fecha_emision->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $receta->fecha_emision->diffForHumans() }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-800">
                                    {{ $receta->paciente->nombre }} {{ $receta->paciente->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-id-card mr-1"></i>CI: {{ $receta->paciente->ci }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-capsules text-orange-600 mr-2"></i>
                                    <span class="text-sm text-gray-800">
                                        {{ $receta->medicamentos->count() }} 
                                        medicamento{{ $receta->medicamentos->count() != 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $receta->estado == 'Pendiente' ? 'bg-yellow-200 text-yellow-800' : 
                                       ($receta->estado == 'Dispensada' ? 'bg-green-200 text-green-800' : 
                                       'bg-gray-200 text-gray-800') }}">
                                    {{ $receta->estado }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('medico.recetas.show', $receta) }}" 
                                       class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medico.recetas.pdf', $receta) }}" 
                                       class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-sm"
                                       target="_blank"
                                       title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $recetas->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-prescription text-6xl mb-4"></i>
            <p class="text-lg font-semibold">
                @if(request()->hasAny(['buscar', 'fecha_desde', 'fecha_hasta', 'estado']))
                    No se encontraron recetas con los filtros aplicados
                @else
                    No hay recetas registradas este mes
                @endif
            </p>
            <a href="{{ route('medico.recetas.create') }}" 
               class="inline-block mt-4 bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                <i class="fas fa-plus mr-2"></i>Crear Primera Receta
            </a>
        </div>
    @endif
</div>
@endsection