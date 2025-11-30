{{-- ============================================ --}}
{{-- resources/views/admin/recetas/show.blade.php --}}
{{-- Detalles de Receta --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Detalles de Receta')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Receta #{{ str_pad($receta->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-gray-600 mt-2">Fecha de emisión: {{ $receta->fecha_emision->format('d/m/Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.recetas.pdf', $receta) }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
            </a>
            <a href="{{ route('admin.recetas.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

{{-- Estado --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-{{ $receta->estado == 'Dispensada' ? 'green' : ($receta->estado == 'Cancelada' ? 'red' : 'yellow') }}-100 rounded-full p-4">
                <i class="fas fa-{{ $receta->estado == 'Dispensada' ? 'check-circle' : ($receta->estado == 'Cancelada' ? 'times-circle' : 'clock') }} 
                   text-{{ $receta->estado == 'Dispensada' ? 'green' : ($receta->estado == 'Cancelada' ? 'red' : 'yellow') }}-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Estado: {{ $receta->estado }}</h2>
                @if($receta->valida_hasta)
                <p class="text-gray-600">Válida hasta: {{ $receta->valida_hasta->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>
        @if($receta->estado == 'Pendiente')
        <div class="flex space-x-2">
            <form action="{{ route('admin.recetas.marcar-dispensada', $receta) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
                        onclick="return confirm('¿Marcar esta receta como dispensada?')">
                    <i class="fas fa-check mr-2"></i>Marcar Dispensada
                </button>
            </form>
            <form action="{{ route('admin.recetas.cancelar', $receta) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
                        onclick="return confirm('¿Cancelar esta receta?')">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Paciente y Médico --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Paciente
            </h2>
            <div class="space-y-2 text-sm">
                <p class="font-semibold text-gray-800">{{ $receta->paciente->nombre_completo }}</p>
                <p class="text-gray-600"><i class="fas fa-id-card mr-2"></i>CI: {{ $receta->paciente->ci }}</p>
                <p class="text-gray-600"><i class="fas fa-birthday-cake mr-2"></i>{{ $receta->paciente->edad }} años</p>
                @if($receta->paciente->alergias)
                <div class="bg-red-50 border border-red-200 p-2 rounded mt-2">
                    <p class="text-red-800 font-semibold text-xs">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Alergias:
                    </p>
                    <p class="text-red-700 text-xs">{{ $receta->paciente->alergias }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Médico --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Médico Tratante
            </h2>
            <div class="space-y-2 text-sm">
                <p class="font-semibold text-gray-800">Dr(a). {{ $receta->medico->nombre_completo }}</p>
                <p class="text-gray-600">{{ $receta->medico->especialidad->nombre }}</p>
                <p class="text-gray-600"><i class="fas fa-id-card mr-2"></i>Matrícula: {{ $receta->medico->matricula }}</p>
            </div>
        </div>

        {{-- Cita Relacionada --}}
        @if($receta->cita)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                Cita Relacionada
            </h2>
            <div class="space-y-2 text-sm">
                <p class="text-gray-600">Fecha: {{ $receta->cita->fecha->format('d/m/Y') }}</p>
                <p class="text-gray-600">Hora: {{ $receta->cita->hora->format('H:i') }}</p>
                <a href="{{ route('admin.citas.show', $receta->cita) }}" 
                   class="block text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition mt-3">
                    <i class="fas fa-eye mr-2"></i>Ver Cita
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Medicamentos y Detalles --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Medicamentos --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-pills text-orange-600 mr-2"></i>
                Medicamentos Prescritos ({{ $receta->medicamentos->count() }})
            </h2>
            <div class="space-y-4">
                @foreach($receta->medicamentos as $medicamento)
                <div class="border border-gray-200 p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $medicamento->nombre_generico }}</p>
                            @if($medicamento->nombre_comercial)
                            <p class="text-sm text-gray-600">({{ $medicamento->nombre_comercial }})</p>
                            @endif
                            <p class="text-xs text-gray-500">{{ $medicamento->presentacion }} - {{ $medicamento->concentracion }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded font-bold">
                            {{ $medicamento->pivot->cantidad }} unidades
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm mt-3 bg-gray-50 p-3 rounded">
                        <div>
                            <p class="text-gray-600 font-semibold">Dosis:</p>
                            <p class="text-gray-800">{{ $medicamento->pivot->dosis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 font-semibold">Frecuencia:</p>
                            <p class="text-gray-800">{{ $medicamento->pivot->frecuencia }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 font-semibold">Duración:</p>
                            <p class="text-gray-800">{{ $medicamento->pivot->duracion }}</p>
                        </div>
                    </div>
                    @if($medicamento->pivot->instrucciones_especiales)
                    <div class="mt-2 bg-yellow-50 border-l-4 border-yellow-500 p-2">
                        <p class="text-xs font-semibold text-yellow-800">Instrucciones especiales:</p>
                        <p class="text-xs text-yellow-700">{{ $medicamento->pivot->instrucciones_especiales }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Indicaciones --}}
        @if($receta->indicaciones)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list text-green-600 mr-2"></i>
                Indicaciones Generales
            </h2>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                <p class="text-gray-700 leading-relaxed">{{ $receta->indicaciones }}</p>
            </div>
        </div>
        @endif

        {{-- Observaciones --}}
        @if($receta->observaciones)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-comment text-yellow-600 mr-2"></i>
                Observaciones
            </h2>
            <p class="text-gray-700 leading-relaxed">{{ $receta->observaciones }}</p>
        </div>
        @endif
    </div>
</div>
@endsection