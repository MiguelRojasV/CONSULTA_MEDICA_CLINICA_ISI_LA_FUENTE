{{-- ============================================ --}}
{{-- resources/views/medico/pacientes/historial.blade.php --}}
{{-- Historial Médico Completo del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Historial Médico')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-file-medical-alt mr-3"></i>Historial Médico
            </h1>
            <p class="text-gray-600 mt-2">
                {{ $paciente->nombre }} {{ $paciente->apellido }} (CI: {{ $paciente->ci }})
            </p>
        </div>
        <a href="{{ route('medico.pacientes.show', $paciente) }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Perfil
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Historial --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                Registros Médicos ({{ $historial->total() }})
            </h2>

            @if($historial->count() > 0)
                <div class="space-y-4">
                    @foreach($historial as $registro)
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                            {{-- Encabezado --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-lg font-bold text-gray-800 mb-1">
                                        <i class="fas fa-calendar mr-2 text-purple-600"></i>
                                        {{ $registro->fecha->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-user-md mr-2"></i>
                                        Dr(a). {{ $registro->medico->nombre ?? 'No especificado' }} 
                                        {{ $registro->medico->apellido ?? '' }}
                                    </p>
                                    @if($registro->medico && $registro->medico->especialidad)
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-stethoscope mr-2"></i>
                                            {{ $registro->medico->especialidad->nombre }}
                                        </p>
                                    @endif
                                </div>
                                
                                @if($registro->tipo_atencion)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ $registro->tipo_atencion }}
                                    </span>
                                @endif
                            </div>

                            {{-- Síntomas --}}
                            @if($registro->sintomas)
                                <div class="mb-3 bg-orange-50 border-l-4 border-orange-500 p-3 rounded-r">
                                    <p class="text-sm font-semibold text-orange-800 mb-1">
                                        <i class="fas fa-thermometer-half mr-2"></i>Síntomas:
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $registro->sintomas }}</p>
                                </div>
                            @endif

                            {{-- Signos Vitales --}}
                            @if($registro->signos_vitales)
                                <div class="mb-3 bg-red-50 border-l-4 border-red-500 p-3 rounded-r">
                                    <p class="text-sm font-semibold text-red-800 mb-1">
                                        <i class="fas fa-heartbeat mr-2"></i>Signos Vitales:
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $registro->signos_vitales }}</p>
                                </div>
                            @endif

                            {{-- Diagnóstico --}}
                            <div class="mb-3 bg-green-50 border-l-4 border-green-500 p-3 rounded-r">
                                <p class="text-sm font-semibold text-green-800 mb-1">
                                    <i class="fas fa-stethoscope mr-2"></i>Diagnóstico:
                                </p>
                                <p class="text-sm text-gray-700">{{ $registro->diagnostico }}</p>
                            </div>

                            {{-- Tratamiento --}}
                            @if($registro->tratamiento)
                                <div class="mb-3 bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r">
                                    <p class="text-sm font-semibold text-blue-800 mb-1">
                                        <i class="fas fa-pills mr-2"></i>Tratamiento:
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $registro->tratamiento }}</p>
                                </div>
                            @endif

                            {{-- Observaciones --}}
                            @if($registro->observaciones)
                                <div class="mb-3 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded-r">
                                    <p class="text-sm font-semibold text-yellow-800 mb-1">
                                        <i class="fas fa-comment-medical mr-2"></i>Observaciones:
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $registro->observaciones }}</p>
                                </div>
                            @endif

                            {{-- Exámenes Solicitados --}}
                            @if($registro->examenes_solicitados)
                                <div class="bg-purple-50 border-l-4 border-purple-500 p-3 rounded-r">
                                    <p class="text-sm font-semibold text-purple-800 mb-1">
                                        <i class="fas fa-vial mr-2"></i>Exámenes Solicitados:
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $registro->examenes_solicitados }}</p>
                                </div>
                            @endif

                            {{-- Enlace a la cita --}}
                            @if($registro->cita)
                                <div class="mt-3 pt-3 border-t">
                                    <a href="{{ route('medico.citas.show', $registro->cita) }}" 
                                       class="text-blue-600 hover:underline text-sm">
                                        <i class="fas fa-link mr-1"></i>Ver cita relacionada →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div class="mt-6">
                    {{ $historial->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-file-medical text-6xl mb-4"></i>
                    <p class="text-lg font-semibold">No hay registros en el historial médico</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Info del Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-4">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">
                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                </h3>
                <p class="text-sm text-gray-600">CI: {{ $paciente->ci }}</p>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Edad:</span>
                    <span class="font-semibold">{{ $paciente->edad }} años</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Género:</span>
                    <span class="font-semibold">{{ $paciente->genero }}</span>
                </div>
                @if($paciente->grupo_sanguineo)
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Grupo Sang.:</span>
                        <span class="font-semibold">{{ $paciente->grupo_sanguineo }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Registros:</span>
                    <span class="font-semibold text-purple-600">{{ $historial->total() }}</span>
                </div>
            </div>
        </div>

        {{-- Alertas --}}
        @if($paciente->alergias || $paciente->antecedentes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4">
                <h3 class="font-bold text-red-800 mb-3 text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas Médicas
                </h3>

                @if($paciente->alergias)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-red-700 mb-1">Alergias:</p>
                        <p class="text-xs text-red-800">{{ $paciente->alergias }}</p>
                    </div>
                @endif

                @if($paciente->antecedentes)
                    <div>
                        <p class="text-xs font-semibold text-red-700 mb-1">Antecedentes:</p>
                        <p class="text-xs text-red-800">{{ $paciente->antecedentes }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Acciones Rápidas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Acciones Rápidas
            </h3>

            <div class="space-y-2">
                <a href="{{ route('medico.pacientes.show', $paciente) }}" 
                   class="block bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                    <i class="fas fa-user mr-2"></i>Ver Perfil
                </a>
                
                <a href="{{ route('admin.pacientes.historial.pdf', $paciente) }}" 
                   class="block bg-red-600 text-white text-center px-4 py-2 rounded hover:bg-red-700 transition text-sm"
                   target="_blank">
                    <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection