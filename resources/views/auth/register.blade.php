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
                    <p class="font-semibold text-red-800 mb-2">Por favor corrija los siguientes errores:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                {{-- Sección 1: Datos de Cuenta --}}
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-key text-blue-600 mr-2"></i>
                        Datos de Cuenta
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico *
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>

                        {{-- Nombre completo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña *
                            </label>
                            <input type="password" 
                                   name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                        </div>

                        {{-- Confirmar contraseña --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña *
                            </label>
                            <input type="password" 
                                   name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- Sección 2: Datos Personales --}}
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Datos Personales
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- CI --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cédula de Identidad (CI) *
                            </label>
                            <input type="text" 
                                   name="ci" 
                                   value="{{ old('ci') }}"
                                   maxlength="8"
                                   pattern="[0-9]{7,8}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">7-8 dígitos</p>
                        </div>

                        {{-- Edad --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Edad *
                            </label>
                            <input type="number" 
                                   name="edad" 
                                   value="{{ old('edad') }}"
                                   min="0" 
                                   max="150"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Nacimiento
                            </label>
                            <input type="date" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Género --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Género
                            </label>
                            <select name="genero"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Seleccione...</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        {{-- Grupo sanguíneo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Grupo Sanguíneo
                            </label>
                            <input type="text" 
                                   name="grupo_sanguineo" 
                                   value="{{ old('grupo_sanguineo') }}"
                                   placeholder="Ej: O+, AB-"
                                   maxlength="5"
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    {{-- Contacto de emergencia --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contacto de Emergencia
                        </label>
                        <input type="text" 
                               name="contacto_emergencia" 
                               value="{{ old('contacto_emergencia') }}"
                               placeholder="Nombre y teléfono"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
    </div>

</body>
</html>
