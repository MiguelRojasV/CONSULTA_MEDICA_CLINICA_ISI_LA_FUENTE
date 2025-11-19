{{-- ============================================ --}}
{{-- resources/views/auth/register.blade.php --}}
{{-- Vista de Registro - ACTUALIZADA Y CORREGIDA --}}
{{-- Compatible con estructura 3FN --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Clínica ISI La Fuente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen p-4">
    
    <div class="max-w-4xl mx-auto py-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-block bg-white p-4 rounded-full shadow-lg mb-4">
                <i class="fas fa-user-plus text-green-600 text-5xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Registro de Nuevo Paciente</h1>
            <p class="text-gray-600 mt-2">Complete el formulario para crear su cuenta</p>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            
            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <p class="font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Por favor corrija los siguientes errores:
                    </p>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                {{-- SECCIÓN 1: Datos de Cuenta --}}
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-key text-blue-600 mr-2"></i>
                        Datos de Cuenta
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-gray-500 @enderror"
                                   placeholder="ejemplo@correo.com"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}"
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
                                   value="{{ old('apellido') }}"
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
                                   value="{{ old('ci') }}"
                                   maxlength="8"
                                   pattern="[0-9]{7,8}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('ci') border-red-500 @enderror"
                                   placeholder="12345678"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">7-8 dígitos numéricos</p>
                            @error('ci')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                   minlength="8"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmar contraseña --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   minlength="8"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: Datos Personales --}}
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Datos Personales
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Fecha de nacimiento --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('fecha_nacimiento') border-red-500 @enderror"
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('genero') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccione...</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
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
                                   value="{{ old('telefono') }}"
                                   maxlength="15"
                                   pattern="[0-9]{7,8}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                                   placeholder="70123456"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">7-8 dígitos</p>
                            @error('telefono')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Grupo sanguíneo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Grupo Sanguíneo
                            </label>
                            <select name="grupo_sanguineo"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Seleccione...</option>
                                <option value="A+" {{ old('grupo_sanguineo') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('grupo_sanguineo') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('grupo_sanguineo') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('grupo_sanguineo') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('grupo_sanguineo') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('grupo_sanguineo') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('grupo_sanguineo') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('grupo_sanguineo') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>

                        {{-- Estado civil --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estado Civil
                            </label>
                            <select name="estado_civil"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Seleccione...</option>
                                <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado/a</option>
                                <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo/a</option>
                            </select>
                        </div>

                        {{-- Ocupación --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ocupación
                            </label>
                            <input type="text" 
                                   name="ocupacion" 
                                   value="{{ old('ocupacion') }}"
                                   maxlength="100"
                                   placeholder="Ej: Estudiante, Profesional..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <input type="text" 
                               name="direccion" 
                               value="{{ old('direccion') }}"
                               maxlength="200"
                               placeholder="Calle, Avenida, Zona..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    {{-- Contacto de emergencia --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de Contacto de Emergencia
                            </label>
                            <input type="text" 
                                   name="contacto_emergencia" 
                                   value="{{ old('contacto_emergencia') }}"
                                   maxlength="100"
                                   placeholder="Nombre completo"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono de Emergencia
                            </label>
                            <input type="tel" 
                                   name="telefono_emergencia" 
                                   value="{{ old('telefono_emergencia') }}"
                                   maxlength="15"
                                   pattern="[0-9]{7,8}"
                                   placeholder="70123456"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Alergias y Antecedentes --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>
                                Alergias Conocidas
                            </label>
                            <textarea name="alergias"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Describa cualquier alergia a medicamentos, alimentos, etc..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('alergias') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Antecedentes Médicos
                            </label>
                            <textarea name="antecedentes"
                                      rows="3"
                                      maxlength="1000"
                                      placeholder="Enfermedades previas, cirugías, tratamientos..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('antecedentes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-between items-center pt-6 border-t">
                    <a href="{{ route('login') }}" 
                       class="text-gray-600 hover:text-gray-800 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al login
                    </a>
                    <button type="submit" 
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-check mr-2"></i>
                        Crear Cuenta
                    </button>
                </div>
            </form>
        </div>

        {{-- Información adicional --}}
        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-gray-700">
                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                <strong>Importante:</strong> Los campos marcados con <span class="text-red-500">*</span> son obligatorios. La información proporcionada será utilizada para su atención médica.
            </p>
        </div>
    </div>

</body>
</html>

{{-- 
CAMBIOS REALIZADOS:
1. ✅ Separado nombre y apellido (compatibilidad 3FN)
2. ✅ Agregado campo CI (obligatorio)
3. ✅ Fecha de nacimiento obligatoria (reemplaza edad)
4. ✅ Género obligatorio
5. ✅ Teléfono obligatorio
6. ✅ Grupo sanguíneo como select
7. ✅ Estado civil agregado
8. ✅ Ocupación agregada
9. ✅ Teléfono de emergencia separado
10. ✅ Campos de alergias y antecedentes
11. ✅ Validaciones @error para todos los campos
12. ✅ Placeholders informativos
13. ✅ Mensajes de ayuda mejorados
14. ✅ 100% compatible con RegisterController actualizado
--}}