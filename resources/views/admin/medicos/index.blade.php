{{-- ============================================ --}}
{{-- 1. resources/views/admin/medicos/index.blade.php --}}
{{-- Lista de Médicos --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Gestión de Médicos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Médicos</h1>
        <p class="text-gray-600 mt-2">Administrar médicos del sistema</p>
    </div>
    <a href="{{ route('admin.medicos.create') }}" 
       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Nuevo Médico
    </a>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.medicos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar
            </label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Nombre, CI o matrícula"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-stethoscope mr-1"></i>Especialidad
            </label>
            <select name="especialidad_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Todas</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp->id }}" {{ request('especialidad_id') == $esp->id ? 'selected' : '' }}>
                        {{ $esp->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-toggle-on mr-1"></i>Estado
            </label>
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Todos</option>
                <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="Licencia" {{ request('estado') == 'Licencia' ? 'selected' : '' }}>Licencia</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-clock mr-1"></i>Turno
            </label>
            <select name="turno" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Todos</option>
                <option value="Mañana" {{ request('turno') == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                <option value="Tarde" {{ request('turno') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                <option value="Noche" {{ request('turno') == 'Noche' ? 'selected' : '' }}>Noche</option>
                <option value="Rotativo" {{ request('turno') == 'Rotativo' ? 'selected' : '' }}>Rotativo</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex-1">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.medicos.index') }}" 
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matrícula</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turno</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultorio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($medicos as $medico)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-full p-2 mr-3">
                                <i class="fas fa-user-md text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}</p>
                                <p class="text-xs text-gray-500">CI: {{ $medico->ci }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $medico->especialidad->nombre }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $medico->matricula }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $medico->turno ?? 'No asignado' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $medico->consultorio ?? 'No asignado' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $medico->estado == 'Activo' ? 'bg-green-100 text-green-800' : 
                               ($medico->estado == 'Inactivo' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $medico->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.medicos.show', $medico) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.medicos.edit', $medico) }}" 
                           class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.medicos.destroy', $medico) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar este médico?')">
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
                        <i class="fas fa-user-md-slash text-4xl mb-3 block"></i>
                        No se encontraron médicos
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $medicos->links() }}
    </div>
</div>
@endsection
