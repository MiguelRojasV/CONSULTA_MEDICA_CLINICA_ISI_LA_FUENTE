{{-- ============================================ --}}
{{-- resources/views/paciente/historial/index.blade.php --}}
{{-- Vista: Historial Médico del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Mi Historial Médico')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mi Historial Médico</h1>
            <p class="text-gray-600 mt-2">Registro completo de sus consultas y tratamientos</p>
        </div>
        <a href="{{ route('paciente.historial.pdf') }}" 
           target="_blank"
           class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition shadow-lg">
            <i class="fas fa-file-pdf mr-2"></i>Descargar PDF Completo
        </a>
    </div>
</div>

{{-- Información del paciente --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-user text-blue-600 mr-2"></i>
        Información del Paciente
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Nombre Completo</p>
            <p class="font-semibold text-gray-800">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">CI</p>
            <p class="font-semibold text-gray-800">{{ $paciente->ci }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Edad</p>
            <p class="font-semibold text-gray-800">{{ $paciente->edad }} años</p>
        </div>

        @if($paciente->grupo_sanguineo)
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600 mb-1">Grupo Sanguíneo</p>
            <p class="font-semibold text-gray-800">{{ $paciente->grupo_sanguineo }}</p>
        </div>
        @endif

        @if($paciente->alergias)
        <div class="bg-red-50 p-4 rounded-lg border border-red-200 md:col-span-2">
            <p class="text-sm font-semibold text-red-800 mb-1">
                <i class="fas fa-exclamation-triangle mr-2"></i>Alergias
            </p>
            <p class="text-gray-800">{{ $paciente->alergias }}</p>
        </div>
        @endif
    </div>

    @if($paciente->antecedentes)
    <div class="mt-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
        <p class="text-sm font-semibold text-yellow-900 mb-2">
            <i class="fas fa-clipboard-list mr-2"></i>Antecedentes Médicos
        </p>
        <p class="text-gray-800">{{ $paciente->antecedentes }}</p>
    </div>
    @endif
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Consultas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $historial->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-user-md text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Médicos Diferentes</p>
                <p class="text-2xl font-bold text-gray-800">{{ $historial->unique('medico_id')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-prescription text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Recetas Emitidas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $paciente->recetas->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Última Consulta</p>
                <p class="text-lg font-bold text-gray-800">
                    @if($historial->first())
                        {{ $historial->first()->fecha->format('d/m/Y') }}
                    @else
                        Sin registro
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Línea de tiempo del historial --}}
@if($historial->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-history text-green-600 mr-2"></i>
            Línea de Tiempo
        </h2>

        <div class="space-y-6">
            @foreach($historial as $registro)
                <div class="relative pl-8 pb-6 border-l-2 border-gray-300 last:border-l-0 last:pb-0">
                    {{-- Punto en la línea de tiempo --}}
                    <div class="absolute -left-2 top-0 w-4 h-4 rounded-full bg-blue-600 border-2 border-white"></div>

                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $registro->fecha->format('d/m/Y') }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $registro->tipo_atencion ?? 'Consulta General' }}</p>
                            </div>
                            @if($registro->cita_id)
                                <a href="{{ route('paciente.citas.show', $registro->cita_id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Ver cita
                                </a>
                            @endif
                        </div>

                        {{-- Médico --}}
                        @if($registro->medico)
                        <div class="mb-4 flex items-center">
                            <div class="bg-green-100 rounded-full p-2 mr-3">
                                <i class="fas fa-user-md text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    Dr(a). {{ $registro->medico->nombre }} {{ $registro->medico->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $registro->medico->especialidad->nombre }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Síntomas (si existen) --}}
                        @if($registro->sintomas)
                        <div class="mb-3 bg-yellow-50 p-3 rounded border border-yellow-200">
                            <p class="text-sm font-semibold text-yellow-900 mb-1">
                                <i class="fas fa-notes-medical mr-2"></i>Síntomas
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->sintomas }}</p>
                        </div>
                        @endif

                        {{-- Diagnóstico --}}
                        <div class="mb-3 bg-blue-50 p-3 rounded border border-blue-200">
                            <p class="text-sm font-semibold text-blue-900 mb-1">
                                <i class="fas fa-diagnoses mr-2"></i>Diagnóstico
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->diagnostico }}</p>
                        </div>

                        {{-- Tratamiento --}}
                        @if($registro->tratamiento)
                        <div class="mb-3 bg-green-50 p-3 rounded border border-green-200">
                            <p class="text-sm font-semibold text-green-900 mb-1">
                                <i class="fas fa-prescription-bottle-alt mr-2"></i>Tratamiento
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->tratamiento }}</p>
                        </div>
                        @endif

                        {{-- Signos vitales --}}
                        @if($registro->signos_vitales)
                        <div class="bg-purple-50 p-3 rounded border border-purple-200">
                            <p class="text-sm font-semibold text-purple-900 mb-1">
                                <i class="fas fa-heartbeat mr-2"></i>Signos Vitales
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->signos_vitales }}</p>
                        </div>
                        @endif

                        {{-- Observaciones --}}
                        @if($registro->observaciones)
                        <div class="mt-3 bg-gray-100 p-3 rounded">
                            <p class="text-sm font-semibold text-gray-900 mb-1">
                                <i class="fas fa-comment mr-2"></i>Observaciones
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->observaciones }}</p>
                        </div>
                        @endif

                        {{-- Exámenes solicitados --}}
                        @if($registro->examenes_solicitados)
                        <div class="mt-3 bg-red-50 p-3 rounded border border-red-200">
                            <p class="text-sm font-semibold text-red-900 mb-1">
                                <i class="fas fa-vial mr-2"></i>Exámenes Solicitados
                            </p>
                            <p class="text-sm text-gray-800">{{ $registro->examenes_solicitados }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-file-medical text-gray-300 text-6xl mb-4"></i>
        <p class="text-gray-500 text-lg mb-2">No tiene historial médico registrado</p>
        <p class="text-gray-400 text-sm">El historial se genera después de sus consultas médicas</p>
    </div>
@endif

{{-- Información adicional --}}
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <p class="text-sm text-gray-700">
        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
        <strong>Información:</strong> Su historial médico es confidencial y solo es accesible por usted y los médicos autorizados que lo atiendan.
    </p>
</div>

@endsection

{{-- 
CARACTERÍSTICAS:
1. Vista completa del historial médico del paciente
2. Información del paciente con alergias destacadas
3. Estadísticas visuales (consultas, médicos, recetas)
4. Línea de tiempo cronológica
5. Cada registro incluye: fecha, médico, diagnóstico, tratamiento
6. Signos vitales, síntomas y observaciones
7. Exámenes solicitados destacados
8. Links a citas relacionadas
9. Botón para descargar PDF completo
10. Diseño profesional tipo timeline
--}}