{{-- ============================================ --}}
{{-- resources/views/admin/reportes/index.blade.php --}}
{{-- Panel Principal de Reportes --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Reportes del Sistema')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Centro de Reportes y Estadísticas</h1>
    <p class="text-gray-600 mt-2">Genera reportes detallados del sistema</p>
</div>

{{-- Estadísticas Generales --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Pacientes</p>
                <p class="text-3xl font-bold mt-2">{{ $totalPacientes }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Médicos Activos</p>
                <p class="text-3xl font-bold mt-2">{{ $totalMedicos }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-user-md text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Total Citas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalCitas }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Total Recetas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalRecetas }}</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-prescription text-3xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Tipos de Reportes Disponibles --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Reporte de Citas --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
        <div class="flex items-center mb-4">
            <div class="bg-purple-100 rounded-full p-4 mr-4">
                <i class="fas fa-calendar-alt text-purple-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Citas</h2>
                <p class="text-sm text-gray-600">Por fecha y médico</p>
            </div>
        </div>
        <p class="text-gray-600 mb-4">
            Genera reportes detallados de citas médicas por rango de fechas, médicos y estados.
        </p>
        <a href="{{ route('admin.reportes.citas') }}" 
           class="block text-center bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-chart-line mr-2"></i>Ver Reporte
        </a>
    </div>

    {{-- Reporte de Pacientes --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 rounded-full p-4 mr-4">
                <i class="fas fa-users text-blue-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Pacientes</h2>
                <p class="text-sm text-gray-600">Estadísticas generales</p>
            </div>
        </div>
        <p class="text-gray-600 mb-4">
            Análisis de pacientes registrados, distribución por género, edad y grupo sanguíneo.
        </p>
        <a href="{{ route('admin.reportes.pacientes') }}" 
           class="block text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-chart-pie mr-2"></i>Ver Reporte
        </a>
    </div>

    {{-- Reporte de Médicos --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 rounded-full p-4 mr-4">
                <i class="fas fa-user-md text-green-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Médicos</h2>
                <p class="text-sm text-gray-600">Actividad y estadísticas</p>
            </div>
        </div>
        <p class="text-gray-600 mb-4">
            Análisis de médicos activos, citas atendidas, especialidades y desempeño.
        </p>
        <a href="{{ route('admin.reportes.medicos') }}" 
           class="block text-center bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-chart-bar mr-2"></i>Ver Reporte
        </a>
    </div>

    {{-- Reporte de Medicamentos --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
        <div class="flex items-center mb-4">
            <div class="bg-orange-100 rounded-full p-4 mr-4">
                <i class="fas fa-pills text-orange-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Medicamentos</h2>
                <p class="text-sm text-gray-600">Inventario y alertas</p>
            </div>
        </div>
        <p class="text-gray-600 mb-4">
            Control de inventario, alertas de stock, medicamentos vencidos y valor total.
        </p>
        <a href="{{ route('admin.reportes.medicamentos') }}" 
           class="block text-center bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition">
            <i class="fas fa-boxes mr-2"></i>Ver Reporte
        </a>
    </div>

    {{-- Búsqueda Avanzada --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
        <div class="flex items-center mb-4">
            <div class="bg-indigo-100 rounded-full p-4 mr-4">
                <i class="fas fa-search text-indigo-600 text-3xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Búsqueda Avanzada</h2>
                <p class="text-sm text-gray-600">Consultas personalizadas</p>
            </div>
        </div>
        <p class="text-gray-600 mb-4">
            Realiza búsquedas avanzadas por múltiples criterios en todo el sistema.
        </p>
        <a href="{{ route('admin.busqueda') }}" 
           class="block text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-filter mr-2"></i>Búsqueda Avanzada
        </a>
    </div>
</div>
@endsection