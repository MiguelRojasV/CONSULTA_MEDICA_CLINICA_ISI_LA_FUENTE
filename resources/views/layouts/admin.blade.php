{{-- ============================================ --}}
{{-- resources/views/layouts/admin.blade.php --}}
{{-- Layout Base para el Panel del Administrador --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración') - Clínica ISI La Fuente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    {{-- Navbar del Administrador --}}
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo y nombre --}}
                <div class="flex items-center space-x-3">
                    <i class="fas fa-hospital-user text-3xl"></i>
                    <div>
                        <h1 class="text-xl font-bold">Clínica ISI La Fuente</h1>
                        <p class="text-xs text-red-200">Panel de Administración</p>
                    </div>
                </div>

                {{-- Menú de navegación --}}
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                    <a href="{{ route('admin.pacientes.index') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.pacientes.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-users mr-2"></i>Pacientes
                    </a>
                    <a href="{{ route('admin.medicos.index') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.medicos.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-user-md mr-2"></i>Médicos
                    </a>
                    <a href="{{ route('admin.citas.index') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.citas.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i>Citas
                    </a>
                    <a href="{{ route('admin.medicamentos.index') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.medicamentos.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-pills mr-2"></i>Medicamentos
                    </a>
                    <a href="{{ route('admin.reportes.index') }}" 
                       class="hover:text-red-200 transition {{ request()->routeIs('admin.reportes.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-chart-bar mr-2"></i>Reportes
                    </a>
                </div>

                {{-- Usuario y logout --}}
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-red-200 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="container mx-auto px-4 py-8">
        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded animate-fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-semibold mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Por favor corrija los siguientes errores:
                </p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; {{ date('Y') }} Clínica ISI La Fuente - Panel de Administración</p>
    </footer>

</body>
</html>

{{-- 
CARACTERÍSTICAS DEL LAYOUT ADMIN:
1. Navbar rojo (distintivo del administrador)
2. Menú completo con todos los módulos
3. Links activos destacados
4. Usuario actual visible
5. Mensajes de éxito/error con animación
6. Footer institucional
7. Responsive
8. Iconos Font Awesome
9. Manejo completo de errores de validación
10. Diseño profesional y limpio
--}}