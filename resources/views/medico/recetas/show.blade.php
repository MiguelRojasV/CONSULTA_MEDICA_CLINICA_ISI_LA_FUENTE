{{-- ============================================ --}}
{{-- resources/views/medico/recetas/show.blade.php --}}
{{-- Detalle de Receta Médica --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Detalle de Receta')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-prescription mr-3"></i>Receta Médica #{{ $receta->id }}
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('medico.recetas.pdf', $receta) }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
               target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
            </a>
            <a href="{{ route('medico.recetas.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información Principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Encabezado de la Receta --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Receta #{{ $receta->id }}
                    </h2>
                    <p class="text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        Fecha de emisión: {{ $receta->fecha_emision->format('d/m/Y') }}
                    </p>
                    @if($receta->valida_hasta)
                        <p class="text-gray-600">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Válida hasta: {{ $receta->valida_hasta->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
                
                <span class="px-4 py-2 text-sm font-semibold rounded-full
                    {{ $receta->estado == 'Pendiente' ? 'bg-yellow-200 text-yellow-800' : 
                       ($receta->estado == 'Dispensada' ? 'bg-green-200 text-green-800' : 
                       'bg-gray-200 text-gray-800') }}">
                    <i class="fas fa-circle mr-2"></i>{{ $receta->estado }}
                </span>
            </div>

            @if($receta->estaVencida())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                    <p class="text-red-800 font-semibold">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Esta receta ha vencido
                    </p>
                </div>
            @endif
        </div>

        {{-- Indicaciones --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-notes-medical text-green-600 mr-2"></i>
                Indicaciones Generales
            </h3>

            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r mb-4">
                <p class="text-gray-800 whitespace-pre-line">{{ $receta->indicaciones }}</p>
            </div>

            @if($receta->observaciones)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
                    <p class="text-sm font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Observaciones:
                    </p>
                    <p class="text-gray-800">{{ $receta->observaciones }}</p>
                </div>
            @endif
        </div>

        {{-- Medicamentos --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-pills text-orange-600 mr-2"></i>
                Medicamentos Prescritos ({{ $receta->medicamentos->count() }})
            </h3>

            <div class="space-y-4">
                @foreach($receta->medicamentos as $index => $medicamento)
                    <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                        <div class="flex items-start space-x-4">
                            <div class="bg-orange-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-lg mb-1">
                                    {{ $medicamento->nombre_generico }}
                                </h4>
                                @if($medicamento->nombre_comercial)
                                    <p class="text-sm text-gray-600 mb-2">
                                        ({{ $medicamento->nombre_comercial }})
                                    </p>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div class="bg-white rounded p-2">
                                        <p class="text-xs text-gray-600 mb-1">
                                            <i class="fas fa-capsules mr-1"></i>Cantidad
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $medicamento->pivot->cantidad }} unidad(es)
                                        </p>
                                    </div>
                                    
                                    <div class="bg-white rounded p-2">
                                        <p class="text-xs text-gray-600 mb-1">
                                            <i class="fas fa-prescription-bottle mr-1"></i>Dosis
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $medicamento->pivot->dosis }}
                                        </p>
                                    </div>
                                    
                                    <div class="bg-white rounded p-2">
                                        <p class="text-xs text-gray-600 mb-1">
                                            <i class="fas fa-clock mr-1"></i>Frecuencia
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $medicamento->pivot->frecuencia }}
                                        </p>
                                    </div>
                                    
                                    <div class="bg-white rounded p-2">
                                        <p class="text-xs text-gray-600 mb-1">
                                            <i class="fas fa-calendar-alt mr-1"></i>Duración
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{ $medicamento->pivot->duracion }}
                                        </p>
                                    </div>
                                </div>

                                @if($medicamento->pivot->instrucciones_especiales)
                                    <div class="mt-3 bg-blue-50 border-l-4 border-blue-500 p-2 rounded-r">
                                        <p class="text-xs font-semibold text-blue-800 mb-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Instrucciones especiales:
                                        </p>
                                        <p class="text-sm text-gray-700">
                                            {{ $medicamento->pivot->instrucciones_especiales }}
                                        </p>
                                    </div>
                                @endif

                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">
                                        {{ $medicamento->presentacion }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">
                                        {{ $medicamento->via_administracion }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Cita Asociada --}}
        @if($receta->cita)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-calendar-check text-purple-600 mr-2"></i>
                    Cita Asociada
                </h3>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800 mb-1">
                                {{ $receta->cita->fecha->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($receta->cita->hora)->format('H:i') }}
                            </p>
                            @if($receta->cita->diagnostico)
                                <p class="text-sm text-gray-700 mb-1">
                                    <strong>Diagnóstico:</strong> {{ Str::limit($receta->cita->diagnostico, 100) }}
                                </p>
                            @endif
                            @if($receta->cita->tratamiento)
                                <p class="text-sm text-gray-600">
                                    <strong>Tratamiento:</strong> {{ Str::limit($receta->cita->tratamiento, 100) }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('medico.citas.show', $receta->cita) }}" 
                           class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition text-sm">
                            <i class="fas fa-eye mr-2"></i>Ver Cita
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Información del Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Paciente
            </h3>

            <div class="text-center mb-4">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 text-lg">
                    {{ $receta->paciente->nombre }} {{ $receta->paciente->apellido }}
                </h4>
                <p class="text-sm text-gray-600">CI: {{ $receta->paciente->ci }}</p>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Edad:</span>
                    <span class="font-semibold">{{ $receta->paciente->edad }} años</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Género:</span>
                    <span class="font-semibold">{{ $receta->paciente->genero }}</span>
                </div>
                @if($receta->paciente->grupo_sanguineo)
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Grupo Sang.:</span>
                        <span class="font-semibold">{{ $receta->paciente->grupo_sanguineo }}</span>
                    </div>
                @endif
                @if($receta->paciente->telefono)
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Teléfono:</span>
                        <span class="font-semibold">{{ $receta->paciente->telefono }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ route('medico.pacientes.show', $receta->paciente) }}" 
               class="block mt-4 bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-folder-open mr-2"></i>Ver Perfil
            </a>
        </div>

        {{-- Alertas del Paciente --}}
        @if($receta->paciente->alergias || $receta->paciente->antecedentes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4">
                <h4 class="font-bold text-red-800 mb-3 text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas Médicas
                </h4>

                @if($receta->paciente->alergias)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-red-700 mb-1">Alergias:</p>
                        <p class="text-xs text-red-800">{{ $receta->paciente->alergias }}</p>
                    </div>
                @endif

                @if($receta->paciente->antecedentes)
                    <div>
                        <p class="text-xs font-semibold text-red-700 mb-1">Antecedentes:</p>
                        <p class="text-xs text-red-800">{{ $receta->paciente->antecedentes }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Información de la Receta --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                Información
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Estado:</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        {{ $receta->estado == 'Pendiente' ? 'bg-yellow-200 text-yellow-800' : 
                           ($receta->estado == 'Dispensada' ? 'bg-green-200 text-green-800' : 
                           'bg-gray-200 text-gray-800') }}">
                        {{ $receta->estado }}
                    </span>
                </div>

                <div class="flex justify-between pb-3 border-b">
                    <span class="text-gray-600">Medicamentos:</span>
                    <span class="font-semibold">{{ $receta->medicamentos->count() }}</span>
                </div>

                <div class="flex justify-between pb-3 border-b">
                    <span class="text-gray-600">Fecha emisión:</span>
                    <span class="font-semibold">{{ $receta->fecha_emision->format('d/m/Y') }}</span>
                </div>

                @if($receta->valida_hasta)
                    <div class="flex justify-between pb-3 border-b">
                        <span class="text-gray-600">Vigencia:</span>
                        <span class="font-semibold {{ $receta->estaVencida() ? 'text-red-600' : 'text-green-600' }}">
                            {{ $receta->estaVigente() ? 'Vigente' : 'Vencida' }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-gray-600">Creada:</span>
                    <span class="font-semibold">{{ $receta->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-cog text-gray-600 mr-2"></i>
                Acciones
            </h3>

            <div class="space-y-2">
                <a href="{{ route('medico.recetas.pdf', $receta) }}" 
                   class="block bg-red-600 text-white text-center px-4 py-2 rounded hover:bg-red-700 transition text-sm"
                   target="_blank">
                    <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
                </a>

                @if($receta->estado == 'Pendiente')
                    <a href="{{ route('medico.recetas.create', ['cita_id' => $receta->cita_id]) }}" 
                       class="block bg-orange-600 text-white text-center px-4 py-2 rounded hover:bg-orange-700 transition text-sm">
                        <i class="fas fa-plus mr-2"></i>Nueva Receta
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection