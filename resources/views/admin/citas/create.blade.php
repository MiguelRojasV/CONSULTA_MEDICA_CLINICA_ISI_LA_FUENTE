{{-- ============================================ --}}
{{-- 2. resources/views/admin/citas/create.blade.php --}}
{{-- Formulario Crear Cita --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Nueva Cita')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Agendar Nueva Cita</h1>
    <p class="text-gray-600 mt-2">Complete el formulario para agendar una cita médica</p>
</div>

<form action="{{ route('admin.citas.store') }}" method="POST" class="space-y-6">
    @csrf
    
    {{-- Selección de Paciente y Médico --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-users text-purple-600 mr-2"></i>
            Paciente y Médico
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Paciente <span class="text-red-500">*</span>
                </label>
                <select name="paciente_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('paciente_id') border-red-500 @enderror">
                    <option value="">Seleccione un paciente...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                            {{ $paciente->nombre_completo }} - CI: {{ $paciente->ci }}
                        </option>
                    @endforeach
                </select>
                @error('paciente_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Médico <span class="text-red-500">*</span>
                </label>
                <select name="medico_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('medico_id') border-red-500 @enderror">
                    <option value="">Seleccione un médico...</option>
                    @foreach($medicos as $medico)
                        <option value="{{ $medico->id }}" {{ old('medico_id') == $medico->id ? 'selected' : '' }}>
                            Dr(a). {{ $medico->nombre_completo }} - {{ $medico->especialidad->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('medico_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    {{-- Fecha y Hora --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
            Fecha y Hora
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('fecha') border-red-500 @enderror">
                @error('fecha')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Hora <span class="text-red-500">*</span>
                </label>
                <input type="time" name="hora" value="{{ old('hora') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('hora') border-red-500 @enderror">
                @error('hora')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Cita
                </label>
                <select name="tipo_cita"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="Primera Vez">Primera Vez</option>
                    <option value="Control">Control</option>
                    <option value="Emergencia">Emergencia</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Duración (minutos)
                </label>
                <input type="number" name="duracion_estimada" value="{{ old('duracion_estimada', 30) }}"
                       min="15" max="480"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
        </div>
    </div>
    
    {{-- Detalles de la Cita --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-notes-medical text-green-600 mr-2"></i>
            Detalles de la Cita
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de Consulta
                </label>
                <textarea name="motivo" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('motivo') }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select name="estado" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 mb-4">
                    <option value="Programada">Programada</option>
                    <option value="Confirmada">Confirmada</option>
                </select>
                
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Costo (Bs.)
                </label>
                <input type="number" name="costo" value="{{ old('costo', 0) }}" step="0.01" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
            </div>
        </div>
    </div>
    
    {{-- Botones --}}
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.citas.index') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-times mr-2"></i>Cancelar
        </a>
        <button type="submit" 
                class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition shadow-lg">
            <i class="fas fa-save mr-2"></i>Agendar Cita
        </button>
    </div>
</form>
@endsection