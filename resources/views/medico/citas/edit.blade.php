{{-- ============================================ --}}
{{-- resources/views/medico/citas/edit.blade.php --}}
{{-- Formulario para Atender Cita --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Atender Paciente')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-md mr-3"></i>Atender Paciente
        </h1>
        <a href="{{ route('medico.citas.show', $cita) }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Cancelar
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Formulario de Atención --}}
    <div class="lg:col-span-2">
        <form action="{{ route('medico.citas.update', $cita) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')

            {{-- Información de la Cita --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r mb-6">
                <p class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Cita: {{ $cita->fecha->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                </p>
                @if($cita->motivo)
                    <p class="text-sm text-blue-700">
                        <strong>Motivo:</strong> {{ $cita->motivo }}
                    </p>
                @endif
            </div>

            {{-- Síntomas --}}
            <div class="mb-6">
                <label for="sintomas" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-thermometer-half text-orange-600 mr-2"></i>
                    Síntomas Presentados
                </label>
                <textarea id="sintomas" 
                          name="sintomas" 
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Describa los síntomas que presenta el paciente...">{{ old('sintomas', $cita->sintomas ?? '') }}</textarea>
                @error('sintomas')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Signos Vitales --}}
            <div class="mb-6">
                <label for="signos_vitales" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heartbeat text-red-600 mr-2"></i>
                    Signos Vitales
                </label>
                <textarea id="signos_vitales" 
                          name="signos_vitales" 
                          rows="2"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Ej: PA: 120/80 mmHg, FC: 72 lpm, Temp: 36.5°C, FR: 16 rpm">{{ old('signos_vitales', $cita->signos_vitales ?? '') }}</textarea>
                @error('signos_vitales')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Diagnóstico --}}
            <div class="mb-6">
                <label for="diagnostico" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-stethoscope text-green-600 mr-2"></i>
                    Diagnóstico <span class="text-red-500">*</span>
                </label>
                <textarea id="diagnostico" 
                          name="diagnostico" 
                          rows="4"
                          required
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Escriba el diagnóstico médico...">{{ old('diagnostico', $cita->diagnostico) }}</textarea>
                @error('diagnostico')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tratamiento --}}
            <div class="mb-6">
                <label for="tratamiento" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-pills text-blue-600 mr-2"></i>
                    Tratamiento Prescrito <span class="text-red-500">*</span>
                </label>
                <textarea id="tratamiento" 
                          name="tratamiento" 
                          rows="4"
                          required
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Describa el tratamiento indicado...">{{ old('tratamiento', $cita->tratamiento) }}</textarea>
                @error('tratamiento')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Observaciones --}}
            <div class="mb-6">
                <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-comment-medical text-purple-600 mr-2"></i>
                    Observaciones Adicionales
                </label>
                <textarea id="observaciones" 
                          name="observaciones" 
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Indicaciones, recomendaciones o notas adicionales...">{{ old('observaciones', $cita->observaciones) }}</textarea>
                @error('observaciones')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado de la Cita --}}
            <div class="mb-6">
                <label for="estado" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-flag text-yellow-600 mr-2"></i>
                    Estado de la Cita <span class="text-red-500">*</span>
                </label>
                <select id="estado" 
                        name="estado" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="En Consulta" {{ old('estado', $cita->estado) == 'En Consulta' ? 'selected' : '' }}>
                        En Consulta
                    </option>
                    <option value="Atendida" {{ old('estado', $cita->estado) == 'Atendida' ? 'selected' : '' }}>
                        Atendida (Finalizar)
                    </option>
                </select>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Seleccione "Atendida" para finalizar la consulta
                </p>
                @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('medico.citas.show', $cita) }}" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-save mr-2"></i>Guardar Atención
                </button>
            </div>
        </form>
    </div>

    {{-- Información del Paciente e Historial --}}
    <div class="space-y-6">
        {{-- Datos del Paciente --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Paciente
            </h3>

            <div class="text-center mb-4">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-user text-blue-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800">
                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                </h4>
                <p class="text-sm text-gray-600">CI: {{ $paciente->ci }}</p>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Edad:</span>
                    <span class="font-semibold">{{ $paciente->edad }} años</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Género:</span>
                    <span class="font-semibold">{{ $paciente->genero }}</span>
                </div>
                @if($paciente->grupo_sanguineo)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Grupo Sang.:</span>
                        <span class="font-semibold">{{ $paciente->grupo_sanguineo }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Alertas --}}
        @if($paciente->alergias || $paciente->antecedentes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4">
                <h4 class="font-bold text-red-800 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    ¡IMPORTANTE!
                </h4>

                @if($paciente->alergias)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-red-700 mb-1">Alergias:</p>
                        <p class="text-sm text-red-800">{{ $paciente->alergias }}</p>
                    </div>
                @endif

                @if($paciente->antecedentes)
                    <div>
                        <p class="text-xs font-semibold text-red-700 mb-1">Antecedentes:</p>
                        <p class="text-sm text-red-800">{{ $paciente->antecedentes }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Historial Reciente --}}
        @if($historial->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    Historial Reciente
                </h3>

                <div class="space-y-3">
                    @foreach($historial as $registro)
                        <div class="border-l-4 border-purple-500 bg-purple-50 p-3 rounded-r">
                            <p class="text-xs text-gray-600 mb-1">
                                {{ $registro->fecha->format('d/m/Y') }}
                            </p>
                            <p class="text-sm font-semibold text-gray-800 mb-1">
                                {{ Str::limit($registro->diagnostico, 60) }}
                            </p>
                            <p class="text-xs text-gray-600">
                                Dr(a). {{ $registro->medico->nombre ?? 'N/A' }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('medico.pacientes.historial', $paciente) }}" 
                   class="block mt-3 text-purple-600 hover:underline text-sm text-center">
                    Ver historial completo →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection