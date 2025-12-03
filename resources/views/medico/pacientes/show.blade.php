{{-- ============================================ --}}
{{-- resources/views/medico/pacientes/show.blade.php --}}
{{-- Perfil Completo del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Perfil del Paciente')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-circle mr-3"></i>Perfil del Paciente
        </h1>
        <a href="{{ route('medico.pacientes.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información Principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Datos Personales --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información Personal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                    <p class="text-gray-800">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Carnet de Identidad</label>
                    <p class="text-gray-800">{{ $paciente->ci }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Edad</label>
                    <p class="text-gray-800">{{ $paciente->edad }} años</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Nacimiento</label>
                    <p class="text-gray-800">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Género</label>
                    <p class="text-gray-800">{{ $paciente->genero }}</p>
                </div>

                @if($paciente->grupo_sanguineo)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Grupo Sanguíneo</label>
                        <p class="text-gray-800">{{ $paciente->grupo_sanguineo }}</p>
                    </div>
                @endif

                @if($paciente->estado_civil)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estado Civil</label>
                        <p class="text-gray-800">{{ $paciente->estado_civil }}</p>
                    </div>
                @endif

                @if($paciente->ocupacion)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ocupación</label>
                        <p class="text-gray-800">{{ $paciente->ocupacion }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Contacto --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-address-book text-green-600 mr-2"></i>
                Información de Contacto
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($paciente->telefono)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-phone mr-1"></i>Teléfono
                        </label>
                        <p class="text-gray-800">{{ $paciente->telefono }}</p>
                    </div>
                @endif

                @if($paciente->email)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </label>
                        <p class="text-gray-800 break-all">{{ $paciente->email }}</p>
                    </div>
                @endif

                @if($paciente->direccion)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>Dirección
                        </label>
                        <p class="text-gray-800">{{ $paciente->direccion }}</p>
                    </div>
                @endif

                @if($paciente->contacto_emergencia)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-user-shield mr-1"></i>Contacto de Emergencia
                        </label>
                        <p class="text-gray-800">{{ $paciente->contacto_emergencia }}</p>
                    </div>
                @endif

                @if($paciente->telefono_emergencia)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            <i class="fas fa-phone-square mr-1"></i>Teléfono de Emergencia
                        </label>
                        <p class="text-gray-800">{{ $paciente->telefono_emergencia }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Historial de Citas con el Médico --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar-check text-purple-600 mr-2"></i>
                    Citas Conmigo ({{ $citasConMedico->count() }})
                </h2>
                <a href="{{ route('medico.pacientes.historial', $paciente) }}" 
                   class="text-purple-600 hover:underline text-sm">
                    Ver historial completo →
                </a>
            </div>

            @if($citasConMedico->count() > 0)
                <div class="space-y-3">
                    @foreach($citasConMedico as $cita)
                        <div class="border-l-4 
                            {{ $cita->estado == 'Atendida' ? 'border-green-500 bg-green-50' : 
                               'border-blue-500 bg-blue-50' }}
                            p-4 rounded-r">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 mb-1">
                                        {{ $cita->fecha->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                    </p>
                                    @if($cita->diagnostico)
                                        <p class="text-sm text-gray-700 mb-1">
                                            <strong>Diagnóstico:</strong> 
                                            {{ Str::limit($cita->diagnostico, 80) }}
                                        </p>
                                    @endif
                                    @if($cita->tratamiento)
                                        <p class="text-sm text-gray-600">
                                            <strong>Tratamiento:</strong> 
                                            {{ Str::limit($cita->tratamiento, 80) }}
                                        </p>
                                    @endif
                                    <span class="text-xs px-2 py-1 rounded-full mt-2 inline-block
                                        {{ $cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 
                                           'bg-blue-200 text-blue-800' }}">
                                        {{ $cita->estado }}
                                    </span>
                                </div>
                                <a href="{{ route('medico.citas.show', $cita) }}" 
                                   class="ml-4 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-4">No hay citas registradas</p>
            @endif
        </div>

        {{-- Última Receta --}}
        @if($ultimaReceta)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-prescription text-orange-600 mr-2"></i>
                    Última Receta Emitida
                </h2>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-800">
                                Receta #{{ $ultimaReceta->id }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $ultimaReceta->fecha_emision->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $ultimaReceta->estado == 'Pendiente' ? 'bg-yellow-200 text-yellow-800' : 
                               ($ultimaReceta->estado == 'Dispensada' ? 'bg-green-200 text-green-800' : 
                               'bg-gray-200 text-gray-800') }}">
                            {{ $ultimaReceta->estado }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-capsules mr-1"></i>
                            Medicamentos ({{ $ultimaReceta->medicamentos->count() }}):
                        </p>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            @foreach($ultimaReceta->medicamentos as $medicamento)
                                <li>{{ $medicamento->nombre_generico }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('medico.recetas.show', $ultimaReceta) }}" 
                           class="flex-1 bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-eye mr-2"></i>Ver Receta
                        </a>
                        <a href="{{ route('medico.recetas.pdf', $ultimaReceta) }}" 
                           class="flex-1 bg-red-600 text-white text-center px-4 py-2 rounded hover:bg-red-700 transition text-sm"
                           target="_blank">
                            <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Foto del Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="bg-blue-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user text-blue-600 text-5xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-xl mb-1">
                {{ $paciente->nombre }} {{ $paciente->apellido }}
            </h3>
            <p class="text-gray-600 text-sm mb-4">CI: {{ $paciente->ci }}</p>
            
            <a href="{{ route('medico.pacientes.historial', $paciente) }}" 
               class="block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition mb-2">
                <i class="fas fa-file-medical-alt mr-2"></i>Ver Historial Completo
            </a>
            
            <a href="{{ route('admin.pacientes.historial.pdf', $paciente) }}" 
               class="block bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
               target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Descargar Historial PDF
            </a>
        </div>

        {{-- Alertas Médicas --}}
        @if($paciente->alergias || $paciente->antecedentes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4">
                <h3 class="font-bold text-red-800 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas Médicas
                </h3>

                @if($paciente->alergias)
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-red-700 mb-2">
                            <i class="fas fa-allergies mr-2"></i>Alergias:
                        </p>
                        <p class="text-sm text-red-800">{{ $paciente->alergias }}</p>
                    </div>
                @endif

                @if($paciente->antecedentes)
                    <div>
                        <p class="text-sm font-semibold text-red-700 mb-2">
                            <i class="fas fa-file-medical mr-2"></i>Antecedentes:
                        </p>
                        <p class="text-sm text-red-800">{{ $paciente->antecedentes }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Estadísticas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                Estadísticas
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600 text-sm">Total Citas</span>
                    <span class="font-bold text-gray-800">{{ $citasConMedico->count() }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600 text-sm">Citas Atendidas</span>
                    <span class="font-bold text-green-600">
                        {{ $citasConMedico->where('estado', 'Atendida')->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Última Consulta</span>
                    <span class="font-bold text-gray-800">
                        @if($citasConMedico->first())
                            {{ $citasConMedico->first()->fecha->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection