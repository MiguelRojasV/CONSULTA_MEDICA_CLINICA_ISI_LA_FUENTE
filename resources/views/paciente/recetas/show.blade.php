{{-- ============================================ --}}
{{-- resources/views/paciente/recetas/show.blade.php --}}
{{-- Vista: Detalle de Receta del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Detalle de Receta Médica')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Receta Médica #{{ $receta->id }}</h1>
            <p class="text-gray-600 mt-2">Información completa de la receta emitida</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('paciente.recetas.pdf', $receta) }}" 
               target="_blank"
               class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition shadow-lg">
                <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
            </a>
            <a href="{{ route('paciente.recetas.index') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Información general --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                    Información General
                </h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $receta->estado == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                       ($receta->estado == 'Dispensada' ? 'bg-green-100 text-green-800' : 
                       'bg-red-100 text-red-800') }}">
                    {{ $receta->estado }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Fecha de Emisión</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-calendar mr-2 text-blue-600"></i>
                        {{ $receta->fecha_emision->format('d/m/Y') }}
                    </p>
                </div>

                @if($receta->valida_hasta)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Válida Hasta</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-hourglass-half mr-2 text-yellow-600"></i>
                        {{ $receta->valida_hasta->format('d/m/Y') }}
                    </p>
                    @if($receta->valida_hasta->isPast())
                        <p class="text-xs text-red-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Receta vencida
                        </p>
                    @else
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>Vigente
                        </p>
                    @endif
                </div>
                @endif

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Cita Relacionada</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-link mr-2 text-green-600"></i>
                        {{ $receta->cita->fecha->format('d/m/Y') }}
                    </p>
                    <a href="{{ route('paciente.citas.show', $receta->cita) }}" 
                       class="text-sm text-blue-600 hover:underline">
                        Ver cita completa →
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Total de Medicamentos</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-pills mr-2 text-purple-600"></i>
                        {{ $receta->medicamentos->count() }} medicamento(s)
                    </p>
                </div>
            </div>

            @if($receta->indicaciones)
            <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm font-semibold text-blue-900 mb-2">
                    <i class="fas fa-clipboard-list mr-2"></i>Indicaciones Generales
                </p>
                <p class="text-gray-800 whitespace-pre-line">{{ $receta->indicaciones }}</p>
            </div>
            @endif

            @if($receta->observaciones)
            <div class="mt-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm font-semibold text-yellow-900 mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>Observaciones
                </p>
                <p class="text-gray-800">{{ $receta->observaciones }}</p>
            </div>
            @endif
        </div>

        {{-- Médico que prescribe --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Médico que Prescribe
            </h2>

            <div class="flex items-start space-x-4">
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-md text-green-600 text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Dr(a). {{ $receta->medico->nombre }} {{ $receta->medico->apellido }}
                    </h3>
                    <p class="text-blue-600 font-semibold">{{ $receta->medico->especialidad->nombre }}</p>
                    
                    <div class="mt-3 space-y-1 text-sm text-gray-600">
                        <p><i class="fas fa-id-card mr-2"></i>Matrícula: {{ $receta->medico->matricula }}</p>
                        @if($receta->medico->registro_profesional)
                        <p><i class="fas fa-certificate mr-2"></i>Registro: {{ $receta->medico->registro_profesional }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Lista de medicamentos --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-pills text-purple-600 mr-2"></i>
                Medicamentos Prescritos
            </h2>

            @if($receta->medicamentos->count() > 0)
                <div class="space-y-4">
                    @foreach($receta->medicamentos as $index => $medicamento)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-800">
                                            {{ $medicamento->nombre_generico }}
                                        </h3>
                                    </div>

                                    @if($medicamento->nombre_comercial)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-tag mr-2"></i>
                                        Nombre comercial: {{ $medicamento->nombre_comercial }}
                                    </p>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                                        <div class="bg-blue-50 p-3 rounded">
                                            <p class="text-xs text-gray-600">Cantidad</p>
                                            <p class="font-semibold text-gray-800">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $medicamento->pivot->cantidad }} {{ $medicamento->presentacion }}
                                            </p>
                                        </div>

                                        @if($medicamento->pivot->dosis)
                                        <div class="bg-green-50 p-3 rounded">
                                            <p class="text-xs text-gray-600">Dosis</p>
                                            <p class="font-semibold text-gray-800">
                                                <i class="fas fa-syringe mr-1"></i>
                                                {{ $medicamento->pivot->dosis }}
                                            </p>
                                        </div>
                                        @endif

                                        @if($medicamento->pivot->frecuencia)
                                        <div class="bg-yellow-50 p-3 rounded">
                                            <p class="text-xs text-gray-600">Frecuencia</p>
                                            <p class="font-semibold text-gray-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $medicamento->pivot->frecuencia }}
                                            </p>
                                        </div>
                                        @endif

                                        @if($medicamento->pivot->duracion)
                                        <div class="bg-purple-50 p-3 rounded">
                                            <p class="text-xs text-gray-600">Duración</p>
                                            <p class="font-semibold text-gray-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $medicamento->pivot->duracion }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>

                                    @if($medicamento->pivot->instrucciones_especiales)
                                    <div class="mt-3 bg-red-50 p-3 rounded-lg border border-red-200">
                                        <p class="text-xs font-semibold text-red-900 mb-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Instrucciones Especiales
                                        </p>
                                        <p class="text-sm text-gray-800">{{ $medicamento->pivot->instrucciones_especiales }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-8">No hay medicamentos en esta receta</p>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Acciones --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Acciones
            </h2>

            <div class="space-y-3">
                <a href="{{ route('paciente.recetas.pdf', $receta) }}" 
                   target="_blank"
                   class="block bg-red-600 text-white p-3 rounded-lg hover:bg-red-700 transition text-center">
                    <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
                </a>

                <a href="{{ route('paciente.citas.show', $receta->cita) }}" 
                   class="block bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Ver Cita
                </a>

                <a href="{{ route('paciente.recetas.index') }}" 
                   class="block bg-gray-600 text-white p-3 rounded-lg hover:bg-gray-700 transition text-center">
                    <i class="fas fa-list mr-2"></i>Ver Todas
                </a>
            </div>
        </div>

        {{-- Información importante --}}
        <div class="bg-gradient-to-br from-purple-50 to-white rounded-lg shadow-md p-6 border border-purple-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                Información Importante
            </h2>

            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                    <p>Tome los medicamentos según las indicaciones</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-clock text-blue-600 mt-1 mr-2"></i>
                    <p>Respete los horarios y frecuencias indicadas</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-2"></i>
                    <p>No suspenda el tratamiento sin consultar</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-phone text-red-600 mt-1 mr-2"></i>
                    <p>Consulte a su médico si tiene dudas o reacciones adversas</p>
                </div>
            </div>
        </div>

        {{-- Estado de validez --}}
        @if($receta->valida_hasta)
        <div class="rounded-lg shadow-md p-6 
            {{ $receta->valida_hasta->isPast() ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
            <h2 class="text-lg font-bold mb-2
                {{ $receta->valida_hasta->isPast() ? 'text-red-800' : 'text-green-800' }}">
                <i class="fas {{ $receta->valida_hasta->isPast() ? 'fa-times-circle' : 'fa-check-circle' }} mr-2"></i>
                Estado de Validez
            </h2>
            <p class="text-sm {{ $receta->valida_hasta->isPast() ? 'text-red-700' : 'text-green-700' }}">
                @if($receta->valida_hasta->isPast())
                    Esta receta ha vencido. Consulte con su médico para una nueva prescripción.
                @else
                    Esta receta es válida hasta el {{ $receta->valida_hasta->format('d/m/Y') }}
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

@endsection

{{-- 
CARACTERÍSTICAS:
1. Vista completa con todos los detalles de la receta
2. Información del médico que prescribe
3. Lista detallada de cada medicamento
4. Dosis, frecuencia, duración por medicamento
5. Instrucciones especiales destacadas
6. Indicaciones generales y observaciones
7. Estado de validez visual
8. Botón prominente para descargar PDF
9. Sidebar con información importante
10. Diseño profesional y fácil de leer
--}}