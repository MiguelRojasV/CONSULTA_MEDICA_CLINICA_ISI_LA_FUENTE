{{-- ============================================ --}}
{{-- resources/views/admin/medicos/edit.blade.php --}}
{{-- Formulario para Editar Médico --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Editar Médico')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Editar Médico</h1>
    <p class="text-gray-600 mt-2">Modificar información de Dr(a). {{ $medico->nombre_completo }}</p>
</div>

<form action="{{ route('admin.medicos.update', $medico) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    {{-- Datos de Acceso --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-key text-green-600 mr-2"></i>
            Datos de Acceso
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nueva Contraseña (dejar en blanco para mantener la actual)
                </label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Nueva Contraseña
                </label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>
    
    {{-- Datos Personales --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-user text-blue-600 mr-2"></i>
            Datos Personales
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    CI <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ci" value="{{ old('ci', $medico->ci) }}" required
                       pattern="[0-9]{7,8}" maxlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('ci') border-red-500 @enderror">
                @error('ci')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" value="{{ old('nombre', $medico->nombre) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Apellido <span class="text-red-500">*</span>
                </label>
                <input type="text" name="apellido" value="{{ old('apellido', $medico->apellido) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="text" name="telefono" value="{{ old('telefono', $medico->telefono) }}" required
                       pattern="[0-9]{7,8}" maxlength="8"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>
    
    {{-- Datos Profesionales --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-stethoscope text-purple-600 mr-2"></i>
            Datos Profesionales
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Especialidad <span class="text-red-500">*</span>
                </label>
                <select name="especialidad_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    @foreach($especialidades as $esp)
                        <option value="{{ $esp->id }}" {{ old('especialidad_id', $medico->especialidad_id) == $esp->id ? 'selected' : '' }}>
                            {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Matrícula <span class="text-red-500">*</span>
                </label>
                <input type="text" name="matricula" value="{{ old('matricula', $medico->matricula) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Registro Profesional
                </label>
                <input type="text" name="registro_profesional" value="{{ old('registro_profesional', $medico->registro_profesional) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Años de Experiencia
                </label>
                <input type="number" name="años_experiencia" value="{{ old('años_experiencia', $medico->años_experiencia) }}"
                       min="0" max="70"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Turno
                </label>
                <select name="turno"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Seleccione...</option>
                    <option value="Mañana" {{ old('turno', $medico->turno) == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="Tarde" {{ old('turno', $medico->turno) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                    <option value="Noche" {{ old('turno', $medico->turno) == 'Noche' ? 'selected' : '' }}>Noche</option>
                    <option value="Rotativo" {{ old('turno', $medico->turno) == 'Rotativo' ? 'selected' : '' }}>Rotativo</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Consultorio
                </label>
                <input type="text" name="consultorio" value="{{ old('consultorio', $medico->consultorio) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Contratación
                </label>
                <input type="date" name="fecha_contratacion" 
                       value="{{ old('fecha_contratacion', $medico->fecha_contratacion ? $medico->fecha_contratacion->format('Y-m-d') : '') }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select name="estado" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="Activo" {{ old('estado', $medico->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estado', $medico->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="Licencia" {{ old('estado', $medico->estado) == 'Licencia' ? 'selected' : '' }}>Licencia</option>
                </select>
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Formación Continua / Certificaciones
                </label>
                <textarea name="formacion_continua" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('formacion_continua', $medico->formacion_continua) }}</textarea>
            </div>
        </div>
    </div>
    
    {{-- Botones --}}
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.medicos.show', $medico) }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-times mr-2"></i>Cancelar
        </a>
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
            <i class="fas fa-save mr-2"></i>Guardar Cambios
        </button>
    </div>
</form>
@endsection