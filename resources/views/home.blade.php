{{-- ============================================ --}}
{{-- resources/views/home.blade.php --}}
{{-- Página Principal Actualizada - Compatible con BD 3FN --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $clinica->nombre ?? 'Clínica ISI La Fuente' }} - Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fade-in { animation: fadeIn 0.8s ease-in; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-3 rounded-full">
                        <i class="fas fa-hospital text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $clinica->nombre }}</h1>
                        <p class="text-xs text-gray-500">Sistema de Consulta Médica</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ auth()->user()->rutaDashboard() }}" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-home mr-2"></i>Mi Panel
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-blue-600 transition font-semibold">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center fade-in">
                <h2 class="text-5xl font-bold mb-6">Bienvenido a {{ $clinica->nombre }}</h2>
                <p class="text-xl mb-8 text-blue-100">
                    {{ $clinica->descripcion ?? 'Tu salud es nuestra prioridad. Atención médica de calidad con calidez humana.' }}
                </p>
                @guest
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('register') }}" 
                           class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg text-lg">
                            <i class="fas fa-calendar-check mr-2"></i>Agendar Cita Ahora
                        </a>
                        <a href="#servicios" 
                           class="bg-blue-500 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-400 transition shadow-lg text-lg">
                            <i class="fas fa-info-circle mr-2"></i>Conocer Más
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </section>

    {{-- SERVICIOS --}}
    <section id="servicios" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-stethoscope text-blue-600 mr-3"></i>Nuestros Servicios
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Ofrecemos atención médica integral con especialistas calificados
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($servicios as $servicio)
                    <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300 border border-blue-100">
                        <div class="flex items-start">
                            <div class="bg-blue-600 p-3 rounded-lg mr-4">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg mb-2">{{ $servicio }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- EQUIPO MÉDICO - ACTUALIZADO CON APELLIDO Y ESPECIALIDAD --}}
    @if($medicos->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-md text-blue-600 mr-3"></i>Nuestro Equipo Médico
                </h2>
                <p class="text-gray-600">Profesionales altamente calificados a su servicio</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($medicos as $medico)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto flex items-center justify-center">
                                <i class="fas fa-user-md text-blue-600 text-4xl"></i>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
                            </h3>
                            <p class="text-blue-600 font-semibold mb-2">
                                {{ $medico->especialidad->nombre }}
                            </p>
                            @if($medico->consultorio)
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-door-open mr-1"></i>{{ $medico->consultorio }}
                                </p>
                            @endif
                            @if($medico->turno)
                                <p class="text-xs text-gray-500 mb-3">
                                    <i class="fas fa-clock mr-1"></i>Turno: {{ $medico->turno }}
                                </p>
                            @endif
                            @guest
                                <a href="{{ route('register') }}" 
                                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                    <i class="fas fa-calendar-alt mr-2"></i>Agendar Cita
                                </a>
                            @endguest
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- MISIÓN Y VISIÓN --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-600 p-4 rounded-full mr-4">
                            <i class="fas fa-bullseye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800">Nuestra Misión</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        {{ $clinica->mision ?? 'Brindar atención médica de excelencia con calidez humana.' }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-white p-8 rounded-2xl shadow-lg border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-600 p-4 rounded-full mr-4">
                            <i class="fas fa-eye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800">Nuestra Visión</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        {{ $clinica->vision ?? 'Ser referentes en atención médica de calidad.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section class="py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">
                    <i class="fas fa-map-marker-alt text-blue-400 mr-3"></i>Encuéntranos
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-800 p-6 rounded-xl hover:bg-gray-750 transition">
                    <div class="bg-blue-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Dirección</h3>
                    <p class="text-gray-300">
                        {{ $clinica->direccion ?? 'Calle Beni entre 6 de octubre y Potosí, Nro. 60, Oruro, Bolivia' }}
                    </p>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl hover:bg-gray-750 transition">
                    <div class="bg-green-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Teléfono</h3>
                    <p class="text-gray-300">{{ $clinica->telefono ?? '+591 2 5252525' }}</p>
                    @if($clinica->whatsapp)
                        <p class="text-green-400 mt-2">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp: {{ $clinica->whatsapp }}
                        </p>
                    @endif
                </div>

                <div class="bg-gray-800 p-6 rounded-xl hover:bg-gray-750 transition">
                    <div class="bg-red-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Email</h3>
                    <p class="text-gray-300 break-all">
                        {{ $clinica->email ?? 'info@clinicaislafuente.com' }}
                    </p>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl hover:bg-gray-750 transition">
                    <div class="bg-yellow-600 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Horarios</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        {{ $clinica->horario_atencion ?? 'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 12:00 PM' }}
                    </p>
                </div>
            </div>

            @if($clinica->facebook || $clinica->instagram)
            <div class="text-center mt-12">
                <h3 class="text-2xl font-bold mb-4">Síguenos en Redes Sociales</h3>
                <div class="flex justify-center space-x-6">
                    @if($clinica->facebook)
                        <a href="{{ $clinica->facebook }}" target="_blank"
                           class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                    @endif
                    @if($clinica->instagram)
                        <a href="{{ $clinica->instagram }}" target="_blank"
                           class="bg-pink-600 w-12 h-12 rounded-full flex items-center justify-center hover:bg-pink-700 transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-950 text-gray-400 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">&copy; {{ date('Y') }} {{ $clinica->nombre }}. Todos los derechos reservados.</p>
            <p class="text-sm">Sistema de Consulta Médica | Oruro, Bolivia</p>
        </div>
    </footer>

</body>
</html>

{{-- 
CAMBIOS EN ESTA ACTUALIZACIÓN:
- Médicos muestran apellido completo
- Especialidad viene de la relación (especialidad->nombre)
- Se muestra consultorio y turno si existen
- Uso de auth()->user()->rutaDashboard() para redirección dinámica
--}}