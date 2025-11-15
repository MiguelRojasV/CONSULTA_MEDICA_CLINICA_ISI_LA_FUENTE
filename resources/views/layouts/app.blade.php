<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clínica ISI La Fuente')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="text-2xl font-bold">
                    <a href="{{ route('dashboard') }}">Clínica ISI La Fuente</a>
                </div>
                <div class="flex space-x-6">
                    <a href="{{ route('pacientes.index') }}" class="hover:text-blue-200">
                        <i class="fas fa-users"></i> Pacientes
                    </a>
                    <a href="{{ route('medicos.index') }}" class="hover:text-blue-200">
                        <i class="fas fa-user-md"></i> Médicos
                    </a>
                    <a href="{{ route('citas.index') }}" class="hover:text-blue-200">
                        <i class="fas fa-calendar-alt"></i> Citas
                    </a>
                    <a href="{{ route('medicamentos.index') }}" class="hover:text-blue-200">
                        <i class="fas fa-pills"></i> Medicamentos
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; 2025 Clínica ISI La Fuente - Oruro, Bolivia</p>
    </footer>
</body>
</html>