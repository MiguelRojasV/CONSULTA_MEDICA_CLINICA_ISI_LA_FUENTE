@extends('layouts.app')

@section('title', 'Detalles del Paciente - Clínica ISI La Fuente')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $paciente->nombre }}</h1>
            <p class="text-gray-600 mt-1">CI: {{ $paciente->ci }}</p>
            <a href="{{ route('pacientes.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Volver a la lista
            </a>
        </div>
        <div class="space-x-2">
            <a href="{{ route('pacientes.edit', $paciente) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 inline-block">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('citas.create') }}?paciente_id={{ $paciente->id }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 inline-block">
                <i class="fas fa-calendar-plus mr-2"></i>Nueva Cita
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Paciente -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Información Personal</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Edad</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $paciente->edad }} años</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Contacto de Emergencia</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $paciente->contacto_emergencia ?? 'No registrado' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Información Médica</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Antecedentes</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $paciente->antecedentes ?? 'Ninguno registrado' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alergias</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($paciente->alergias)
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $paciente->alergias }}
                                </span>
                            @else
                                Ninguna registrada
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Historial de Citas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Historial de Citas</h2>
                
                @if($paciente->citas->count() > 0)
                    <div class="space-y-4">
                        @foreach($paciente->citas->sortByDesc('fecha') as $cita)
                            <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded-r-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <span class="text-sm font-semibold text-gray-900">
                                                {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                                            </span>
                                            @php
                                                $estadoClasses = [
                                                    'Programada' => 'bg-blue-100 text-blue-800',
                                                    'Confirmada' => 'bg-green-100 text-green-800',
                                                    'En Consulta' => 'bg-yellow-100 text-yellow-800',
                                                    'Atendida' => 'bg-purple-100 text-purple-800',
                                                    'Cancelada' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $estadoClasses[$cita->estado] }}">
                                                {{ $cita->estado }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-1">
                                            <i class="fas fa-user-md mr-1"></i>
                                            <strong>Dr(a). {{ $cita->medico->nombre }}</strong> - {{ $cita->medico->especialidad }}
                                        </p>
                                        
                                        @if($cita->motivo)
                                            <p class="text-sm text-gray-700 mb-1">
                                                <strong>Motivo:</strong> {{ $cita->motivo }}
                                            </p>
                                        @endif
                                        
                                        @if($cita->diagnostico)
                                            <p class="text-sm text-gray-700 mb-1">
                                                <strong>Diagnóstico:</strong> {{ $cita->diagnostico }}
                                            </p>
                                        @endif
                                        
                                        @if($cita->tratamiento)
                                            <p class="text-sm text-gray-700">
                                                <strong>Tratamiento:</strong> {{ $cita->tratamiento }}
                                            </p>
                                        @endif

                                        @if($cita->recetas->count() > 0)
                                            <div class="mt-2 pt-2 border-t border-gray-200">
                                                <p class="text-sm font-semibold text-gray-700 mb-1">
                                                    <i class="fas fa-prescription mr-1"></i>Recetas:
                                                </p>
                                                @foreach($cita->recetas as $receta)
                                                    <div class="ml-4 text-sm text-gray-600">
                                                        <span class="font-medium">Medicamentos:</span>
                                                        {{ $receta->medicamentos->pluck('nombre_generico')->join(', ') }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('citas.show', $cita) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Ver detalles →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">
                        <i class="fas fa-calendar-times text-4xl mb-2"></i><br>
                        No hay citas registradas para este paciente
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection