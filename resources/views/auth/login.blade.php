<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Clínica ISI La Fuente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        {{-- Logo y título --}}
        <div class="text-center mb-8">
            <div class="inline-block bg-white p-4 rounded-full shadow-lg mb-4">
                <i class="fas fa-hospital text-blue-600 text-5xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Clínica ISI La Fuente</h1>
            <p class="text-gray-600 mt-2">Sistema de Consulta Médica</p>
        </div>

        {{-- Formulario de login --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mensaje de éxito (si viene de logout) --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                {{-- Campo de email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="tu@email.com"
                           required
                           autofocus>
                </div>

                {{-- Campo de contraseña --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>
                        Contraseña
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="••••••••"
                           required>
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                </div>

                {{-- Botón de submit --}}
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>

            {{-- Enlaces adicionales --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition">
                        Regístrate aquí
                    </a>
                </p>
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 mt-3 inline-block">
                    <i class="fas fa-home mr-1"></i>
                    Volver al inicio
                </a>
            </div>
        </div>

        {{-- Información de acceso para pruebas --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-gray-700">
            <p class="font-semibold mb-2"><i class="fas fa-info-circle mr-2 text-blue-600"></i>Accesos de prueba:</p>
            <ul class="space-y-1 text-xs">
                <li><strong>Admin:</strong> admin@clinica.com / admin123</li>
                <li><strong>Médico:</strong> Se crea desde el panel admin</li>
                <li><strong>Paciente:</strong> Registrarse en el sistema</li>
            </ul>
        </div>
    </div>

</body>
</html>