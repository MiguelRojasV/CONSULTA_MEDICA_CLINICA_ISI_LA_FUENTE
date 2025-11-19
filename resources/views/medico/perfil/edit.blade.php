{{-- ============================================ --}}
{{-- resources/views/medico/perfil/edit.blade.php --}}
{{-- Vista: Editar Perfil del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Editar Mi Perfil')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Editar Mi Perfil</h1>
    <p class="text-gray-600 mt-2">Actualice su información personal y profesional</p>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('medico.perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- SECCIÓN 1: Datos Personales --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Datos Personales
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $medico->nombre) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                               required>
                        @error('nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="apellido" 
                               value="{{ old('apellido', $medico->apellido) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('apellido') border-red-500 @enderror"
                               required>
                        @error('apellido')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CI --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cédula de Identidad (CI) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="ci" 
                               value="{{ old('ci', $medico->ci) }}"
                               maxlength="8"
                               pattern="[0-9]{7,8}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('ci') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">7-8 dígitos numéricos</p>
                        @error('ci')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $medico->email ?? $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="telefono" 
                               value="{{ old('telefono', $medico->telefono) }}"
                               maxlength="15"
                               pattern="[0-9]{7,8}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">7-8 dígitos</p>
                        @error('telefono')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: Datos Profesionales --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user-md text-green-600 mr-2"></i>
                    Datos Profesionales
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Especialidad --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidad <span class="text-red-500">*</span>
                        </label>
                        <select name="especialidad_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('especialidad_id') border-red-500 @enderror"
                                required>
                            <option value="">Seleccione una especialidad</option>
                            @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}" 
                                        {{ old('especialidad_id', $medico->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('especialidad_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Matrícula --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Matrícula Profesional <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="matricula" 
                               value="{{ old('matricula', $medico->matricula) }}"
                               maxlength="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('matricula') border-red-500 @enderror"
                               required>
                        @error('matricula')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Registro Profesional --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Registro Profesional
                        </label>
                        <input type="text" 
                               name="registro_profesional" 
                               value="{{ old('registro_profesional', $medico->registro_profesional) }}"
                               maxlength="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    {{-- Años de Experiencia --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Años de Experiencia
                        </label>
                        <input type="number" 
                               name="años_experiencia" 
                               value="{{ old('años_experiencia', $medico->años_experiencia) }}"
                               min="0"
                               max="70"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('años_experiencia') border-red-500 @enderror">
                        @error('años_experiencia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Turno --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Turno de Trabajo
                        </label>
                        <select name="turno"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Seleccione un turno</option>
                            <option value="Mañana" {{ old('turno', $medico->turno) == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="Tarde" {{ old('turno', $medico->turno) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="Noche" {{ old('turno', $medico->turno) == 'Noche' ? 'selected' : '' }}>Noche</option>
                            <option value="Rotativo" {{ old('turno', $medico->turno) == 'Rotativo' ? 'selected' : '' }}>Rotativo</option>
                        </select>
                    </div>

                    {{-- Consultorio --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Consultorio
                        </label>
                        <input type="text" 
                               name="consultorio" 
                               value="{{ old('consultorio', $medico->consultorio) }}"
                               maxlength="100"
                               placeholder="Ej: Consultorio 101"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                {{-- Formación Continua --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Formación Continua (Cursos, Certificaciones)
                    </label>
                    <textarea name="formacion_continua"
                              rows="4"
                              maxlength="1000"
                              placeholder="Describa sus cursos, certificaciones y formación adicional..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('formacion_continua', $medico->formacion_continua) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('medico.perfil.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Información de ayuda --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-gray-700">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <strong>Importante:</strong> Los campos marcados con <span class="text-red-500">*</span> son obligatorios. Asegúrese de que su información de contacto esté actualizada.
        </p>
    </div>
</div>
@endsection

{{-- 
CARACTERÍSTICAS DE ESTA VISTA:
1. Formulario completo con todos los campos del médico
2. Validaciones HTML5 (pattern, maxlength, required)
3. Campos divididos en: Personales y Profesionales
4. Select de especialidades cargado dinámicamente
5. Manejo de errores de validación
6. Mensajes de ayuda en campos específicos
7. Diseño responsive
8. Botones de cancelar y guardar
9. Compatible con estructura 3FN de la BD
--}}