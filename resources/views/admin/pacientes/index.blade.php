{{-- ============================================ --}}
{{-- 1. resources/views/admin/pacientes/index.blade.php --}}
{{-- Lista de Pacientes con Búsqueda y Filtros --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Gestión de Pacientes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Pacientes</h1>
        <p class="text-gray-600 mt-2">Administrar pacientes registrados en el sistema</p>
    </div>
    <a href="{{ route('admin.pacientes.create') }}" 
       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>Nuevo Paciente
    </a>
</div>

{{-- Filtros y Búsqueda --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.pacientes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar
            </label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="CI, nombre o apellido"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-venus-mars mr-1"></i>Género
            </label>
            <select name="genero" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex-1">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.pacientes.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

{{-- Tabla de Pacientes --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CI</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Género</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pacientes as $paciente)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $paciente->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $paciente->ci }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $paciente->edad }} años</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $paciente->genero == 'Masculino' ? 'bg-blue-100 text-blue-800' : 
                               ($paciente->genero == 'Femenino' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $paciente->genero }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $paciente->telefono }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $paciente->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.pacientes.show', $paciente) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.pacientes.edit', $paciente) }}" 
                           class="text-yellow-600 hover:text-yellow-900 mr-3" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.pacientes.destroy', $paciente) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar este paciente?')">
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
                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                        No se encontraron pacientes
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{-- Paginación --}}
    <div class="px-6 py-4 bg-gray-50">
        {{ $pacientes->links() }}
    </div>
</div>
@endsection