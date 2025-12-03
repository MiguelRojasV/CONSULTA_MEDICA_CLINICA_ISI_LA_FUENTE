{{-- ============================================ --}}
{{-- resources/views/medico/dashboard.blade.php --}}
{{-- Dashboard Principal del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Panel Médico')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        Bienvenido, Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
    </h1>
    <p class="text-gray-600 mt-2">
        <i class="fas fa-stethoscope mr-2"></i>{{ $medico->especialidad->nombre }}
        @if($medico->consultorio)
            <span class="mx-2">•</span>
            <i class="fas fa-door-open mr-2"></i>{{ $medico->consultorio }}
        @endif
    </p>
</div>

{{-- Tarjetas de estadísticas principales --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Citas de Hoy --}}
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Citas de Hoy</p>
                <p class="text-3xl font-bold mt-2">{{ $citasHoy->count() }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-day text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('medico.citas.index') }}" 
           class="block mt-4 text-blue-100 hover:text-white text-sm">
            Ver agenda →
        </a>
    </div>

    {{-- Total Citas --}}
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Citas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalCitas }}</p>
                <p class="text-green-100 text-xs mt-2">
                    {{ $citasAtendidas }} atendidas
                </p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-check text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Pacientes Atendidos --}}
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Pacientes</p>
                <p class="text-3xl font-bold mt-2">{{ $totalPacientes }}</p>
                <p class="text-purple-100 text-xs mt-2">Total atendidos</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('medico.pacientes.index') }}" 
           class="block mt-4 text-purple-100 hover:text-white text-sm">
            Ver pacientes →
        </a>
    </div>

    {{-- Recetas Este Mes --}}
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Recetas (Mes)</p>
                <p class="text-3xl font-bold mt-2">{{ $recetasEsteMes }}</p>
                <p class="text-orange-100 text-xs mt-2">
                    {{ $citasPendientes }} citas pendientes
                </p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-prescription text-3xl"></i>
            </div>
        </div>
        <a href="{{ route('medico.recetas.index') }}" 
           class="block mt-4 text-orange-100 hover:text-white text-sm">
            Ver recetas →
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Citas de Hoy --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                Agenda de Hoy
            </h2>
            <a href="{{ route('medico.citas.index') }}" 
               class="text-blue-600 hover:underline text-sm">
                Ver completa →
            </a>
        </div>

        @if($citasHoy->count() > 0)
            <div class="space-y-3">
                @foreach($citasHoy as $cita)
                    <div class="border-l-4 
                        {{ $cita->estado == 'Programada' ? 'border-yellow-500 bg-yellow-50' : 
                           ($cita->estado == 'Confirmada' ? 'border-blue-500 bg-blue-50' : 
                           ($cita->estado == 'Atendida' ? 'border-green-500 bg-green-50' : 
                           'border-gray-500 bg-gray-50')) }}
                        p-4 rounded-r hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-lg font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $cita->estado == 'Programada' ? 'bg-yellow-200 text-yellow-800' : 
                                           ($cita->estado == 'Confirmada' ? 'bg-blue-200 text-blue-800' : 
                                           ($cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 
                                           'bg-gray-200 text-gray-800')) }}">
                                        {{ $cita->estado }}
                                    </span>
                                </div>
                                <p class="font-semibold text-gray-800">
                                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-id-card mr-1"></i>CI: {{ $cita->paciente->ci }}
                                </p>
                                @if($cita->motivo)
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-notes-medical mr-1"></i>
                                        {{ Str::limit($cita->motivo, 60) }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('medico.citas.show', $cita) }}" 
                               class="ml-4 bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition text-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No tiene citas programadas para hoy</p>
            </div>
        @endif
    </div>

    {{-- Próximas Citas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clock text-purple-600 mr-2"></i>
                Próximas Citas
            </h2>
        </div>

        @if($proximasCitas->count() > 0)
            <div class="space-y-3">
                @foreach($proximasCitas as $cita)
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded-r hover:bg-blue-100 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $cita->fecha->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </p>
                                <p class="font-semibold text-gray-800">
                                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-200 text-blue-800">
                                    {{ $cita->estado }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-check text-4xl mb-3"></i>
                <p>No hay citas próximas programadas</p>
            </div>
        @endif
    </div>
</div>

{{-- Accesos rápidos --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <a href="{{ route('medico.citas.index') }}" 
       class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition border-l-4 border-blue-600">
        <div class="flex items-center">
            <div class="bg-blue-100 p-4 rounded-full mr-4">
                <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Mi Agenda</h3>
                <p class="text-gray-600 text-sm">Ver y gestionar citas</p>
            </div>
        </div>
    </a>

    <a href="{{ route('medico.pacientes.index') }}" 
       class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition border-l-4 border-green-600">
        <div class="flex items-center">
            <div class="bg-green-100 p-4 rounded-full mr-4">
                <i class="fas fa-users text-green-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Pacientes</h3>
                <p class="text-gray-600 text-sm">Ver historial médico</p>
            </div>
        </div>
    </a>

    <a href="{{ route('medico.recetas.create') }}" 
       class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition border-l-4 border-orange-600">
        <div class="flex items-center">
            <div class="bg-orange-100 p-4 rounded-full mr-4">
                <i class="fas fa-prescription text-orange-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Nueva Receta</h3>
                <p class="text-gray-600 text-sm">Emitir receta médica</p>
            </div>
        </div>
    </a>
</div>
@endsection