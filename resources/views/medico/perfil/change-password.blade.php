{{-- ============================================ --}}
{{-- resources/views/medico/perfil/change-password.blade.php --}}
{{-- Vista: Cambiar Contraseña del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Cambiar Contraseña</h1>
    <p class="text-gray-600 mt-2">Actualice su contraseña de acceso al sistema</p>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">Recomendaciones de Seguridad</p>
                    <ul class="text-sm text-yellow-700 mt-2 space-y-1 list-disc list-inside">
                        <li>Use una contraseña de al menos 8 caracteres</li>
                        <li>Combine letras mayúsculas, minúsculas y números</li>
                        <li>No use información personal obvia</li>
                        <li>No comparta su contraseña con nadie</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('medico.perfil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Contraseña Actual --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-500"></i>
                    Contraseña Actual <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="current_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('current_password') @enderror"
                       placeholder="Ingrese su contraseña actual"
                       required>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            {{-- Nueva Contraseña --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-2 text-gray-500"></i>
                    Nueva Contraseña <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') @enderror"
                       placeholder="Ingrese su nueva contraseña"
                       minlength="8"
                       required>
                <p class="text-xs text-gray-500 mt-2">Mínimo 8 caracteres, debe incluir letras y números</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Confirmar Nueva Contraseña --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-2 text-gray-500"></i>
                    Confirmar Nueva Contraseña <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Confirme su nueva contraseña"
                       minlength="8"
                       required>
                <p class="text-xs text-gray-500 mt-2">Debe coincidir con la nueva contraseña</p>
            </div>

            {{-- Indicador de seguridad de contraseña --}}
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                    Nivel de Seguridad
                </p>
                <div class="flex space-x-2">
                    <div class="flex-1 h-2 bg-red-200 rounded"></div>
                    <div class="flex-1 h-2 bg-yellow-200 rounded"></div>
                    <div class="flex-1 h-2 bg-green-200 rounded"></div>
                </div>
                <p class="text-xs text-gray-600 mt-2" id="password-strength">
                    La contraseña debe tener al menos 8 caracteres
                </p>
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
                    <i class="fas fa-key mr-2"></i>
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>

    {{-- Información adicional --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-gray-700">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <strong>Nota:</strong> Después de cambiar su contraseña, deberá iniciar sesión nuevamente con la nueva contraseña.
        </p>
    </div>
</div>

{{-- Script para indicador de seguridad de contraseña --}}
<script>
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthText = document.getElementById('password-strength');
        const bars = document.querySelectorAll('.h-2');
        
        // Resetear barras
        bars.forEach(bar => {
            bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
            bar.classList.add('bg-gray-200');
        });
        
        if (password.length === 0) {
            strengthText.textContent = 'La contraseña debe tener al menos 8 caracteres';
            strengthText.className = 'text-xs text-gray-600 mt-2';
            return;
        }
        
        let strength = 0;
        
        // Verificar longitud
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Verificar complejidad
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        // Actualizar visualización
        if (strength <= 2) {
            bars[0].classList.remove('bg-gray-200');
            bars[0].classList.add('bg-red-500');
            strengthText.textContent = 'Contraseña débil - Agregue más caracteres y variedad';
            strengthText.className = 'text-xs text-red-600 mt-2';
        } else if (strength <= 4) {
            bars[0].classList.remove('bg-gray-200');
            bars[0].classList.add('bg-yellow-500');
            bars[1].classList.remove('bg-gray-200');
            bars[1].classList.add('bg-yellow-500');
            strengthText.textContent = 'Contraseña media - Puede mejorarla';
            strengthText.className = 'text-xs text-yellow-600 mt-2';
        } else {
            bars[0].classList.remove('bg-gray-200');
            bars[0].classList.add('bg-green-500');
            bars[1].classList.remove('bg-gray-200');
            bars[1].classList.add('bg-green-500');
            bars[2].classList.remove('bg-gray-200');
            bars[2].classList.add('bg-green-500');
            strengthText.textContent = 'Contraseña fuerte - ¡Excelente!';
            strengthText.className = 'text-xs text-green-600 mt-2';
        }
    });
</script>
@endsection

{{-- 
CARACTERÍSTICAS DE ESTA VISTA:
1. Formulario simple y seguro para cambio de contraseña
2. Validación de contraseña actual
3. Indicador visual de seguridad de contraseña
4. Recomendaciones de seguridad visibles
5. Validación de coincidencia de contraseñas
6. Diseño limpio y profesional
7. Mensajes de error claros
8. Script JavaScript para feedback en tiempo real
9. Compatible con Laravel Password Rules
--}}