{{-- ============================================ --}}
{{-- resources/views/admin/reportes/pacientes.blade.php --}}
{{-- Reporte de Pacientes --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Reporte de Pacientes')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Pacientes</h1>
            <p class="text-gray-600 mt-2">Estadísticas y análisis de pacientes registrados</p>
        </div>
        <a href="{{ route('admin.reportes.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.reportes.pacientes') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>Mes de Registro
            </label>
            <select name="mes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los meses</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('mes') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-alt mr-1"></i>Año
            </label>
            <select name="anio" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}" {{ request('anio', now()->year) == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
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
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tint mr-1"></i>Grupo Sanguíneo
            </label>
            <select name="grupo_sanguineo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
        
        <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
        </div>
    </form>
</div>

{{-- Estadísticas Generales --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Pacientes</p>
                <p class="text-4xl font-bold mt-2">{{ $totalPacientes }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-4xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Nuevos Este Mes</p>
                <p class="text-4xl font-bold mt-2">{{ $pacientesEsteMes }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-user-plus text-4xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Con Alergias</p>
                <p class="text-4xl font-bold mt-2">
                    {{ \App\Models\Paciente::whereNotNull('alergias')->where('alergias', '!=', '')->count() }}
                </p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-allergies text-4xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Distribución por Género --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-venus-mars text-blue-600 mr-2"></i>
            Distribución por Género
        </h2>
        <div class="space-y-4">
            @foreach($porGenero as $genero => $cantidad)
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-{{ $genero == 'Masculino' ? 'mars' : ($genero == 'Femenino' ? 'venus' : 'genderless') }} mr-2
                           text-{{ $genero == 'Masculino' ? 'blue' : ($genero == 'Femenino' ? 'pink' : 'gray') }}-600"></i>
                        {{ $genero }}
                    </span>
                    <span class="text-sm font-bold text-gray-900">
                        {{ $cantidad }} ({{ $totalPacientes > 0 ? number_format(($cantidad / $totalPacientes) * 100, 1) : 0 }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-{{ $genero == 'Masculino' ? 'blue' : ($genero == 'Femenino' ? 'pink' : 'gray') }}-600 h-3 rounded-full transition-all duration-500"
                         style="width: {{ $totalPacientes > 0 ? ($cantidad / $totalPacientes) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Grupos Sanguíneos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-tint text-red-600 mr-2"></i>
            Grupos Sanguíneos
        </h2>
        <div class="grid grid-cols-2 gap-3">
            @php
                $gruposSanguineos = \App\Models\Paciente::whereNotNull('grupo_sanguineo')
                    ->select('grupo_sanguineo', \DB::raw('count(*) as total'))
                    ->groupBy('grupo_sanguineo')
                    ->orderBy('total', 'desc')
                    ->get();
            @endphp
            @foreach($gruposSanguineos as $grupo)
            <div class="bg-red-50 border border-red-200 p-3 rounded-lg text-center">
                <p class="text-2xl font-bold text-red-600">{{ $grupo->grupo_sanguineo }}</p>
                <p class="text-sm text-gray-600">{{ $grupo->total }} pacientes</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabla de Pacientes --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-list text-purple-600 mr-2"></i>
        Lista de Pacientes
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CI</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Edad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Género</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo Sang.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Citas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pacientes as $paciente)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $paciente->nombre_completo }}</p>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                        {{ $paciente->grupo_sanguineo ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $paciente->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded bg-purple-100 text-purple-800">
                            {{ $paciente->citas->count() }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $pacientes->links() }}
    </div>
</div>
@endsection