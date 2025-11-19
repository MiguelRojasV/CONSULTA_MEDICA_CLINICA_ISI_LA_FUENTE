{{-- ============================================ --}}
{{-- resources/views/paciente/citas/show.blade.php --}}
{{-- Vista: Detalles de Cita del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Detalles de la Cita')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detalles de la Cita</h1>
            <p class="text-gray-600 mt-2">Información completa de su consulta médica</p>
        </div>
        <a href="{{ route('paciente.citas.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Información de la cita --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                    Información de la Cita
                </h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $cita->estado == 'Programada' ? 'bg-blue-100 text-blue-800' : 
                       ($cita->estado == 'Confirmada' ? 'bg-green-100 text-green-800' : 
                       ($cita->estado == 'Atendida' ? 'bg-purple-100 text-purple-800' : 
                       'bg-red-100 text-red-800')) }}">
                    {{ $cita->estado }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Fecha</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-calendar mr-2 text-blue-600"></i>
                        {{ $cita->fecha->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $cita->fecha->locale('es')->isoFormat('dddd') }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Hora</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-clock mr-2 text-green-600"></i>
                        {{ $cita->hora->format('H:i') }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $cita->duracion_estimada ?? 30 }} minutos aprox.</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Tipo de Cita</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-tag mr-2 text-purple-600"></i>
                        {{ $cita->tipo_cita ?? 'Primera Vez' }}
                    </p>
                </div>

                @if($cita->costo)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Costo</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                        Bs. {{ number_format($cita->costo, 2) }}
                    </p>
                </div>
                @endif
            </div>

            @if($cita->motivo)
            <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm font-semibold text-blue-900 mb-2">
                    <i class="fas fa-comment-medical mr-2"></i>Motivo de Consulta
                </p>
                <p class="text-gray-800">{{ $cita->motivo }}</p>
            </div>
            @endif
        </div>

        {{-- Información del médico --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Información del Médico
            </h2>

            <div class="flex items-start space-x-4">
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-md text-green-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Dr(a). {{ $cita->medico->nombre }} {{ $cita->medico->apellido }}
                    </h3>
                    <p class="text-blue-600 font-semibold">{{ $cita->medico->especialidad->nombre }}</p>
                    
                    <div class="mt-3 space-y-2 text-sm">
                        @if($cita->medico->matricula)
                        <p class="text-gray-600">
                            <i class="fas fa-id-card mr-2"></i>
                            Matrícula: {{ $cita->medico->matricula }}
                        </p>
                        @endif
                        
                        @if($cita->medico->consultorio)
                        <p class="text-gray-600">
                            <i class="fas fa-door-open mr-2"></i>
                            Consultorio: {{ $cita->medico->consultorio }}
                        </p>
                        @endif

                        @if($cita->medico->telefono)
                        <p class="text-gray-600">
                            <i class="fas fa-phone mr-2"></i>
                            Teléfono: {{ $cita->medico->telefono }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Diagnóstico y Tratamiento (si está atendida) --}}
        @if($cita->estado == 'Atendida' && $cita->diagnostico)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-notes-medical text-purple-600 mr-2"></i>
                Diagnóstico y Tratamiento
            </h2>

            <div class="space-y-4">
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <p class="text-sm font-semibold text-purple-900 mb-2">Diagnóstico</p>
                    <p class="text-gray-800">{{ $cita->diagnostico }}</p>
                </div>

                @if($cita->tratamiento)
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <p class="text-sm font-semibold text-green-900 mb-2">Tratamiento</p>
                    <p class="text-gray-800">{{ $cita->tratamiento }}</p>
                </div>
                @endif

                @if($cita->observaciones)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm font-semibold text-gray-900 mb-2">Observaciones</p>
                    <p class="text-gray-800">{{ $cita->observaciones }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar con acciones --}}
    <div class="space-y-6">
        {{-- Acciones disponibles --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Acciones
            </h2>

            <div class="space-y-3">
                @if($cita->estado != 'Cancelada' && $cita->estado != 'Atendida' && $cita->fecha->isFuture())
                    <form action="{{ route('paciente.citas.cancelar', $cita) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de cancelar esta cita?')">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-600 text-white p-3 rounded-lg hover:bg-red-700 transition text-center">
                            <i class="fas fa-times mr-2"></i>Cancelar Cita
                        </button>
                    </form>
                @endif

                @if($cita->estado == 'Atendida' && $cita->recetas->count() > 0)
                    <a href="{{ route('paciente.recetas.show', $cita->recetas->first()) }}" 
                       class="block bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition text-center">
                        <i class="fas fa-prescription mr-2"></i>Ver Receta
                    </a>
                @endif

                <a href="{{ route('paciente.citas.index') }}" 
                   class="block bg-gray-600 text-white p-3 rounded-lg hover:bg-gray-700 transition text-center">
                    <i class="fas fa-list mr-2"></i>Ver Todas las Citas
                </a>
            </div>
        </div>

        {{-- Información de contacto --}}
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg shadow-md p-6 border border-blue-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Información Importante
            </h2>

            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                    <p>Llegue 10 minutos antes de su hora programada</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-id-card text-blue-600 mt-1 mr-2"></i>
                    <p>Traiga su documento de identidad</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-file-medical text-purple-600 mt-1 mr-2"></i>
                    <p>Si tiene estudios previos, tráigalos a la consulta</p>
                </div>
            </div>
        </div>

        {{-- Recordatorio --}}
        @if($cita->estado != 'Cancelada' && $cita->estado != 'Atendida')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-gray-700">
                <i class="fas fa-bell text-yellow-600 mr-2"></i>
                <strong>Recordatorio:</strong> Si no puede asistir, por favor cancele su cita con anticipación.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection

{{-- 
CARACTERÍSTICAS:
1. Vista completa con toda la información de la cita
2. Estado visual destacado con colores
3. Información del médico detallada
4. Diagnóstico y tratamiento (si aplicable)
5. Acciones contextuales según estado
6. Sidebar con información importante
7. Botón para cancelar (si aplica)
8. Link a receta (si existe)
9. Diseño limpio y organizado
10. Responsive
--}}