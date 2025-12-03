{{-- ============================================ --}}
{{-- resources/views/medico/citas/show.blade.php --}}
{{-- Detalle de Cita Médica --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Detalle de Cita')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-calendar-check mr-3"></i>Detalle de Cita
        </h1>
        <a href="{{ route('medico.citas.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Agenda
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información de la Cita --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Estado y Fecha --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ $cita->fecha->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                    </h2>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                        {{ $cita->estado == 'Programada' ? 'bg-yellow-200 text-yellow-800' : 
                           ($cita->estado == 'Confirmada' ? 'bg-blue-200 text-blue-800' : 
                           ($cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 
                           ($cita->estado == 'En Consulta' ? 'bg-purple-200 text-purple-800' : 
                           'bg-gray-200 text-gray-800'))) }}">
                        <i class="fas fa-circle mr-2"></i>{{ $cita->estado }}
                    </span>
                </div>

                @if($cita->estado !== 'Atendida' && $cita->estado !== 'Cancelada')
                    <a href="{{ route('medico.citas.edit', $cita) }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-user-md mr-2"></i>Atender Paciente
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-tag mr-2"></i>Tipo de Cita
                    </p>
                    <p class="font-semibold text-gray-800">{{ $cita->tipo_cita ?? 'No especificado' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-clock mr-2"></i>Duración
                    </p>
                    <p class="font-semibold text-gray-800">
                        {{ $cita->duracion_estimada ?? 30 }} minutos
                    </p>
                </div>
            </div>

            @if($cita->motivo)
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                    <p class="text-sm text-blue-800 font-semibold mb-2">
                        <i class="fas fa-notes-medical mr-2"></i>Motivo de Consulta
                    </p>
                    <p class="text-gray-700">{{ $cita->motivo }}</p>
                </div>
            @endif
        </div>

        {{-- Diagnóstico y Tratamiento --}}
        @if($cita->diagnostico || $cita->tratamiento)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-stethoscope text-green-600 mr-2"></i>
                    Atención Médica
                </h3>

                @if($cita->diagnostico)
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heartbeat mr-2"></i>Diagnóstico
                        </label>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                            <p class="text-gray-800">{{ $cita->diagnostico }}</p>
                        </div>
                    </div>
                @endif

                @if($cita->tratamiento)
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-pills mr-2"></i>Tratamiento
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                            <p class="text-gray-800">{{ $cita->tratamiento }}</p>
                        </div>
                    </div>
                @endif

                @if($cita->observaciones)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-comment-medical mr-2"></i>Observaciones
                        </label>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
                            <p class="text-gray-800">{{ $cita->observaciones }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Recetas Asociadas --}}
        @if($cita->recetas->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-prescription text-orange-600 mr-2"></i>
                    Recetas Emitidas ({{ $cita->recetas->count() }})
                </h3>

                <div class="space-y-3">
                    @foreach($cita->recetas as $receta)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        Receta #{{ $receta->id }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Emitida: {{ $receta->fecha_emision->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-capsules mr-1"></i>
                                        {{ $receta->medicamentos->count() }} medicamento(s)
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full mt-2 inline-block
                                        {{ $receta->estado == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($receta->estado == 'Dispensada' ? 'bg-green-100 text-green-800' : 
                                           'bg-gray-100 text-gray-800') }}">
                                        {{ $receta->estado }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('medico.recetas.show', $receta) }}" 
                                       class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medico.recetas.pdf', $receta) }}" 
                                       class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition text-sm"
                                       target="_blank"
                                       title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            @if($cita->estado == 'Atendida')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-prescription text-orange-600 mr-2"></i>
                        Recetas
                    </h3>
                    <div class="text-center py-6">
                        <i class="fas fa-prescription text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-500 mb-4">No se emitió receta para esta cita</p>
                        <a href="{{ route('medico.recetas.create', ['cita_id' => $cita->id]) }}" 
                           class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                            <i class="fas fa-plus mr-2"></i>Crear Receta
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>

    {{-- Información del Paciente --}}
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información del Paciente
            </h3>

            <div class="text-center mb-4">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 text-lg">
                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                </h4>
                <p class="text-gray-600 text-sm">CI: {{ $cita->paciente->ci }}</p>
            </div>

            <div class="space-y-3">
                <div class="border-b pb-2">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-birthday-cake mr-2"></i>Edad
                    </p>
                    <p class="font-semibold text-gray-800">{{ $cita->paciente->edad }} años</p>
                </div>

                <div class="border-b pb-2">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-venus-mars mr-2"></i>Género
                    </p>
                    <p class="font-semibold text-gray-800">{{ $cita->paciente->genero }}</p>
                </div>

                @if($cita->paciente->grupo_sanguineo)
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-tint mr-2"></i>Grupo Sanguíneo
                        </p>
                        <p class="font-semibold text-gray-800">{{ $cita->paciente->grupo_sanguineo }}</p>
                    </div>
                @endif

                @if($cita->paciente->telefono)
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-2"></i>Teléfono
                        </p>
                        <p class="font-semibold text-gray-800">{{ $cita->paciente->telefono }}</p>
                    </div>
                @endif

                @if($cita->paciente->email)
                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </p>
                        <p class="font-semibold text-gray-800 text-sm break-all">
                            {{ $cita->paciente->email }}
                        </p>
                    </div>
                @endif
            </div>

            <a href="{{ route('medico.pacientes.show', $cita->paciente) }}" 
               class="block mt-4 bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-folder-open mr-2"></i>Ver Historial Completo
            </a>
        </div>

        {{-- Alertas Médicas --}}
        @if($cita->paciente->alergias || $cita->paciente->antecedentes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4">
                <h4 class="font-bold text-red-800 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas Médicas
                </h4>

                @if($cita->paciente->alergias)
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-red-700 mb-1">
                            <i class="fas fa-allergies mr-2"></i>Alergias:
                        </p>
                        <p class="text-sm text-red-800">{{ $cita->paciente->alergias }}</p>
                    </div>
                @endif

                @if($cita->paciente->antecedentes)
                    <div>
                        <p class="text-sm font-semibold text-red-700 mb-1">
                            <i class="fas fa-file-medical mr-2"></i>Antecedentes:
                        </p>
                        <p class="text-sm text-red-800">{{ $cita->paciente->antecedentes }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection