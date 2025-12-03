{{-- ============================================ --}}
{{-- resources/views/medico/perfil/edit.blade.php --}}
{{-- Editar Perfil del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Editar Perfil')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-edit mr-3"></i>Editar Mi Perfil
        </h1>
        <a href="{{ route('medico.perfil.show') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Cancelar
        </a>
    </div>
</div>

<form action="{{ route('medico.perfil.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulario Principal --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Datos Personales --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-id-card text-blue-600 mr-2"></i>
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $medico->nombre) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('nombre')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">
                            Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="apellido" 
                               name="apellido" 
                               value="{{ old('apellido', $medico->apellido) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('apellido')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ci" class="block text-sm font-semibold text-gray-700 mb-2">
                            Carnet de Identidad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="ci" 
                               name="ci" 
                               value="{{ old('ci', $medico->ci) }}"
                               required
                               pattern="[0-9]{7,8}"
                               maxlength="8"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">7 u 8 dígitos</p>
                        @error('ci')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono', $medico->telefono) }}"
                               required
                               pattern="[0-9]{7,8}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('telefono')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $medico->email) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Datos Profesionales --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-stethoscope text-green-600 mr-2"></i>
                    Información Profesional
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="especialidad_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Especialidad <span class="text-red-500">*</span>
                        </label>
                        <select id="especialidad_id" 
                                name="especialidad_id" 
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Seleccionar --</option>
                            @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}"
                                        {{ old('especialidad_id', $medico->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('especialidad_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="matricula" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matrícula Profesional <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="matricula" 
                               name="matricula" 
                               value="{{ old('matricula', $medico->matricula) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('matricula')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registro_profesional" class="block text-sm font-semibold text-gray-700 mb-2">
                            Registro Profesional
                        </label>
                        <input type="text" 
                               id="registro_profesional" 
                               name="registro_profesional" 
                               value="{{ old('registro_profesional', $medico->registro_profesional) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('registro_profesional')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="años_experiencia" class="block text-sm font-semibold text-gray-700 mb-2">
                            Años de Experiencia
                        </label>
                        <input type="number" 
                               id="años_experiencia" 
                               name="años_experiencia" 
                               value="{{ old('años_experiencia', $medico->años_experiencia) }}"
                               min="0"
                               max="70"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('años_experiencia')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="turno" class="block text-sm font-semibold text-gray-700 mb-2">
                            Turno de Trabajo
                        </label>
                        <select id="turno" 
                                name="turno"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Seleccionar --</option>
                            <option value="Mañana" {{ old('turno', $medico->turno) == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="Tarde" {{ old('turno', $medico->turno) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="Noche" {{ old('turno', $medico->turno) == 'Noche' ? 'selected' : '' }}>Noche</option>
                            <option value="Rotativo" {{ old('turno', $medico->turno) == 'Rotativo' ? 'selected' : '' }}>Rotativo</option>
                        </select>
                        @error('turno')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="consultorio" class="block text-sm font-semibold text-gray-700 mb-2">
                            Consultorio
                        </label>
                        <input type="text" 
                               id="consultorio" 
                               name="consultorio" 
                               value="{{ old('consultorio', $medico->consultorio) }}"
                               placeholder="Ej: Consultorio 101"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('consultorio')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="formacion_continua" class="block text-sm font-semibold text-gray-700 mb-2">
                            Formación Continua
                        </label>
                        <textarea id="formacion_continua" 
                                  name="formacion_continua" 
                                  rows="4"
                                  placeholder="Cursos, certificaciones, diplomados, maestrías..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('formacion_continua', $medico->formacion_continua) }}</textarea>
                        @error('formacion_continua')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('medico.perfil.show') }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-save mr-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Vista Previa --}}
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="bg-green-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-md text-green-600 text-4xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-1">
                    Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
                </h3>
                <p class="text-gray-600">{{ $medico->especialidad->nombre }}</p>
            </div>

            {{-- Ayuda --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r p-4">
                <h3 class="font-bold text-blue-800 mb-2 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información Importante
                </h3>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Los campos con (*) son obligatorios</li>
                    <li>• El CI debe tener 7 u 8 dígitos</li>
                    <li>• El email debe ser único en el sistema</li>
                    <li>• La matrícula debe ser única</li>
                    <li>• Verifique bien sus datos antes de guardar</li>
                </ul>
            </div>

            {{-- Enlaces Adicionales --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-link text-purple-600 mr-2"></i>
                    Enlaces Rápidos
                </h3>

                <div class="space-y-2">
                    <a href="{{ route('medico.perfil.password.edit') }}" 
                       class="block bg-yellow-600 text-white text-center px-4 py-2 rounded hover:bg-yellow-700 transition text-sm">
                        <i class="fas fa-key mr-2"></i>Cambiar Contraseña
                    </a>
                    <a href="{{ route('medico.perfil.horarios') }}" 
                       class="block bg-purple-600 text-white text-center px-4 py-2 rounded hover:bg-purple-700 transition text-sm">
                        <i class="fas fa-clock mr-2"></i>Ver Horarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection