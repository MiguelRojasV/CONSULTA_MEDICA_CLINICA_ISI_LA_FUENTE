{{-- ============================================ --}}
{{-- resources/views/admin/reportes/medicos.blade.php --}}
{{-- Reporte de Médicos --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Reporte de Médicos')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Médicos</h1>
            <p class="text-gray-600 mt-2">Estadísticas y desempeño del cuerpo médico</p>
        </div>
        <a href="{{ route('admin.reportes.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.reportes.medicos') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        
        <div class="flex items-end">
            <button type="submit" class="w-full bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-filter mr-2"></i>Aplicar Filtros
            </button>
        </div>
    </form>
</div>

{{-- Estadísticas por Médico --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
        Desempeño por Médico
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Citas</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Atendidas</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Recetas</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Experiencia</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($medicos as $medico)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                    <i class="fas fa-user-md text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dr(a). {{ $medico->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500">{{ $medico->matricula }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $medico->especialidad->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-bold rounded bg-blue-100 text-blue-800">
                                {{ $medico->citas_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-bold rounded bg-green-100 text-green-800">
                                {{ $medico->citas_atendidas_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-bold rounded bg-orange-100 text-orange-800">
                                {{ $medico->recetas_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ $medico->años_experiencia }} años
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $medico->estado == 'Activo' ? 'bg-green-100 text-green-800' : 
                                   ($medico->estado == 'Inactivo' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $medico->estado }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-user-md-slash text-4xl mb-3 block"></i>
                            No se encontraron médicos con los filtros aplicados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Top 5 Médicos Más Activos --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-trophy text-yellow-600 mr-2"></i>
        Top 5 Médicos Más Activos (Mes Actual)
    </h2>
    <div class="space-y-3">
        @foreach($medicos->sortByDesc('citas_atendidas_count')->take(5) as $index => $medico)
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-50 to-white rounded-lg border-l-4 border-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-500">
                <div class="flex items-center space-x-4">
                    <div class="bg-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-100 rounded-full w-12 h-12 flex items-center justify-center">
                        <span class="text-2xl font-bold text-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'orange') }}-600">
                            {{ $index + 1 }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Dr(a). {{ $medico->nombre_completo }}</p>
                        <p class="text-xs text-gray-600">{{ $medico->especialidad->nombre }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600">{{ $medico->citas_atendidas_count }}</p>
                    <p class="text-xs text-gray-500">citas atendidas</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
