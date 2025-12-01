{{-- ============================================ --}}
{{-- resources/views/medico/citas/index.blade.php --}}
{{-- Agenda de Citas del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mi Agenda')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-calendar-alt mr-3"></i>Mi Agenda
    </h1>
    <p class="text-gray-600 mt-2">Gestione sus citas y atenciones médicas</p>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="{{ route('medico.citas.index') }}" method="GET" class="flex items-end space-x-4">
        <div class="flex-1">
            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>Fecha
            </label>
            <input type="date" 
                   id="fecha" 
                   name="fecha" 
                   value="{{ $fecha }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>
        
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-search mr-2"></i>Buscar
        </button>

        <a href="{{ route('medico.citas.index') }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-sync-alt mr-2"></i>Hoy
        </a>
    </form>
</div>

{{-- Resumen del día --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
        <p class="text-blue-800 font-semibold text-lg">{{ $citas->count() }}</p>
        <p class="text-blue-600 text-sm">Total del día</p>
    </div>
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
        <p class="text-yellow-800 font-semibold text-lg">
            {{ $citas->where('estado', 'Programada')->count() }}
        </p>
        <p class="text-yellow-600 text-sm">Programadas</p>
    </div>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
        <p class="text-green-800 font-semibold text-lg">
            {{ $citas->where('estado', 'Atendida')->count() }}
        </p>
        <p class="text-green-600 text-sm">Atendidas</p>
    </div>
    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r">
        <p class="text-purple-800 font-semibold text-lg">
            {{ $citas->where('estado', 'Confirmada')->count() }}
        </p>
        <p class="text-purple-600 text-sm">Confirmadas</p>
    </div>
</div>

{{-- Lista de Citas --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        Citas del {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
    </h2>

    @if($citas->count() > 0)
        <div class="space-y-4">
            @foreach($citas as $cita)
                <div class="border-l-4 
                    {{ $cita->estado == 'Programada' ? 'border-yellow-500 bg-yellow-50' : 
                       ($cita->estado == 'Confirmada' ? 'border-blue-500 bg-blue-50' : 
                       ($cita->estado == 'Atendida' ? 'border-green-500 bg-green-50' : 
                       ($cita->estado == 'En Consulta' ? 'border-purple-500 bg-purple-50' : 
                       'border-gray-500 bg-gray-50'))) }}
                    p-4 rounded-r hover:shadow-md transition">
                    
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-2xl font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $cita->estado == 'Programada' ? 'bg-yellow-200 text-yellow-800' : 
                                       ($cita->estado == 'Confirmada' ? 'bg-blue-200 text-blue-800' : 
                                       ($cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 
                                       ($cita->estado == 'En Consulta' ? 'bg-purple-200 text-purple-800' : 
                                       'bg-gray-200 text-gray-800'))) }}">
                                    {{ $cita->estado }}
                                </span>
                                @if($cita->tipo_cita)
                                    <span class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                                        {{ $cita->tipo_cita }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <p class="font-semibold text-gray-800 text-lg">
                                        <i class="fas fa-user mr-2 text-gray-600"></i>
                                        {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-id-card mr-2"></i>CI: {{ $cita->paciente->ci }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ $cita->paciente->telefono ?? 'No disponible' }}
                                    </p>
                                </div>

                                <div>
                                    @if($cita->motivo)
                                        <p class="text-sm text-gray-700">
                                            <i class="fas fa-notes-medical mr-2 text-blue-600"></i>
                                            <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                    @endif
                                    @if($cita->duracion_estimada)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-2"></i>
                                            Duración: {{ $cita->duracion_estimada }} min
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex md:flex-col space-x-2 md:space-x-0 md:space-y-2 mt-4 md:mt-0 md:ml-4">
                            <a href="{{ route('medico.citas.show', $cita) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-center text-sm">
                                <i class="fas fa-eye mr-2"></i>Ver
                            </a>
                            
                            @if($cita->estado !== 'Atendida' && $cita->estado !== 'Cancelada')
                                <a href="{{ route('medico.citas.edit', $cita) }}" 
                                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-center text-sm">
                                    <i class="fas fa-user-md mr-2"></i>Atender
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-calendar-times text-6xl mb-4"></i>
            <p class="text-lg font-semibold">No hay citas programadas para esta fecha</p>
            <p class="text-sm mt-2">Seleccione otra fecha para ver más citas</p>
        </div>
    @endif
</div>
@endsection