
@extends('layouts.medico')

@section('title', 'Mi Panel Médico')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">¡Bienvenido, Dr(a). {{ $medico->nombre }}!</h1>
    <p class="text-gray-600 mt-2">{{ $medico->especialidad }}</p>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    {{-- Citas de hoy --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Citas Hoy</p>
                <p class="text-2xl font-bold text-gray-800">{{ $citasHoy->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Total de citas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Citas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCitas }}</p>
            </div>
        </div>
    </div>

    {{-- Citas atendidas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Atendidas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $citasAtendidas }}</p>
            </div>
        </div>
    </div>

    {{-- Citas pendientes --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pendientes</p>
                <p class="text-2xl font-bold text-gray-800">{{ $citasPendientes }}</p>
            </div>
        </div>
    </div>

    {{-- Recetas este mes --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-red-100 rounded-full p-3">
                <i class="fas fa-prescription text-red-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Recetas (mes)</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetasEsteMes }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Agenda del día --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                    Agenda de Hoy - {{ now()->format('d/m/Y') }}
                </h2>
                <a href="{{ route('medico.citas.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm">
                    Ver agenda completa →
                </a>
            </div>

            @if($citasHoy->count() > 0)
                <div class="space-y-3">
                    @foreach($citasHoy as $cita)
                        <div class="border-l-4 
                            {{ $cita->estado == 'Programada' ? 'border-blue-500 bg-blue-50' : 
                               ($cita->estado == 'Confirmada' ? 'border-green-500 bg-green-50' : 
                               ($cita->estado == 'En Consulta' ? 'border-yellow-500 bg-yellow-50' : 
                               'border-purple-500 bg-purple-50')) }} 
                            p-4 rounded-r-lg hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="font-bold text-lg text-gray-800">
                                            {{ $cita->hora->format('H:i') }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $cita->estado == 'Programada' ? 'bg-blue-100 text-blue-800' : 
                                               ($cita->estado == 'Confirmada' ? 'bg-green-100 text-green-800' : 
                                               ($cita->estado == 'En Consulta' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-purple-100 text-purple-800')) }}">
                                            {{ $cita->estado }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ $cita->paciente->nombre }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        CI: {{ $cita->paciente->ci }} | Edad: {{ $cita->paciente->edad }} años
                                    </p>
                                    @if($cita->motivo)
                                        <p class="text-sm text-gray-700 mt-2">
                                            <strong>Motivo:</strong> {{ Str::limit($cita->motivo, 60) }}
                                        </p>
                                    @endif
                                    @if($cita->paciente->alergias)
                                        <div class="bg-red-100 border border-red-300 p-2 rounded mt-2">
                                            <p class="text-xs text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                <strong>Alergias:</strong> {{ $cita->paciente->alergias }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <a href="{{ route('medico.citas.show', $cita) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-eye mr-1"></i>Ver
                                    </a>
                                    @if($cita->estado != 'Atendida')
                                        <a href="{{ route('medico.citas.edit', $cita) }}" 
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-notes-medical mr-1"></i>Atender
                                        </a>
                                    @endif
                                </div>
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
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Accesos rápidos --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Accesos Rápidos
            </h2>
            <div class="space-y-3">
                <a href="{{ route('medico.citas.index') }}" 
                   class="block bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Ver Mi Agenda
                </a>
                <a href="{{ route('medico.recetas.create') }}" 
                   class="block bg-green-600 text-white p-3 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-prescription mr-2"></i>
                    Emitir Receta
                </a>
                <a href="{{ route('medico.pacientes.index') }}" 
                   class="block bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-users mr-2"></i>
                    Mis Pacientes
                </a>
            </div>
        </div>

        {{-- Próximas citas --}}
        @if($proximasCitas->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-week text-green-600 mr-2"></i>
                Próximas Citas
            </h2>
            <div class="space-y-3">
                @foreach($proximasCitas as $cita)
                    <div class="border border-gray-200 p-3 rounded-lg hover:shadow-md transition">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $cita->paciente->nombre }}
                        </p>
                        <span class="inline-block px-2 py-1 text-xs rounded-full mt-2
                            {{ $cita->estado == 'Programada' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $cita->estado }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Información profesional --}}
        <div class="bg-linear-to-br from-green-50 to-white rounded-lg shadow-md p-6 border border-green-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-id-badge text-green-600 mr-2"></i>
                Mi Información
            </h2>
            <div class="space-y-2 text-sm">
                <p><strong>Especialidad:</strong> {{ $medico->especialidad }}</p>
                @if($medico->registro_profesional)
                    <p><strong>Registro:</strong> {{ $medico->registro_profesional }}</p>
                @endif
                @if($medico->turno)
                    <p><strong>Turno:</strong> {{ $medico->turno }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection