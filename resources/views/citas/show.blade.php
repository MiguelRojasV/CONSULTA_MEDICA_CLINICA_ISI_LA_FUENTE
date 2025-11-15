@extends('layouts.app')

@section('title', 'Detalles de la Cita - Clínica ISI La Fuente')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detalles de la Cita</h1>
            <p class="text-gray-600 mt-1">
                {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
            </p>
            <a href="{{ route('citas.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Volver a la lista
            </a>
        </div>
        <div class="space-x-2">
            <a href="{{ route('citas.edit', $cita) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 inline-block">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            @if($cita->estado == 'Atendida' && $cita->recetas->count() == 0)
                <a href="{{ route('recetas.create') }}?cita_id={{ $cita->id }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 inline-block">
                    <i class="fas fa-prescription mr-2"></i>Crear Receta
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información General -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Estado de la Cita -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Estado</h2>
                @php
                    $estadoClasses = [
                        'Programada' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'Confirmada' => 'bg-green-100 text-green-800 border-green-300',
                        'En Consulta' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'Atendida' => 'bg-purple-100 text-purple-800 border-purple-300',
                        'Cancelada' => 'bg-red-100 text-red-800 border-red-300'
                    ];
                @endphp
                <div class="flex justify-center">
                    <span class="px-4 py-2 text-lg font-semibold rounded-full border-2 {{ $estadoClasses[$cita->estado] }}">
                        {{ $cita->estado }}
                    </span>
                </div>
            </div>

            <!-- Información del Paciente -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Paciente
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            <a href="{{ route('pacientes.show', $cita->paciente) }}" class="text-blue-600 hover:underline">
                                {{ $cita->paciente->nombre }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CI</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cita->paciente->ci }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Edad</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cita->paciente->edad }} años</dd>
                    </div>
                    @if($cita->paciente->alergias)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alergias</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $cita->paciente->alergias }}
                                </span>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Información del Médico -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-md mr-2 text-green-600"></i>Médico
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            <a href="{{ route('medicos.show', $cita->medico) }}" class="text-blue-600 hover:underline">
                                Dr(a). {{ $cita->medico->nombre }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Especialidad</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cita->medico->especialidad }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Turno</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cita->medico->turno ?? 'No especificado' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Detalles de la Consulta -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Motivo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Motivo de Consulta</h2>
                <p class="text-gray-700">
                    {{ $cita->motivo ?? 'No especificado' }}
                </p>
            </div>

            <!-- Diagnóstico -->
            @if($cita->diagnostico)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Diagnóstico</h2>
                    <p class="text-gray-700">{{ $cita->diagnostico }}</p>
                </div>
            @endif

            <!-- Tratamiento -->
            @if($cita->tratamiento)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tratamiento</h2>
                    <p class="text-gray-700">{{ $cita->tratamiento }}</p>
                </div>
            @endif

            <!-- Recetas -->
            @if($cita->recetas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-prescription mr-2 text-purple-600"></i>Recetas Emitidas
                    </h2>
                    <div class="space-y-4">
                        @foreach($cita->recetas as $receta)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Fecha de emisión</p>
                                        <p class="font-semibold">{{ $receta->fecha_emision->format('d/m/Y') }}</p>
                                    </div>
                                    <a href="{{ route('recetas.show', $receta) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Ver receta completa →
                                    </a>
                                </div>

                                @if($receta->indicaciones)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Indicaciones:</p>
                                        <p class="text-sm text-gray-600">{{ $receta->indicaciones }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">Medicamentos:</p>
                                    <div class="space-y-2">
                                        @foreach($receta->medicamentos as $medicamento)
                                            <div class="flex items-center space-x-2 text-sm">
                                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded font-semibold">
                                                    {{ $medicamento->pivot->cantidad }}x
                                                </span>
                                                <span class="text-gray-700">
                                                    {{ $medicamento->nombre_generico }}
                                                    @if($medicamento->dosis)
                                                        <span class="text-gray-500">({{ $medicamento->dosis }})</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Información Adicional -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Información Adicional</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $cita->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $cita->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection