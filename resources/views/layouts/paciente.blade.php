<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel del Paciente') - Clínica ISI La Fuente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    {{-- Navbar del Paciente --}}
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo y nombre --}}
                <div class="flex items-center space-x-3">
                    <i class="fas fa-hospital text-3xl"></i>
                    <div>
                        <h1 class="text-xl font-bold">Clínica ISI La Fuente</h1>
                        <p class="text-xs text-blue-200">Panel del Paciente</p>
                    </div>
                </div>

                {{-- Menú de navegación --}}
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('paciente.dashboard') }}" 
                       class="hover:text-blue-200 transition {{ request()->routeIs('paciente.dashboard') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                    <a href="{{ route('paciente.citas.index') }}" 
                       class="hover:text-blue-200 transition {{ request()->routeIs('paciente.citas.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i>Mis Citas
                    </a>
                    <a href="{{ route('paciente.recetas.index') }}" 
                       class="hover:text-blue-200 transition {{ request()->routeIs('paciente.recetas.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-prescription mr-2"></i>Recetas
                    </a>
                    <a href="{{ route('paciente.historial.index') }}" 
                       class="hover:text-blue-200 transition {{ request()->routeIs('paciente.historial.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-file-medical mr-2"></i>Historial
                    </a>
                </div>

                {{-- Usuario y logout --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('paciente.perfil.show') }}" 
                       class="hover:text-blue-200 transition">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-blue-200 transition">
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
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-times mr-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; {{ date('Y') }} Clínica ISI La Fuente - Panel del Paciente</p>
    </footer>

</body>
</html>