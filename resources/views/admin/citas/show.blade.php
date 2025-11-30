
@extends('layouts.admin')

@section('title', 'Detalles de la Cita')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detalles de la Cita</h1>
            <p class="text-gray-600 mt-2">
                {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.citas.edit', $cita) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.citas.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

{{-- Estado de la Cita --}}
<div class="mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-{{ $cita->estado == 'Atendida' ? 'green' : ($cita->estado == 'Cancelada' ? 'red' : 'blue') }}-100 rounded-full p-4">
                    <i class="fas fa-{{ $cita->estado == 'Atendida' ? 'check-circle' : ($cita->estado == 'Cancelada' ? 'times-circle' : 'clock') }} 
                       text-{{ $cita->estado == 'Atendida' ? 'green' : ($cita->estado == 'Cancelada' ? 'red' : 'blue') }}-600 text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Estado: {{ $cita->estado }}</h2>
                    <p class="text-gray-600">Tipo: {{ $cita->tipo_cita ?? 'Primera Vez' }}</p>
                    @if($cita->duracion_estimada)
                    <p class="text-sm text-gray-500">Duración estimada: {{ $cita->duracion_estimada }} minutos</p>
                    @endif
                </div>
            </div>
            @if($cita->costo > 0)
            <div class="text-right">
                <p class="text-sm text-gray-600">Costo de consulta</p>
                <p class="text-3xl font-bold text-green-600">Bs. {{ number_format($cita->costo, 2) }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información de Paciente y Médico --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Paciente
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-3">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $cita->paciente->nombre_completo }}</p>
                        <p class="text-xs text-gray-500">CI: {{ $cita->paciente->ci }}</p>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-2">
                    <p class="text-gray-600"><i class="fas fa-birthday-cake mr-2"></i>{{ $cita->paciente->edad }} años</p>
                    <p class="text-gray-600"><i class="fas fa-phone mr-2"></i>{{ $cita->paciente->telefono }}</p>
                    <p class="text-gray-600"><i class="fas fa-envelope mr-2"></i>{{ $cita->paciente->email }}</p>
                    @if($cita->paciente->grupo_sanguineo)
                    <p class="text-gray-600"><i class="fas fa-tint mr-2"></i>Grupo: {{ $cita->paciente->grupo_sanguineo }}</p>
                    @endif
                </div>
                @if($cita->paciente->alergias)
                <div class="bg-red-50 border border-red-200 p-3 rounded mt-2">
                    <p class="text-red-800 font-semibold text-xs">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Alergias:
                    </p>
                    <p class="text-red-700 text-xs mt-1">{{ $cita->paciente->alergias }}</p>
                </div>
                @endif
                <a href="{{ route('admin.pacientes.show', $cita->paciente) }}" 
                   class="block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition mt-3">
                    <i class="fas fa-eye mr-2"></i>Ver Perfil Completo
                </a>
            </div>
        </div>

        {{-- Médico --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Médico
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-3">
                        <i class="fas fa-user-md text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Dr(a). {{ $cita->medico->nombre_completo }}</p>
                        <p class="text-xs text-gray-500">{{ $cita->medico->especialidad->nombre }}</p>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-2">
                    <p class="text-gray-600"><i class="fas fa-id-card mr-2"></i>Matrícula: {{ $cita->medico->matricula }}</p>
                    <p class="text-gray-600"><i class="fas fa-phone mr-2"></i>{{ $cita->medico->telefono }}</p>
                    @if($cita->medico->consultorio)
                    <p class="text-gray-600"><i class="fas fa-door-open mr-2"></i>{{ $cita->medico->consultorio }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.medicos.show', $cita->medico) }}" 
                   class="block text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition mt-3">
                    <i class="fas fa-eye mr-2"></i>Ver Perfil Completo
                </a>
            </div>
        </div>
    </div>

    {{-- Detalles de la Cita --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Motivo --}}
        @if($cita->motivo)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-comment-medical text-purple-600 mr-2"></i>
                Motivo de la Consulta
            </h2>
            <p class="text-gray-700 leading-relaxed">{{ $cita->motivo }}</p>
        </div>
        @endif

        {{-- Diagnóstico --}}
        @if($cita->diagnostico)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-stethoscope text-red-600 mr-2"></i>
                Diagnóstico Médico
            </h2>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                <p class="text-gray-700 leading-relaxed">{{ $cita->diagnostico }}</p>
            </div>
        </div>
        @endif

        {{-- Tratamiento --}}
        @if($cita->tratamiento)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-prescription-bottle-alt text-green-600 mr-2"></i>
                Tratamiento Prescrito
            </h2>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                <p class="text-gray-700 leading-relaxed">{{ $cita->tratamiento }}</p>
            </div>
        </div>
        @endif

        {{-- Observaciones --}}
        @if($cita->observaciones)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard text-yellow-600 mr-2"></i>
                Observaciones
            </h2>
            <p class="text-gray-700 leading-relaxed">{{ $cita->observaciones }}</p>
        </div>
        @endif

        {{-- Recetas Asociadas --}}
        @if($cita->recetas->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-prescription text-purple-600 mr-2"></i>
                Recetas Emitidas ({{ $cita->recetas->count() }})
            </h2>
            <div class="space-y-3">
                @foreach($cita->recetas as $receta)
                <div class="border border-gray-200 p-4 rounded-lg hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">
                                Receta #{{ str_pad($receta->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Fecha: {{ $receta->fecha_emision->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Medicamentos: {{ $receta->medicamentos->count() }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $receta->estado == 'Dispensada' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ $receta->estado }}
                            </span>
                            <a href="{{ route('admin.recetas.show', $receta) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.recetas.pdf', $receta) }}" 
                               class="text-red-600 hover:text-red-800" target="_blank">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Historial Médico Relacionado --}}
        @if($cita->historialMedico)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-notes-medical text-blue-600 mr-2"></i>
                Registro en Historial Médico
            </h2>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                <p class="text-sm text-gray-700">
                    <strong>Fecha:</strong> {{ $cita->historialMedico->fecha->format('d/m/Y') }}
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Tipo:</strong> {{ $cita->historialMedico->tipo_atencion }}
                </p>
                @if($cita->historialMedico->sintomas)
                <p class="text-sm text-gray-700 mt-2">
                    <strong>Síntomas:</strong> {{ $cita->historialMedico->sintomas }}
                </p>
                @endif
                @if($cita->historialMedico->signos_vitales)
                <p class="text-sm text-gray-700 mt-2">
                    <strong>Signos Vitales:</strong> {{ $cita->historialMedico->signos_vitales }}
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Información Adicional --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                Información Adicional
            </h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 font-semibold">Fecha de Registro:</p>
                    <p class="text-gray-800">{{ $cita->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Última Actualización:</p>
                    <p class="text-gray-800">{{ $cita->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
