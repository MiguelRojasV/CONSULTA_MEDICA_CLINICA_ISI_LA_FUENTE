{{-- ============================================ --}}
{{-- resources/views/paciente/perfil/show.blade.php --}}
{{-- Vista: Perfil del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Mi Perfil')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
            <p class="text-gray-600 mt-2">Información personal y médica</p>
        </div>
        <a href="{{ route('paciente.perfil.edit') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg">
            <i class="fas fa-edit mr-2"></i>Editar Perfil
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Información Personal --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información Personal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Nombre Completo</label>
                    <p class="text-gray-800 text-lg">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Cédula de Identidad (CI)</label>
                    <p class="text-gray-800 text-lg">{{ $paciente->ci }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Fecha de Nacimiento</label>
                    <p class="text-gray-800">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Edad</label>
                    <p class="text-gray-800">{{ $paciente->edad }} años</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Género</label>
                    <p class="text-gray-800">{{ $paciente->genero }}</p>
                </div>

                @if($paciente->estado_civil)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Estado Civil</label>
                    <p class="text-gray-800">{{ $paciente->estado_civil }}</p>
                </div>
                @endif

                @if($paciente->ocupacion)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Ocupación</label>
                    <p class="text-gray-800">{{ $paciente->ocupacion }}</p>
                </div>
                @endif

                @if($paciente->grupo_sanguineo)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Grupo Sanguíneo</label>
                    <p class="text-gray-800 font-bold text-red-600">{{ $paciente->grupo_sanguineo }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Información de Contacto --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-address-book text-green-600 mr-2"></i>
                Información de Contacto
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <p class="text-gray-800">{{ $paciente->email ?? $user->email }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                    <p class="text-gray-800">{{ $paciente->telefono ?? 'No registrado' }}</p>
                </div>

                @if($paciente->direccion)
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-600">Dirección</label>
                    <p class="text-gray-800">{{ $paciente->direccion }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Contacto de Emergencia --}}
        @if($paciente->contacto_emergencia || $paciente->telefono_emergencia)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-phone-volume text-red-600 mr-2"></i>
                Contacto de Emergencia
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($paciente->contacto_emergencia)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Nombre</label>
                    <p class="text-gray-800">{{ $paciente->contacto_emergencia }}</p>
                </div>
                @endif

                @if($paciente->telefono_emergencia)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                    <p class="text-gray-800 font-semibold">{{ $paciente->telefono_emergencia }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Información Médica --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-heartbeat text-purple-600 mr-2"></i>
                Información Médica
            </h2>

            @if($paciente->alergias)
            <div class="mb-4 bg-red-50 p-4 rounded-lg border border-red-200">
                <p class="text-sm font-semibold text-red-900 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Alergias Conocidas
                </p>
                <p class="text-gray-800">{{ $paciente->alergias }}</p>
            </div>
            @else
            <div class="mb-4 bg-green-50 p-4 rounded-lg border border-green-200">
                <p class="text-sm text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>No registra alergias conocidas
                </p>
            </div>
            @endif

            @if($paciente->antecedentes)
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm font-semibold text-yellow-900 mb-2">
                    <i class="fas fa-clipboard-list mr-2"></i>Antecedentes Médicos
                </p>
                <p class="text-gray-800 whitespace-pre-line">{{ $paciente->antecedentes }}</p>
            </div>
            @else
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>No registra antecedentes médicos
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Acciones --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-cogs text-blue-600 mr-2"></i>
                Acciones
            </h2>

            <div class="space-y-3">
                <a href="{{ route('paciente.perfil.edit') }}" 
                   class="block bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-edit mr-2"></i>Editar Información
                </a>
                <a href="{{ route('paciente.citas.create') }}" 
                   class="block bg-green-600 text-white p-3 rounded-lg hover:bg-green-700 transition text-center">
                    <i class="fas fa-calendar-plus mr-2"></i>Agendar Cita
                </a>
                <a href="{{ route('paciente.historial.index') }}" 
                   class="block bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition text-center">
                    <i class="fas fa-file-medical mr-2"></i>Ver Historial
                </a>
            </div>
        </div>

        {{-- Información de cuenta --}}
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg shadow-md p-6 border border-blue-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Información de Cuenta
            </h2>

            <div class="space-y-2 text-sm">
                <p><strong>Usuario:</strong> {{ $user->email }}</p>
                <p><strong>Rol:</strong> Paciente</p>
                <p><strong>Cuenta creada:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Mis Estadísticas
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="text-sm text-gray-600">Citas Totales:</span>
                    <span class="font-bold text-gray-800">{{ $paciente->citas->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="text-sm text-gray-600">Recetas:</span>
                    <span class="font-bold text-gray-800">{{ $paciente->recetas->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <span class="text-sm text-gray-600">Próximas Citas:</span>
                    <span class="font-bold text-gray-800">{{ $paciente->citasProximas()->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Ayuda --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-gray-700">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                <strong>Tip:</strong> Mantén tu información actualizada para recibir mejor atención médica.
            </p>
        </div>
    </div>
</div>

@endsection

{{-- 
CARACTERÍSTICAS:
1. Vista completa del perfil del paciente
2. Información dividida en secciones: Personal, Contacto, Emergencia, Médica
3. Alergias destacadas con color rojo
4. Antecedentes médicos visibles
5. Sidebar con acciones rápidas
6. Estadísticas personales
7. Información de cuenta
8. Diseño limpio y profesional
9. Campos opcionales solo se muestran si tienen valor
10. Botón prominente para editar
--}}