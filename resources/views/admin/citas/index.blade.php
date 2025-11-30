{{-- ============================================ --}}
{{-- 1. resources/views/admin/citas/index.blade.php --}}
{{-- Lista de Citas con Filtros --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Gestión de Citas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Citas</h1>
        <p class="text-gray-600 mt-2">Administrar citas médicas del sistema</p>
    </div>
    <a href="{{ route('admin.citas.create') }}" 
       class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Nueva Cita
    </a>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.citas.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar Paciente
            </label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="CI, nombre o apellido"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>Fecha
            </label>
            <input type="date" name="fecha" value="{{ request('fecha') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user-md mr-1"></i>Médico
            </label>
            <select name="medico_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Todos</option>
                @foreach($medicos as $medico)
                    <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                        Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-toggle-on mr-1"></i>Estado
            </label>
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Todos</option>
                <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programada</option>
                <option value="Confirmada" {{ request('estado') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="En Consulta" {{ request('estado') == 'En Consulta' ? 'selected' : '' }}>En Consulta</option>
                <option value="Atendida" {{ request('estado') == 'Atendida' ? 'selected' : '' }}>Atendida</option>
                <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition flex-1">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.citas.index') }}" 
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($citas as $cita)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $cita->fecha->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $cita->hora->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $cita->paciente->nombre_completo }}</p>
                                <p class="text-xs text-gray-500">CI: {{ $cita->paciente->ci }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">Dr(a). {{ $cita->medico->nombre }} {{ $cita->medico->apellido }}</div>
                        <div class="text-xs text-gray-500">{{ $cita->medico->especialidad->nombre }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">{{ Str::limit($cita->motivo, 50) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $cita->estado == 'Atendida' ? 'bg-green-100 text-green-800' : 
                               ($cita->estado == 'Cancelada' ? 'bg-red-100 text-red-800' : 
                               ($cita->estado == 'Confirmada' ? 'bg-blue-100 text-blue-800' : 
                               ($cita->estado == 'En Consulta' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                            {{ $cita->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.citas.show', $cita) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.citas.edit', $cita) }}" 
                           class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($cita->estado != 'Atendida')
                        <form action="{{ route('admin.citas.destroy', $cita) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar esta cita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                        No se encontraron citas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $citas->links() }}
    </div>
</div>
@endsection
