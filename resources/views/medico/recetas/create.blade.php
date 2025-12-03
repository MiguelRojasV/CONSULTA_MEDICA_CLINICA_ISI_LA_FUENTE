{{-- ============================================ --}}
{{-- resources/views/medico/recetas/create.blade.php --}}
{{-- Formulario para Crear Nueva Receta --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Nueva Receta')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-prescription-bottle-alt mr-3"></i>Nueva Receta Médica
        </h1>
        <a href="{{ route('medico.recetas.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Cancelar
        </a>
    </div>
</div>

<form action="{{ route('medico.recetas.store') }}" method="POST" id="formReceta">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulario Principal --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Selección de Cita --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-injured text-blue-600 mr-2"></i>
                    1. Seleccionar Paciente/Cita
                </h2>

                <div>
                    <label for="cita_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Cita Atendida <span class="text-red-500">*</span>
                    </label>
                    <select id="cita_id" 
                            name="cita_id" 
                            required
                            onchange="cargarPaciente(this)"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">-- Seleccione una cita atendida --</option>
                        @foreach($citasSinReceta as $citaDisponible)
                            <option value="{{ $citaDisponible->id }}" 
                                    data-paciente="{{ $citaDisponible->paciente->nombre }} {{ $citaDisponible->paciente->apellido }}"
                                    data-ci="{{ $citaDisponible->paciente->ci }}"
                                    {{ old('cita_id', $cita->id ?? '') == $citaDisponible->id ? 'selected' : '' }}>
                                {{ $citaDisponible->fecha->format('d/m/Y') }} - 
                                {{ $citaDisponible->paciente->nombre }} {{ $citaDisponible->paciente->apellido }} 
                                (CI: {{ $citaDisponible->paciente->ci }})
                            </option>
                        @endforeach
                    </select>
                    @error('cita_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if($citasSinReceta->count() == 0)
                        <div class="mt-3 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded-r">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                No hay citas atendidas sin receta. Primero debe atender una cita.
                            </p>
                        </div>
                    @endif
                </div>

                <div id="infoPaciente" class="mt-4 hidden bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                    <p class="text-sm font-semibold text-blue-800 mb-1">
                        <i class="fas fa-user mr-2"></i>Paciente Seleccionado:
                    </p>
                    <p class="text-sm text-blue-700" id="nombrePaciente"></p>
                </div>
            </div>

            {{-- Indicaciones --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-notes-medical text-green-600 mr-2"></i>
                    2. Indicaciones Generales
                </h2>

                <div class="mb-4">
                    <label for="indicaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        Indicaciones del Tratamiento <span class="text-red-500">*</span>
                    </label>
                    <textarea id="indicaciones" 
                              name="indicaciones" 
                              rows="4"
                              required
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Ej: Tomar los medicamentos con alimentos. Mantener reposo relativo. Evitar exposición al sol...">{{ old('indicaciones') }}</textarea>
                    @error('indicaciones')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        Observaciones Adicionales
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Notas adicionales o precauciones especiales...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="valida_hasta" class="block text-sm font-semibold text-gray-700 mb-2">
                        Válida Hasta
                    </label>
                    <input type="date" 
                           id="valida_hasta" 
                           name="valida_hasta" 
                           value="{{ old('valida_hasta', now()->addMonth()->format('Y-m-d')) }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @error('valida_hasta')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Medicamentos --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-pills text-orange-600 mr-2"></i>
                        3. Medicamentos Prescritos
                    </h2>
                    <button type="button" 
                            onclick="agregarMedicamento()"
                            class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition text-sm">
                        <i class="fas fa-plus mr-2"></i>Agregar Medicamento
                    </button>
                </div>

                <div id="listaMedicamentos" class="space-y-4">
                    {{-- Los medicamentos se agregarán dinámicamente aquí --}}
                </div>

                <div id="sinMedicamentos" class="text-center py-8 text-gray-500">
                    <i class="fas fa-capsules text-4xl mb-3"></i>
                    <p>No hay medicamentos agregados</p>
                    <p class="text-sm mt-2">Haga clic en "Agregar Medicamento" para comenzar</p>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('medico.recetas.index') }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-save mr-2"></i>Guardar Receta
                    </button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Lista de Medicamentos Disponibles --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-database text-purple-600 mr-2"></i>
                    Medicamentos Disponibles
                </h3>

                <div class="mb-3">
                    <input type="text" 
                           id="buscarMedicamento" 
                           onkeyup="filtrarMedicamentos()"
                           placeholder="Buscar medicamento..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div id="medicamentosDisponibles" class="max-h-96 overflow-y-auto space-y-2">
                    @foreach($medicamentos as $medicamento)
                        <div class="medicamento-item border border-gray-200 rounded p-3 hover:bg-gray-50 transition cursor-pointer"
                             data-nombre="{{ strtolower($medicamento->nombre_generico) }}"
                             onclick="seleccionarMedicamento({{ $medicamento->id }}, '{{ $medicamento->nombre_generico }}', '{{ $medicamento->presentacion }}', '{{ $medicamento->dosis }}')">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $medicamento->nombre_generico }}
                            </p>
                            <p class="text-xs text-gray-600">
                                {{ $medicamento->presentacion }} - {{ $medicamento->dosis }}
                            </p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs px-2 py-1 rounded {{ $medicamento->disponibilidad > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    Stock: {{ $medicamento->disponibilidad }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Ayuda --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r p-4">
                <h3 class="font-bold text-blue-800 mb-2 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>Instrucciones
                </h3>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Seleccione una cita atendida</li>
                    <li>• Agregue indicaciones generales</li>
                    <li>• Haga clic en medicamentos para agregarlos</li>
                    <li>• Complete dosis, frecuencia y duración</li>
                    <li>• Revise y guarde la receta</li>
                </ul>
            </div>
        </div>
    </div>
</form>

<script>
let contadorMedicamentos = 0;

function cargarPaciente(select) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        const paciente = option.dataset.paciente;
        const ci = option.dataset.ci;
        document.getElementById('nombrePaciente').textContent = `${paciente} (CI: ${ci})`;
        document.getElementById('infoPaciente').classList.remove('hidden');
    } else {
        document.getElementById('infoPaciente').classList.add('hidden');
    }
}

function agregarMedicamento() {
    alert('Haga clic en un medicamento de la lista de la derecha para agregarlo');
}

function seleccionarMedicamento(id, nombre, presentacion, dosis) {
    contadorMedicamentos++;
    const index = contadorMedicamentos;
    
    const html = `
        <div id="medicamento_${index}" class="border border-orange-200 rounded-lg p-4 bg-orange-50">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="font-semibold text-gray-800">${nombre}</p>
                    <p class="text-sm text-gray-600">${presentacion}</p>
                </div>
                <button type="button" 
                        onclick="eliminarMedicamento(${index})"
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <input type="hidden" name="medicamentos[${index}][medicamento_id]" value="${id}">
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Cantidad *</label>
                    <input type="number" 
                           name="medicamentos[${index}][cantidad]" 
                           required min="1"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Dosis *</label>
                    <input type="text" 
                           name="medicamentos[${index}][dosis]" 
                           required
                           value="${dosis}"
                           placeholder="Ej: 500mg"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Frecuencia *</label>
                    <input type="text" 
                           name="medicamentos[${index}][frecuencia]" 
                           required
                           placeholder="Ej: Cada 8 horas"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Duración *</label>
                    <input type="text" 
                           name="medicamentos[${index}][duracion]" 
                           required
                           placeholder="Ej: 7 días"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Instrucciones Especiales</label>
                    <input type="text" 
                           name="medicamentos[${index}][instrucciones_especiales]"
                           placeholder="Ej: Tomar con alimentos"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('listaMedicamentos').insertAdjacentHTML('beforeend', html);
    document.getElementById('sinMedicamentos').classList.add('hidden');
}

function eliminarMedicamento(index) {
    document.getElementById(`medicamento_${index}`).remove();
    
    if (document.getElementById('listaMedicamentos').children.length === 0) {
        document.getElementById('sinMedicamentos').classList.remove('hidden');
    }
}

function filtrarMedicamentos() {
    const busqueda = document.getElementById('buscarMedicamento').value.toLowerCase();
    const items = document.querySelectorAll('.medicamento-item');
    
    items.forEach(item => {
        const nombre = item.dataset.nombre;
        if (nombre.includes(busqueda)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Validación antes de enviar
document.getElementById('formReceta').addEventListener('submit', function(e) {
    const medicamentos = document.getElementById('listaMedicamentos').children.length;
    if (medicamentos === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un medicamento a la receta');
    }
});
</script>
@endsection