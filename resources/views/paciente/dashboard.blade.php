@extends('layouts.paciente')

@section('title', 'Mi Panel')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">¡Bienvenido, {{ $paciente->nombre }}!</h1>
    <p class="text-gray-600 mt-2">Aquí puede gestionar sus citas médicas y consultar su información de salud</p>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    {{-- Total de citas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
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
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
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

    {{-- Recetas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-prescription text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Recetas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetasRecientes->count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Próximas citas --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Próximas Citas
                </h2>
                <a href="{{ route('paciente.citas.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                    <i class="fas fa-plus mr-2"></i>Nueva Cita
                </a>
            </div>

            @if($proximasCitas->count() > 0)
                <div class="space-y-3">
                    @foreach($proximasCitas as $cita)
                        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="font-semibold text-gray-800">
                                            {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $cita->estado == 'Programada' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $cita->estado }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-user-md mr-2 text-blue-600"></i>
                                        <strong>Dr(a). {{ $cita->medico->nombre }}</strong>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $cita->medico->especialidad }}
                                    </p>
                                    @if($cita->motivo)
                                        <p class="text-sm text-gray-500 mt-2">
                                            <strong>Motivo:</strong> {{ Str::limit($cita->motivo, 80) }}
                                        </p>
                                    @endif
                                </div>
                                <a href="{{ route('paciente.citas.show', $cita) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No tiene citas próximas programadas</p>
                    <a href="{{ route('paciente.citas.create') }}" 
                       class="text-blue-600 hover:underline mt-2 inline-block">
                        Agendar una cita ahora
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Accesos rápidos y recetas recientes --}}
    <div class="space-y-6">
        {{-- Accesos rápidos --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Accesos Rápidos
            </h2>
            <div class="space-y-3">
                <a href="{{ route('paciente.citas.create') }}" 
                   class="block bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Agendar Nueva Cita
                </a>
                <a href="{{ route('paciente.historial.index') }}" 
                   class="block bg-green-600 text-white p-3 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-medical mr-2"></i>
                    Ver Historial Médico
                </a>
                <a href="{{ route('paciente.perfil.show') }}" 
                   class="block bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-user-edit mr-2"></i>
                    Editar Mi Perfil
                </a>
            </div>
        </div>

        {{-- Recetas recientes --}}
        @if($recetasRecientes->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-prescription text-purple-600 mr-2"></i>
                Recetas Recientes
            </h2>
            <div class="space-y-3">
                @foreach($recetasRecientes as $receta)
                    <div class="border border-gray-200 p-3 rounded-lg hover:shadow-md transition">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $receta->fecha_emision->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-gray-600">
                            Dr(a). {{ $receta->medico->nombre }}
                        </p>
                        <a href="{{ route('paciente.recetas.show', $receta) }}" 
                           class="text-blue-600 text-xs hover:underline mt-2 inline-block">
                            Ver receta →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Información de perfil --}}
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg shadow-md p-6 border border-blue-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                Mi Información
            </h2>
            <div class="space-y-2 text-sm">
                <p><strong>CI:</strong> {{ $paciente->ci }}</p>
                <p><strong>Edad:</strong> {{ $paciente->edad }} años</p>
                @if($paciente->grupo_sanguineo)
                    <p><strong>Grupo Sanguíneo:</strong> {{ $paciente->grupo_sanguineo }}</p>
                @endif
                @if($paciente->alergias)
                    <div class="bg-red-50 border border-red-200 p-2 rounded mt-2">
                        <p class="text-red-800 font-semibold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Alergias:
                        </p>
                        <p class="text-red-700 text-xs mt-1">{{ $paciente->alergias }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection