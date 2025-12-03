{{-- ============================================ --}}
{{-- resources/views/medico/perfil/show.blade.php --}}
{{-- Perfil del Médico (Vista) --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mi Perfil')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-md mr-3"></i>Mi Perfil Profesional
        </h1>
        <a href="{{ route('medico.perfil.edit') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-edit mr-2"></i>Editar Perfil
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información Principal --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Datos Personales --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-id-card text-blue-600 mr-2"></i>
                Información Personal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                    <p class="text-gray-800">{{ $medico->nombre }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Apellido</label>
                    <p class="text-gray-800">{{ $medico->apellido }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Carnet de Identidad</label>
                    <p class="text-gray-800">{{ $medico->ci }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <p class="text-gray-800 break-all">{{ $medico->email ?? 'No especificado' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                    <p class="text-gray-800">{{ $medico->telefono ?? 'No especificado' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        {{ $medico->estado == 'Activo' ? 'bg-green-200 text-green-800' : 
                           ($medico->estado == 'Inactivo' ? 'bg-red-200 text-red-800' : 
                           'bg-yellow-200 text-yellow-800') }}">
                        {{ $medico->estado }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Datos Profesionales --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-stethoscope text-green-600 mr-2"></i>
                Información Profesional
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Especialidad</label>
                    <p class="text-gray-800 font-semibold text-lg">{{ $medico->especialidad->nombre }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Matrícula Profesional</label>
                    <p class="text-gray-800">{{ $medico->matricula }}</p>
                </div>

                @if($medico->registro_profesional)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Registro Profesional</label>
                        <p class="text-gray-800">{{ $medico->registro_profesional }}</p>
                    </div>
                @endif

                @if($medico->años_experiencia)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Años de Experiencia</label>
                        <p class="text-gray-800">{{ $medico->años_experiencia }} años</p>
                    </div>
                @endif

                @if($medico->turno)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Turno de Trabajo</label>
                        <p class="text-gray-800">{{ $medico->turno }}</p>
                    </div>
                @endif

                @if($medico->consultorio)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Consultorio</label>
                        <p class="text-gray-800">{{ $medico->consultorio }}</p>
                    </div>
                @endif

                @if($medico->fecha_contratacion)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Contratación</label>
                        <p class="text-gray-800">{{ $medico->fecha_contratacion->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>

            @if($medico->formacion_continua)
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Formación Continua</label>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                        <p class="text-gray-800 whitespace-pre-line">{{ $medico->formacion_continua }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Estadísticas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                Estadísticas Profesionales
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                    <p class="text-blue-800 font-bold text-2xl">{{ $totalCitas }}</p>
                    <p class="text-blue-600 text-sm">Total Citas</p>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                    <p class="text-green-800 font-bold text-2xl">{{ $citasAtendidas }}</p>
                    <p class="text-green-600 text-sm">Atendidas</p>
                </div>

                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r">
                    <p class="text-purple-800 font-bold text-2xl">{{ $totalPacientes }}</p>
                    <p class="text-purple-600 text-sm">Pacientes</p>
                </div>

                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r">
                    <p class="text-orange-800 font-bold text-2xl">{{ $recetasEmitidas }}</p>
                    <p class="text-orange-600 text-sm">Recetas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Foto de Perfil --}}
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="bg-green-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-md text-green-600 text-5xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-xl mb-1">
                Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
            </h3>
            <p class="text-gray-600 mb-1">{{ $medico->especialidad->nombre }}</p>
            <p class="text-sm text-gray-500">CI: {{ $medico->ci }}</p>
        </div>

        {{-- Acciones Rápidas --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Acciones
            </h3>

            <div class="space-y-2">
                <a href="{{ route('medico.perfil.edit') }}" 
                   class="block bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-2"></i>Editar Perfil
                </a>

                <a href="{{ route('medico.perfil.password.edit') }}" 
                   class="block bg-yellow-600 text-white text-center px-4 py-2 rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-key mr-2"></i>Cambiar Contraseña
                </a>

                <a href="{{ route('medico.perfil.horarios') }}" 
                   class="block bg-purple-600 text-white text-center px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-clock mr-2"></i>Ver Horarios
                </a>
            </div>
        </div>

        {{-- Información de Cuenta --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-user-circle text-gray-600 mr-2"></i>
                Cuenta
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Usuario:</span>
                    <span class="font-semibold">{{ $user->name }}</span>
                </div>

                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-semibold break-all">{{ $user->email }}</span>
                </div>

                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Rol:</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Cuenta creada:</span>
                    <span class="font-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Info Adicional --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r p-4">
            <h3 class="font-bold text-blue-800 mb-2 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Información
            </h3>
            <p class="text-xs text-blue-700">
                Mantenga su información actualizada para un mejor servicio. 
                Si necesita cambiar su especialidad o datos profesionales, 
                contacte al administrador.
            </p>
        </div>
    </div>
</div>
@endsection