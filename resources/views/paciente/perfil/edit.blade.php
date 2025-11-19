{{-- ============================================ --}}
{{-- resources/views/paciente/perfil/edit.blade.php --}}
{{-- Vista: Editar Perfil del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Editar Mi Perfil')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Editar Mi Perfil</h1>
    <p class="text-gray-600 mt-2">Actualice su información personal y médica</p>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('paciente.perfil.update') }}" method="POST">
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
                               value="{{ old('nombre', $paciente->nombre) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
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
                               value="{{ old('apellido', $paciente->apellido) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('apellido') border-red-500 @enderror"
                               required>
                        @error('apellido')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CI (no editable) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cédula de Identidad (CI)
                        </label>
                        <input type="text" 
                               value="{{ $paciente->ci }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                               disabled>
                        <p class="text-xs text-gray-500 mt-1">El CI no puede ser modificado</p>
                    </div>

                    {{-- Fecha de nacimiento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fecha_nacimiento') border-red-500 @enderror"
                               required>
                        @error('fecha_nacimiento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Género --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Género <span class="text-red-500">*</span>
                        </label>
                        <select name="genero"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('genero') border-red-500 @enderror"
                                required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" {{ old('genero', $paciente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $paciente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero', $paciente->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('genero')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Grupo sanguíneo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Grupo Sanguíneo
                        </label>
                        <select name="grupo_sanguineo"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccione...</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('grupo_sanguineo', $paciente->grupo_sanguineo) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Estado civil --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado Civil
                        </label>
                        <select name="estado_civil"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccione...</option>
                            <option value="Soltero" {{ old('estado_civil', $paciente->estado_civil) == 'Soltero' ? 'selected' : '' }}>Soltero/a</option>
                            <option value="Casado" {{ old('estado_civil', $paciente->estado_civil) == 'Casado' ? 'selected' : '' }}>Casado/a</option>
                            <option value="Divorciado" {{ old('estado_civil', $paciente->estado_civil) == 'Divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                            <option value="Viudo" {{ old('estado_civil', $paciente->estado_civil) == 'Viudo' ? 'selected' : '' }}>Viudo/a</option>
                        </select>
                    </div>

                    {{-- Ocupación --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ocupación
                        </label>
                        <input type="text" 
                               name="ocupacion" 
                               value="{{ old('ocupacion', $paciente->ocupacion) }}"
                               maxlength="100"
                               placeholder="Ej: Estudiante, Profesional..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: Información de Contacto --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-address-book text-green-600 mr-2"></i>
                    Información de Contacto
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $paciente->email ?? $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
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
                               value="{{ old('telefono', $paciente->telefono) }}"
                               maxlength="15"
                               pattern="[0-9]{7,8}"
                               placeholder="70123456"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">7-8 dígitos</p>
                        @error('telefono')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <input type="text" 
                               name="direccion" 
                               value="{{ old('direccion', $paciente->direccion) }}"
                               maxlength="200"
                               placeholder="Calle, Avenida, Zona..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: Contacto de Emergencia --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-phone-volume text-red-600 mr-2"></i>
                    Contacto de Emergencia
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Contacto
                        </label>
                        <input type="text" 
                               name="contacto_emergencia" 
                               value="{{ old('contacto_emergencia', $paciente->contacto_emergencia) }}"
                               maxlength="100"
                               placeholder="Nombre completo"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono de Emergencia
                        </label>
                        <input type="tel" 
                               name="telefono_emergencia" 
                               value="{{ old('telefono_emergencia', $paciente->telefono_emergencia) }}"
                               maxlength="15"
                               pattern="[0-9]{7,8}"
                               placeholder="70123456"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 4: Información Médica --}}
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-heartbeat text-purple-600 mr-2"></i>
                    Información Médica
                </h3>

                <div class="space-y-4">
                    {{-- Alergias --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>
                            Alergias Conocidas
                        </label>
                        <textarea name="alergias"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Describa cualquier alergia a medicamentos, alimentos, etc..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('alergias', $paciente->alergias) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                    </div>

                    {{-- Antecedentes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Antecedentes Médicos
                        </label>
                        <textarea name="antecedentes"
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Enfermedades previas, cirugías, tratamientos..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('antecedentes', $paciente->antecedentes) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('paciente.perfil.show') }}" 
                   class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg hover:shadow-xl">
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
            <strong>Importante:</strong> Los campos marcados con <span class="text-red-500">*</span> son obligatorios. Su información médica es confidencial.
        </p>
    </div>
</div>
@endsection

{{-- 
CARACTERÍSTICAS:
1. Formulario completo dividido en 4 secciones
2. Datos personales, contacto, emergencia, médicos
3. CI no editable (solo lectura)
4. Validaciones HTML5 y Laravel
5. Campos opcionales claramente indicados
6. Alergias y antecedentes en textarea
7. Select para grupo sanguíneo y estado civil
8. Manejo de errores @error
9. Botones de cancelar y guardar
10. Información de ayuda al final
--}}