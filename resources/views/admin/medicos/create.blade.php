{{-- ============================================ --}}
{{-- 2. resources/views/admin/medicos/create.blade.php --}}
{{-- Formulario Crear Médico --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Nuevo Médico')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Médico</h1>
    <p class="text-gray-600 mt-2">Complete el formulario con los datos del médico</p>
</div>

<form action="{{ route('admin.medicos.store') }}" method="POST" class="space-y-6">
    @csrf
    
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
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Contraseña <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Contraseña <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation" required
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
                <input type="text" name="ci" value="{{ old('ci') }}" required
                       pattern="[0-9]{7,8}" maxlength="8" placeholder="7 u 8 dígitos"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('ci') border-red-500 @enderror">
                @error('ci')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Apellido <span class="text-red-500">*</span>
                </label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('apellido') border-red-500 @enderror">
                @error('apellido')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" required
                       pattern="[0-9]{7,8}" maxlength="8" placeholder="7 u 8 dígitos"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('telefono') border-red-500 @enderror">
                @error('telefono')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('especialidad_id') border-red-500 @enderror">
                    <option value="">Seleccione...</option>
                    @foreach($especialidades as $esp)
                        <option value="{{ $esp->id }}" {{ old('especialidad_id') == $esp->id ? 'selected' : '' }}>
                            {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('especialidad_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Matrícula <span class="text-red-500">*</span>
                </label>
                <input type="text" name="matricula" value="{{ old('matricula') }}" required
                       placeholder="Ej: MP-12345"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('matricula') border-red-500 @enderror">
                @error('matricula')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Registro Profesional
                </label>
                <input type="text" name="registro_profesional" value="{{ old('registro_profesional') }}"
                       placeholder="Ej: REG-001"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Años de Experiencia
                </label>
                <input type="number" name="años_experiencia" value="{{ old('años_experiencia', 0) }}"
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
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noche">Noche</option>
                    <option value="Rotativo">Rotativo</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Consultorio
                </label>
                <input type="text" name="consultorio" value="{{ old('consultorio') }}"
                       placeholder="Ej: Consultorio 101"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Contratación
                </label>
                <input type="date" name="fecha_contratacion" value="{{ old('fecha_contratacion', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Formación Continua / Certificaciones
                </label>
                <textarea name="formacion_continua" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('formacion_continua') }}</textarea>
            </div>
        </div>
    </div>
    
    {{-- Botones --}}
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.medicos.index') }}" 
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-times mr-2"></i>Cancelar
        </a>
        <button type="submit" 
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
            <i class="fas fa-save mr-2"></i>Guardar Médico
        </button>
    </div>
</form>
@endsection