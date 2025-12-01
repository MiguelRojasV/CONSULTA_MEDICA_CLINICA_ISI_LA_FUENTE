{{-- ============================================ --}}
{{-- resources/views/admin/reportes/busqueda.blade.php --}}
{{-- Búsqueda Avanzada en el Sistema --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Búsqueda Avanzada')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Búsqueda Avanzada</h1>
            <p class="text-gray-600 mt-2">Realiza búsquedas personalizadas en todo el sistema</p>
        </div>
        <a href="{{ route('admin.reportes.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

{{-- Formulario de Búsqueda --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.busqueda') }}" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tipo de búsqueda --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Buscar en
                </label>
                <select name="tipo" id="tipo_busqueda" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="paciente" {{ request('tipo') == 'paciente' ? 'selected' : '' }}>
                        <i class="fas fa-user"></i> Pacientes
                    </option>
                    <option value="medico" {{ request('tipo') == 'medico' ? 'selected' : '' }}>
                        <i class="fas fa-user-md"></i> Médicos
                    </option>
                    <option value="cita" {{ request('tipo') == 'cita' ? 'selected' : '' }}>
                        <i class="fas fa-calendar"></i> Citas
                    </option>
                </select>
            </div>

            {{-- Criterio de búsqueda --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>Buscar por
                </label>
                <select name="criterio" id="criterio_busqueda" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccione tipo primero...</option>
                    
                    {{-- Opciones para Pacientes --}}
                    <optgroup label="Pacientes" id="criterios_paciente" style="display: none;">
                        <option value="nombre" {{ request('criterio') == 'nombre' ? 'selected' : '' }}>Nombre/Apellido</option>
                        <option value="ci" {{ request('criterio') == 'ci' ? 'selected' : '' }}>CI</option>
                        <option value="mes" {{ request('criterio') == 'mes' ? 'selected' : '' }}>Mes de Registro</option>
                    </optgroup>
                    
                    {{-- Opciones para Médicos --}}
                    <optgroup label="Médicos" id="criterios_medico" style="display: none;">
                        <option value="nombre" {{ request('criterio') == 'nombre' ? 'selected' : '' }}>Nombre/Apellido</option>
                        <option value="especialidad" {{ request('criterio') == 'especialidad' ? 'selected' : '' }}>Especialidad</option>
                        <option value="matricula" {{ request('criterio') == 'matricula' ? 'selected' : '' }}>Matrícula</option>
                    </optgroup>
                    
                    {{-- Opciones para Citas --}}
                    <optgroup label="Citas" id="criterios_cita" style="display: none;">
                        <option value="fecha" {{ request('criterio') == 'fecha' ? 'selected' : '' }}>Fecha Específica</option>
                        <option value="mes" {{ request('criterio') == 'mes' ? 'selected' : '' }}>Mes</option>
                    </optgroup>
                </select>
            </div>

            {{-- Valor de búsqueda --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-keyboard mr-1"></i>Valor
                </label>
                <input type="text" name="valor" id="valor_busqueda" 
                       value="{{ request('valor') }}"
                       placeholder="Ingrese el valor a buscar"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <small id="helper_text" class="text-xs text-gray-500 mt-1 block"></small>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.busqueda') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-redo mr-2"></i>Limpiar
            </a>
            <button type="submit" 
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
        </div>
    </form>
</div>

{{-- Resultados de Búsqueda --}}
@if(request()->filled(['tipo', 'criterio', 'valor']))
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-list text-indigo-600 mr-2"></i>
            Resultados de la Búsqueda
        </h2>
        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
            {{ $resultados->count() }} resultado(s)
        </span>
    </div>

    @if($resultados->count() > 0)
        {{-- Resultados para PACIENTES --}}
        @if(request('tipo') == 'paciente')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Edad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Género</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Citas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($resultados as $paciente)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $paciente->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500">{{ $paciente->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $paciente->ci }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $paciente->edad }} años</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $paciente->genero == 'Masculino' ? 'bg-blue-100 text-blue-800' : 
                                   ($paciente->genero == 'Femenino' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $paciente->genero }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $paciente->telefono }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-bold rounded bg-purple-100 text-purple-800">
                                {{ $paciente->citas->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.pacientes.show', $paciente) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pacientes.edit', $paciente) }}" 
                               class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Resultados para MÉDICOS --}}
        @if(request('tipo') == 'medico')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Citas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($resultados as $medico)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                    <i class="fas fa-user-md text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dr(a). {{ $medico->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500">{{ $medico->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $medico->especialidad->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $medico->matricula }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $medico->estado == 'Activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $medico->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-800">
                                {{ $medico->citas->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.medicos.show', $medico) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.medicos.edit', $medico) }}" 
                               class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Resultados para CITAS --}}
        @if(request('tipo') == 'cita')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($resultados as $cita)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $cita->fecha->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $cita->hora->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $cita->paciente->nombre_completo }}</p>
                            <p class="text-xs text-gray-500">CI: {{ $cita->paciente->ci }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">Dr(a). {{ $cita->medico->nombre_completo }}</p>
                            <p class="text-xs text-gray-500">{{ $cita->medico->especialidad->nombre }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($cita->motivo, 40) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $cita->estado == 'Atendida' ? 'bg-green-100 text-green-800' : 
                                   ($cita->estado == 'Cancelada' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $cita->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.citas.show', $cita) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.citas.edit', $cita) }}" 
                               class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    @else
        {{-- Sin resultados --}}
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron resultados</h3>
            <p class="text-gray-500">Intenta con otros criterios de búsqueda</p>
        </div>
    @endif
</div>
@else
    {{-- Mensaje inicial --}}
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-search text-indigo-600 text-6xl mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Búsqueda Avanzada</h3>
        <p class="text-gray-600 mb-6">
            Selecciona el tipo de búsqueda, el criterio y el valor para encontrar información específica en el sistema
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto text-left">
            <div class="bg-blue-50 p-4 rounded-lg">
                <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Pacientes</h4>
                <p class="text-sm text-gray-600">Busca por nombre, CI o mes de registro</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <i class="fas fa-user-md text-green-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Médicos</h4>
                <p class="text-sm text-gray-600">Busca por nombre, especialidad o matrícula</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <i class="fas fa-calendar-alt text-purple-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Citas</h4>
                <p class="text-sm text-gray-600">Busca por fecha específica o mes</p>
            </div>
        </div>
    </div>
@endif

{{-- JavaScript para búsqueda dinámica --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoBusqueda = document.getElementById('tipo_busqueda');
    const criterioBusqueda = document.getElementById('criterio_busqueda');
    const valorBusqueda = document.getElementById('valor_busqueda');
    const helperText = document.getElementById('helper_text');

    const criteriosPaciente = document.getElementById('criterios_paciente');
    const criteriosMedico = document.getElementById('criterios_medico');
    const criteriosCita = document.getElementById('criterios_cita');

    tipoBusqueda.addEventListener('change', function() {
        // Ocultar todos los grupos
        criteriosPaciente.style.display = 'none';
        criteriosMedico.style.display = 'none';
        criteriosCita.style.display = 'none';
        
        // Resetear criterio
        criterioBusqueda.value = '';
        valorBusqueda.value = '';
        helperText.textContent = '';

        // Mostrar grupo correspondiente
        if (this.value === 'paciente') {
            criteriosPaciente.style.display = 'block';
        } else if (this.value === 'medico') {
            criteriosMedico.style.display = 'block';
        } else if (this.value === 'cita') {
            criteriosCita.style.display = 'block';
        }
    });

    criterioBusqueda.addEventListener('change', function() {
        valorBusqueda.value = '';
        
        // Cambiar tipo de input según criterio
        if (this.value === 'fecha') {
            valorBusqueda.type = 'date';
            helperText.textContent = 'Seleccione una fecha específica';
        } else if (this.value === 'mes') {
            valorBusqueda.type = 'month';
            helperText.textContent = 'Seleccione mes y año';
        } else if (this.value === 'nombre') {
            valorBusqueda.type = 'text';
            helperText.textContent = 'Ingrese nombre o apellido';
        } else if (this.value === 'ci') {
            valorBusqueda.type = 'text';
            helperText.textContent = 'Ingrese el CI (7-8 dígitos)';
        } else if (this.value === 'especialidad') {
            valorBusqueda.type = 'text';
            helperText.textContent = 'Ingrese la especialidad';
        } else if (this.value === 'matricula') {
            valorBusqueda.type = 'text';
            helperText.textContent = 'Ingrese la matrícula profesional';
        } else {
            valorBusqueda.type = 'text';
            helperText.textContent = '';
        }
    });

    // Cargar estado inicial si hay valores
    if (tipoBusqueda.value) {
        tipoBusqueda.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
/* Estilos para ocultar optgroups en navegadores que no lo soportan nativamente */
optgroup {
    display: none;
}
optgroup[style*="display: block"] {
    display: block;
}
</style>
@endsection