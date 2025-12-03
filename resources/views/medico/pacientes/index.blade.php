{{-- ============================================ --}}
{{-- resources/views/medico/pacientes/index.blade.php --}}
{{-- Lista de Pacientes Atendidos por el Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mis Pacientes')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-users mr-3"></i>Mis Pacientes
    </h1>
    <p class="text-gray-600 mt-2">Pacientes que ha atendido</p>
</div>

{{-- Buscador --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="{{ route('medico.pacientes.index') }}" method="GET" class="flex items-end space-x-4">
        <div class="flex-1">
            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-1"></i>Buscar Paciente
            </label>
            <input type="text" 
                   id="buscar" 
                   name="buscar" 
                   value="{{ request('buscar') }}"
                   placeholder="Nombre, apellido o CI..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>
        
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-search mr-2"></i>Buscar
        </button>

        @if(request('buscar'))
            <a href="{{ route('medico.pacientes.index') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-times mr-2"></i>Limpiar
            </a>
        @endif
    </form>
</div>

{{-- Lista de Pacientes --}}
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            Total: {{ $pacientes->total() }} paciente(s)
        </h2>
    </div>

    @if($pacientes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pacientes as $paciente)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg mb-1 truncate">
                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                            </h3>
                            
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>
                                    <i class="fas fa-id-card mr-2 w-4"></i>
                                    CI: {{ $paciente->ci }}
                                </p>
                                <p>
                                    <i class="fas fa-birthday-cake mr-2 w-4"></i>
                                    {{ $paciente->edad }} años
                                </p>
                                <p>
                                    <i class="fas fa-venus-mars mr-2 w-4"></i>
                                    {{ $paciente->genero }}
                                </p>
                                @if($paciente->telefono)
                                    <p>
                                        <i class="fas fa-phone mr-2 w-4"></i>
                                        {{ $paciente->telefono }}
                                    </p>
                                @endif
                            </div>

                            {{-- Alertas --}}
                            @if($paciente->alergias || $paciente->antecedentes)
                                <div class="mt-2">
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Alertas médicas
                                    </span>
                                </div>
                            @endif

                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('medico.pacientes.show', $paciente) }}" 
                                   class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded hover:bg-blue-700 transition text-sm">
                                    <i class="fas fa-eye mr-1"></i>Ver Perfil
                                </a>
                                <a href="{{ route('medico.pacientes.historial', $paciente) }}" 
                                   class="flex-1 bg-purple-600 text-white text-center px-3 py-2 rounded hover:bg-purple-700 transition text-sm">
                                    <i class="fas fa-file-medical mr-1"></i>Historial
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $pacientes->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-user-slash text-6xl mb-4"></i>
            <p class="text-lg font-semibold">
                @if(request('buscar'))
                    No se encontraron pacientes con "{{ request('buscar') }}"
                @else
                    Aún no ha atendido pacientes
                @endif
            </p>
        </div>
    @endif
</div>
@endsection