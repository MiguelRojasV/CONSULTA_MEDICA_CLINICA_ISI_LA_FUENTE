
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel del Médico') - Clínica ISI La Fuente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    {{-- Navbar del Médico --}}
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo y nombre --}}
                <div class="flex items-center space-x-3">
                    <i class="fas fa-user-md text-3xl"></i>
                    <div>
                        <h1 class="text-xl font-bold">Clínica ISI La Fuente</h1>
                        <p class="text-xs text-green-200">Panel Médico</p>
                    </div>
                </div>

                {{-- Menú de navegación --}}
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('medico.dashboard') }}" 
                       class="hover:text-green-200 transition {{ request()->routeIs('medico.dashboard') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                    <a href="{{ route('medico.citas.index') }}" 
                       class="hover:text-green-200 transition {{ request()->routeIs('medico.citas.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i>Agenda
                    </a>
                    <a href="{{ route('medico.pacientes.index') }}" 
                       class="hover:text-green-200 transition {{ request()->routeIs('medico.pacientes.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-users mr-2"></i>Pacientes
                    </a>
                    <a href="{{ route('medico.recetas.index') }}" 
                       class="hover:text-green-200 transition {{ request()->routeIs('medico.recetas.*') ? 'border-b-2 border-white' : '' }}">
                        <i class="fas fa-prescription mr-2"></i>Recetas
                    </a>
                </div>

                {{-- Usuario y logout --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('medico.perfil.show') }}" 
                       class="hover:text-green-200 transition">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-green-200 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="container mx-auto px-4 py-8">
        {{-- Mensajes --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

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
        <p>&copy; {{ date('Y') }} Clínica ISI La Fuente - Panel Médico</p>
    </footer>

</body>
</html>