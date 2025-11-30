{{-- ============================================ --}}
{{-- resources/views/admin/medicos/show.blade.php --}}
{{-- Vista de Detalles del Médico --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Detalles del Médico')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dr(a). {{ $medico->nombre_completo }}</h1>
            <p class="text-gray-600 mt-2">{{ $medico->especialidad->nombre }} | Matrícula: {{ $medico->matricula }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.medicos.edit', $medico) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.medicos.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Pacientes Atendidos</p>
                <p class="text-3xl font-bold mt-2">{{ $totalPacientes }}</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información del Médico --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Información Personal
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Nombre Completo:</span>
                    <span class="text-gray-800">{{ $medico->nombre_completo }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">CI:</span>
                    <span class="text-gray-800">{{ $medico->ci }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Email:</span>
                    <span class="text-gray-800">{{ $medico->email }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Teléfono:</span>
                    <span class="text-gray-800">{{ $medico->telefono }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Estado:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        {{ $medico->estado == 'Activo' ? 'bg-green-100 text-green-800' : 
                           ($medico->estado == 'Inactivo' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $medico->estado }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-stethoscope text-purple-600 mr-2"></i>
                Datos Profesionales
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Especialidad:</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-bold">
                        {{ $medico->especialidad->nombre }}
                    </span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Matrícula:</span>
                    <span class="text-gray-800">{{ $medico->matricula }}</span>
                </div>
                @if($medico->registro_profesional)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Registro:</span>
                    <span class="text-gray-800">{{ $medico->registro_profesional }}</span>
                </div>
                @endif
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Experiencia:</span>
                    <span class="text-gray-800">{{ $medico->años_experiencia }} años</span>
                </div>
                @if($medico->turno)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Turno:</span>
                    <span class="text-gray-800">{{ $medico->turno }}</span>
                </div>
                @endif
                @if($medico->consultorio)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Consultorio:</span>
                    <span class="text-gray-800">{{ $medico->consultorio }}</span>
                </div>
                @endif
                @if($medico->fecha_contratacion)
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="font-semibold text-gray-600">Contratación:</span>
                    <span class="text-gray-800">{{ $medico->fecha_contratacion->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($medico->formacion_continua)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                Formación Continua
            </h2>
            <p class="text-sm text-gray-700">{{ $medico->formacion_continua }}</p>
        </div>
        @endif
    </div>

    {{-- Citas y Actividad --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Próximas Citas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                Próximas Citas
            </h2>
            
            @if($proximasCitas->count() > 0)
                <div class="space-y-3">
                    @foreach($proximasCitas as $cita)
                        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $cita->paciente->nombre_completo }}
                                    </p>
                                    @if($cita->motivo)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Motivo:</strong> {{ Str::limit($cita->motivo, 80) }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $cita->estado == 'Programada' ? 'bg-yellow-200 text-yellow-800' : 'bg-blue-200 text-blue-800' }}">
                                    {{ $cita->estado }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No tiene citas próximas programadas</p>
                </div>
            @endif
        </div>

        {{-- Historial de Citas Atendidas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history text-green-600 mr-2"></i>
                Historial de Atenciones Recientes
            </h2>
            
            @if($medico->citas()->where('estado', 'Atendida')->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($medico->citas()->where('estado', 'Atendida')->latest('fecha')->take(10)->get() as $cita)
                        <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ $cita->fecha->format('d/m/Y') }} - {{ $cita->hora->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $cita->paciente->nombre_completo }}
                                    </p>
                                    @if($cita->diagnostico)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Diagnóstico:</strong> {{ Str::limit($cita->diagnostico, 100) }}
                                    </p>
                                    @endif
                                </div>
                                <a href="{{ route('admin.citas.show', $cita) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No hay citas atendidas registradas</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection