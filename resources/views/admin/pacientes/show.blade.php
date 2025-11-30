{{-- ============================================ --}}
{{-- resources/views/admin/pacientes/show.blade.php --}}
{{-- Vista de Detalles del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Detalles del Paciente')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $paciente->nombre_completo }}</h1>
            <p class="text-gray-600 mt-2">CI: {{ $paciente->ci }} | Edad: {{ $paciente->edad }} años</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.pacientes.historial.pdf', $paciente) }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>Descargar Historial
            </a>
            <a href="{{ route('admin.pacientes.edit', $paciente) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.pacientes.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

{{-- Estadísticas Rápidas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Citas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalCitas }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Citas Atendidas</p>
                <p class="text-3xl font-bold mt-2">{{ $citasAtendidas }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Recetas Emitidas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalRecetas }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-prescription text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información Personal --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información Personal
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Nombre Completo:</span>
                    <span class="text-gray-800">{{ $paciente->nombre_completo }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">CI:</span>
                    <span class="text-gray-800">{{ $paciente->ci }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Fecha Nacimiento:</span>
                    <span class="text-gray-800">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Edad:</span>
                    <span class="text-gray-800">{{ $paciente->edad }} años</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Género:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        {{ $paciente->genero == 'Masculino' ? 'bg-blue-100 text-blue-800' : 
                           ($paciente->genero == 'Femenino' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $paciente->genero }}
                    </span>
                </div>
                @if($paciente->estado_civil)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Estado Civil:</span>
                    <span class="text-gray-800">{{ $paciente->estado_civil }}</span>
                </div>
                @endif
                @if($paciente->ocupacion)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Ocupación:</span>
                    <span class="text-gray-800">{{ $paciente->ocupacion }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-address-book text-green-600 mr-2"></i>
                Contacto
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex items-start">
                    <i class="fas fa-phone text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-600">Teléfono</p>
                        <p class="text-gray-800">{{ $paciente->telefono }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-600">Email</p>
                        <p class="text-gray-800">{{ $paciente->email }}</p>
                    </div>
                </div>
                @if($paciente->direccion)
                <div class="flex items-start">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-600">Dirección</p>
                        <p class="text-gray-800">{{ $paciente->direccion }}</p>
                    </div>
                </div>
                @endif
                @if($paciente->contacto_emergencia)
                <div class="bg-red-50 border border-red-200 p-3 rounded mt-3">
                    <p class="font-semibold text-red-800 mb-1">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Contacto de Emergencia
                    </p>
                    <p class="text-sm text-red-700">{{ $paciente->contacto_emergencia }}</p>
                    @if($paciente->telefono_emergencia)
                    <p class="text-sm text-red-700">Tel: {{ $paciente->telefono_emergencia }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-heartbeat text-red-600 mr-2"></i>
                Datos Médicos
            </h2>
            <div class="space-y-3 text-sm">
                @if($paciente->grupo_sanguineo)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Grupo Sanguíneo:</span>
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">
                        {{ $paciente->grupo_sanguineo }}
                    </span>
                </div>
                @endif
                
                @if($paciente->alergias)
                <div class="bg-red-50 border border-red-200 p-3 rounded">
                    <p class="font-semibold text-red-800 mb-1">
                        <i class="fas fa-allergies mr-2"></i>Alergias
                    </p>
                    <p class="text-sm text-red-700">{{ $paciente->alergias }}</p>
                </div>
                @endif
                
                @if($paciente->antecedentes)
                <div class="bg-yellow-50 border border-yellow-200 p-3 rounded">
                    <p class="font-semibold text-yellow-800 mb-1">
                        <i class="fas fa-notes-medical mr-2"></i>Antecedentes
                    </p>
                    <p class="text-sm text-yellow-700">{{ $paciente->antecedentes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Historial y Citas --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Citas del Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Historial de Citas
            </h2>
            
            @if($paciente->citas->count() > 0)
                <div class="space-y-3">
                    @foreach($paciente->citas->take(10) as $cita)
                        <div class="border-l-4 
                            {{ $cita->estado == 'Atendida' ? 'border-green-500 bg-green-50' : 
                               ($cita->estado == 'Cancelada' ? 'border-red-500 bg-red-50' : 'border-blue-500 bg-blue-50') }}
                            p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-user-md mr-1"></i>
                                        Dr(a). {{ $cita->medico->nombre_completo }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $cita->medico->especialidad->nombre }}
                                    </p>
                                    @if($cita->motivo)
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>Motivo:</strong> {{ $cita->motivo }}
                                    </p>
                                    @endif
                                    @if($cita->diagnostico)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Diagnóstico:</strong> {{ Str::limit($cita->diagnostico, 100) }}
                                    </p>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 
                                           ($cita->estado == 'Cancelada' ? 'bg-red-200 text-red-800' : 'bg-blue-200 text-blue-800') }}">
                                        {{ $cita->estado }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No tiene citas registradas</p>
                </div>
            @endif
        </div>

        {{-- Recetas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-prescription text-purple-600 mr-2"></i>
                Recetas Recientes
            </h2>
            
            @if($paciente->recetas->count() > 0)
                <div class="space-y-3">
                    @foreach($paciente->recetas->take(5) as $receta)
                        <div class="border border-gray-200 p-4 rounded-lg hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ $receta->fecha_emision->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Dr(a). {{ $receta->medico->nombre_completo }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $receta->medicamentos->count() }} medicamento(s)
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-prescription-bottle text-4xl mb-3"></i>
                    <p>No tiene recetas emitidas</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
