{{-- ============================================ --}}
{{-- resources/views/medico/perfil/index.blade.php --}}
{{-- Vista: Mostrar Perfil del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mi Perfil Profesional')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil Profesional</h1>
            <p class="text-gray-600 mt-2">Información personal y datos profesionales</p>
        </div>
        <a href="{{ route('medico.perfil.edit') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg">
            <i class="fas fa-edit mr-2"></i>Editar Perfil
        </a>
    </div>
</div>

{{-- Estadísticas Rápidas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-users text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pacientes Atendidos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalPacientes }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total de Citas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCitas }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Citas Atendidas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $citasAtendidas }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-red-100 rounded-full p-3">
                <i class="fas fa-prescription text-red-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Recetas Emitidas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $recetasEmitidas }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información Personal --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información Personal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Nombre Completo</label>
                    <p class="text-gray-800 text-lg">{{ $medico->nombre }} {{ $medico->apellido }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Cédula de Identidad (CI)</label>
                    <p class="text-gray-800 text-lg">{{ $medico->ci }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <p class="text-gray-800">{{ $medico->email ?? $user->email }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                    <p class="text-gray-800">{{ $medico->telefono ?? 'No registrado' }}</p>
                </div>
            </div>
        </div>

        {{-- Información Profesional --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-user-md text-green-600 mr-2"></i>
                Información Profesional
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Especialidad</label>
                    <p class="text-gray-800 text-lg font-semibold">{{ $medico->especialidad->nombre }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Matrícula Profesional</label>
                    <p class="text-gray-800">{{ $medico->matricula }}</p>
                </div>

                @if($medico->registro_profesional)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Registro Profesional</label>
                    <p class="text-gray-800">{{ $medico->registro_profesional }}</p>
                </div>
                @endif

                @if($medico->años_experiencia)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Años de Experiencia</label>
                    <p class="text-gray-800">{{ $medico->años_experiencia }} años</p>
                </div>
                @endif

                @if($medico->turno)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Turno de Trabajo</label>
                    <p class="text-gray-800">{{ $medico->turno }}</p>
                </div>
                @endif

                @if($medico->consultorio)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Consultorio</label>
                    <p class="text-gray-800">{{ $medico->consultorio }}</p>
                </div>
                @endif

                @if($medico->fecha_contratacion)
                <div>
                    <label class="text-sm font-semibold text-gray-600">Fecha de Contratación</label>
                    <p class="text-gray-800">{{ $medico->fecha_contratacion->format('d/m/Y') }}</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-semibold text-gray-600">Estado</label>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $medico->estado == 'Activo' ? 'bg-green-100 text-green-800' : 
                           ($medico->estado == 'Licencia' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $medico->estado }}
                    </span>
                </div>
            </div>

            @if($medico->formacion_continua)
            <div class="mt-4">
                <label class="text-sm font-semibold text-gray-600">Formación Continua</label>
                <p class="text-gray-700 mt-2 bg-gray-50 p-3 rounded-lg">{{ $medico->formacion_continua }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar con acciones --}}
    <div class="space-y-6">
        {{-- Acciones rápidas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-cogs text-blue-600 mr-2"></i>
                Acciones
            </h2>
            <div class="space-y-3">
                <a href="{{ route('medico.perfil.edit') }}" 
                   class="block bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Información
                </a>
                <a href="{{ route('medico.perfil.password.edit') }}" 
                   class="block bg-green-600 text-white p-3 rounded-lg hover:bg-green-700 transition text-center">
                    <i class="fas fa-key mr-2"></i>
                    Cambiar Contraseña
                </a>
                <a href="{{ route('medico.perfil.horarios') }}" 
                   class="block bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition text-center">
                    <i class="fas fa-clock mr-2"></i>
                    Mis Horarios
                </a>
            </div>
        </div>

        {{-- Información de cuenta --}}
        <div class="bg-gradient-to-br from-green-50 to-white rounded-lg shadow-md p-6 border border-green-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                Información de Cuenta
            </h2>
            <div class="space-y-2 text-sm">
                <p><strong>Usuario:</strong> {{ $user->email }}</p>
                <p><strong>Rol:</strong> Médico</p>
                <p><strong>Cuenta creada:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Ayuda --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-gray-700">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                <strong>Tip:</strong> Mantén tu información actualizada para que los pacientes puedan contactarte fácilmente.
            </p>
        </div>
    </div>
</div>
@endsection

{{-- 
CARACTERÍSTICAS DE ESTA VISTA:
1. Muestra toda la información del médico de forma organizada
2. Estadísticas rápidas en la parte superior
3. Información dividida en: Personal y Profesional
4. Sidebar con acciones rápidas
5. Uso de colores distintivos para el estado
6. Campos opcionales solo se muestran si tienen valor
7. Diseño responsive y profesional
8. Botones de acceso rápido a editar, cambiar contraseña y horarios
--}}