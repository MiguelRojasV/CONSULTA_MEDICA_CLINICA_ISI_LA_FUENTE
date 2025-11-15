@extends('layouts.app')

@section('title', 'Editar Cita - Clínica ISI La Fuente')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Editar Cita Médica</h1>
        <a href="{{ route('citas.show', $cita) }}" class="text-blue-600 hover:underline mt-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Volver a detalles
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('citas.update', $cita) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="paciente_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Paciente *
                    </label>
                    <select name="paciente_id" 
                            id="paciente_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Seleccione un paciente</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}" 
                                {{ old('paciente_id', $cita->paciente_id) == $paciente->id ? 'selected' : '' }}>
                                {{ $paciente->nombre }} - CI: {{ $paciente->ci }}
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="medico_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Médico *
                    </label>
                    <select name="medico_id" 
                            id="medico_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Seleccione un médico</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}" 
                                {{ old('medico_id', $cita->medico_id) == $medico->id ? 'selected' : '' }}>
                                {{ $medico->nombre }} - {{ $medico->especialidad }}
                            </option>
                        @endforeach
                    </select>
                    @error('medico_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha *
                    </label>
                    <input type="date" 
                           name="fecha" 
                           id="fecha" 
                           value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('fecha')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hora" class="block text-sm font-medium text-gray-700 mb-2">
                        Hora *
                    </label>
                    <input type="time" 
                           name="hora" 
                           id="hora" 
                           value="{{ old('hora', $cita->hora->format('H:i')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('hora')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de la Consulta
                </label>
                <textarea name="motivo" 
                          id="motivo" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('motivo', $cita->motivo) }}</textarea>
                @error('motivo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="diagnostico" class="block text-sm font-medium text-gray-700 mb-2">
                    Diagnóstico
                </label>
                <textarea name="diagnostico" 
                          id="diagnostico" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('diagnostico', $cita->diagnostico) }}</textarea>
                @error('diagnostico')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tratamiento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tratamiento
                </label>
                <textarea name="tratamiento" 
                          id="tratamiento" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('tratamiento', $cita->tratamiento) }}</textarea>
                @error('tratamiento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado *
                </label>
                <select name="estado" 
                        id="estado" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="Programada" {{ old('estado', $cita->estado) == 'Programada' ? 'selected' : '' }}>Programada</option>
                    <option value="Confirmada" {{ old('estado', $cita->estado) == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="En Consulta" {{ old('estado', $cita->estado) == 'En Consulta' ? 'selected' : '' }}>En Consulta</option>
                    <option value="Atendida" {{ old('estado', $cita->estado) == 'Atendida' ? 'selected' : '' }}>Atendida</option>
                    <option value="Cancelada" {{ old('estado', $cita->estado) == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
                @error('estado')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('citas.show', $cita) }}" 
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Cita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection