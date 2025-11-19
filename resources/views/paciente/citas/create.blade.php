{{-- ============================================ --}}
{{-- resources/views/paciente/citas/create.blade.php --}}
{{-- Vista: Agendar Nueva Cita --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Agendar Nueva Cita')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Agendar Nueva Cita Médica</h1>
    <p class="text-gray-600 mt-2">Complete el formulario para solicitar una cita</p>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('paciente.citas.store') }}" method="POST" id="formCita">
            @csrf

            {{-- Paso 1: Seleccionar Especialidad --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-stethoscope text-blue-600 mr-2"></i>
                    Paso 1: Seleccione la Especialidad
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Especialidad Médica <span class="text-red-500">*</span>
                    </label>
                    <select name="especialidad_id" 
                            id="especialidad_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('especialidad_id') border-red-500 @enderror"
                            required>
                        <option value="">Seleccione una especialidad...</option>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                {{ $especialidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('especialidad_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Paso 2: Seleccionar Médico --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user-md text-green-600 mr-2"></i>
                    Paso 2: Seleccione el Médico
                </h3>

                <div id="medicos-container" class="text-center py-8 text-gray-500">
                    <i class="fas fa-arrow-up text-4xl mb-2"></i>
                    <p>Primero seleccione una especialidad</p>
                </div>

                <input type="hidden" name="medico_id" id="medico_id" value="{{ old('medico_id') }}">
                @error('medico_id')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Paso 3: Fecha y Hora --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                    Paso 3: Fecha y Hora
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de la Cita <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="fecha" 
                               id="fecha"
                               value="{{ old('fecha') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fecha') border-red-500 @enderror"
                               required>
                        @error('fecha')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hora de la Cita <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               name="hora" 
                               id="hora"
                               value="{{ old('hora') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('hora') border-red-500 @enderror"
                               required>
                        @error('hora')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="horarios-info" class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4" style="display:none;">
                    <p class="text-sm text-blue-800 font-semibold mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Horarios de Atención:
                    </p>
                    <div id="horarios-list" class="text-sm text-blue-700"></div>
                </div>
            </div>

            {{-- Paso 4: Motivo --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-comment-medical text-red-600 mr-2"></i>
                    Paso 4: Motivo de la Consulta
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Describa el motivo de su consulta <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motivo"
                              rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motivo') border-red-500 @enderror"
                              placeholder="Describa sus síntomas o el motivo de su consulta..."
                              required>{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">
                        Tipo de Cita
                    </label>
                    <select name="tipo_cita"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Primera Vez" {{ old('tipo_cita') == 'Primera Vez' ? 'selected' : '' }}>Primera Vez</option>
                        <option value="Control" {{ old('tipo_cita') == 'Control' ? 'selected' : '' }}>Control</option>
                        <option value="Emergencia" {{ old('tipo_cita') == 'Emergencia' ? 'selected' : '' }}>Emergencia</option>
                    </select>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('paciente.citas.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-calendar-check mr-2"></i>Agendar Cita
                </button>
            </div>
        </form>
    </div>

    {{-- Información importante --}}
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-sm text-gray-700">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <strong>Importante:</strong> Una vez agendada la cita, recibirá una confirmación. Por favor llegue 10 minutos antes de su hora programada.
        </p>
    </div>
</div>

{{-- Script para cargar médicos dinámicamente --}}
<script>
document.getElementById('especialidad_id').addEventListener('change', function() {
    const especialidadId = this.value;
    const medicosContainer = document.getElementById('medicos-container');
    
    if (!especialidadId) {
        medicosContainer.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-arrow-up text-4xl mb-2"></i><p>Primero seleccione una especialidad</p></div>';
        return;
    }
    
    medicosContainer.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i><p class="mt-2 text-gray-600">Cargando médicos...</p></div>';
    
    // Simulación de carga de médicos (en producción, usar AJAX)
    fetch(`/api/medicos-por-especialidad/${especialidadId}`)
        .then(response => response.json())
        .then(medicos => {
            if (medicos.length === 0) {
                medicosContainer.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-user-times text-4xl mb-2"></i><p>No hay médicos disponibles para esta especialidad</p></div>';
                return;
            }
            
            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
            medicos.forEach(medico => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-md transition cursor-pointer medico-card" data-medico-id="${medico.id}">
                        <div class="flex items-center mb-2">
                            <div class="bg-blue-100 rounded-full p-3 mr-3">
                                <i class="fas fa-user-md text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Dr(a). ${medico.nombre} ${medico.apellido}</p>
                                <p class="text-sm text-gray-600">${medico.especialidad}</p>
                            </div>
                        </div>
                        ${medico.consultorio ? `<p class="text-sm text-gray-600"><i class="fas fa-door-open mr-2"></i>${medico.consultorio}</p>` : ''}
                        ${medico.turno ? `<p class="text-sm text-gray-600"><i class="fas fa-clock mr-2"></i>Turno: ${medico.turno}</p>` : ''}
                    </div>
                `;
            });
            html += '</div>';
            
            medicosContainer.innerHTML = html;
            
            // Agregar event listeners a las tarjetas
            document.querySelectorAll('.medico-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.medico-card').forEach(c => c.classList.remove('border-blue-500', 'bg-blue-50'));
                    this.classList.add('border-blue-500', 'bg-blue-50');
                    document.getElementById('medico_id').value = this.dataset.medicoId;
                    
                    // Cargar horarios del médico
                    cargarHorarios(this.dataset.medicoId);
                });
            });
        })
        .catch(error => {
            medicosContainer.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-circle text-4xl mb-2"></i><p>Error al cargar médicos</p></div>';
        });
});

function cargarHorarios(medicoId) {
    const horariosInfo = document.getElementById('horarios-info');
    const horariosList = document.getElementById('horarios-list');
    
    // Simulación de carga de horarios
    horariosInfo.style.display = 'block';
    horariosList.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Cargando horarios...';
    
    fetch(`/api/medico-horarios/${medicoId}`)
        .then(response => response.json())
        .then(horarios => {
            if (horarios.length === 0) {
                horariosList.innerHTML = 'No hay horarios configurados';
                return;
            }
            
            let html = '<ul class="space-y-1">';
            horarios.forEach(horario => {
                html += `<li><strong>${horario.dia_semana}:</strong> ${horario.hora_inicio} - ${horario.hora_fin}</li>`;
            });
            html += '</ul>';
            
            horariosList.innerHTML = html;
        })
        .catch(error => {
            horariosList.innerHTML = 'Error al cargar horarios';
        });
}
</script>
@endsection

{{-- 
CARACTERÍSTICAS:
1. Formulario paso a paso (4 pasos)
2. Selección de especialidad → médico dinámico
3. Tarjetas de médicos clicables
4. Carga dinámica de horarios del médico
5. Validación de fechas (no pasadas)
6. Tipo de cita configurable
7. Campo de motivo obligatorio
8. Diseño intuitivo y guiado
9. JavaScript para interactividad
10. Información de ayuda
--}}