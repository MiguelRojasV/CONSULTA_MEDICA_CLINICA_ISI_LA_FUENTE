{{-- ============================================ --}}
{{-- resources/views/admin/dashboard.blade.php --}}
{{-- Dashboard Principal del Administrador --}}
{{-- ============================================ --}}

@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Panel de Administración</h1>
    <p class="text-gray-600 mt-2">Bienvenido, {{ auth()->user()->name }}. Aquí está el resumen del sistema.</p>
</div>

{{-- Tarjetas de estadísticas principales --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    {{-- Total Pacientes --}}
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Pacientes</p>
                <p class="text-3xl font-bold mt-2">{{ $totalPacientes }}</p>
                <p class="text-blue-100 text-xs mt-2">
                    <i class="fas fa-plus-circle mr-1"></i>
                    {{ $pacientesEsteMes }} este mes
                </p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Total Médicos --}}
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Médicos Activos</p>
                <p class="text-3xl font-bold mt-2">{{ $totalMedicos }}</p>
                <p class="text-green-100 text-xs mt-2">
                    <i class="fas fa-stethoscope mr-1"></i>
                    {{ $totalEspecialidades }} especialidades
                </p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-user-md text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Total Citas --}}
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Citas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalCitas }}</p>
                <p class="text-purple-100 text-xs mt-2">
                    <i class="fas fa-calendar-check mr-1"></i>
                    {{ $citasEstaSemana }} esta semana
                </p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Recetas --}}
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Recetas</p>
                <p class="text-3xl font-bold mt-2">{{ $totalRecetas }}</p>
                <p class="text-orange-100 text-xs mt-2">
                    <i class="fas fa-prescription-bottle mr-1"></i>
                    {{ $recetasEsteMes }} este mes
                </p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-prescription text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Alertas de Medicamentos --}}
    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Alertas</p>
                <p class="text-3xl font-bold mt-2">{{ $medicamentosStockBajo + $medicamentosSinStock }}</p>
                <p class="text-red-100 text-xs mt-2">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Stock bajo/agotado
                </p>
            </div>
            <div class="bg-red-400 bg-opacity-30 rounded-full p-4">
                <i class="fas fa-pills text-3xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Estado de Citas --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
        Estado de Citas
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-r">
            <p class="text-yellow-800 font-semibold text-lg">{{ $citasProgramadas }}</p>
            <p class="text-yellow-600 text-sm">Programadas</p>
        </div>
        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r">
            <p class="text-blue-800 font-semibold text-lg">{{ $citasConfirmadas }}</p>
            <p class="text-blue-600 text-sm">Confirmadas</p>
        </div>
        <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r">
            <p class="text-green-800 font-semibold text-lg">{{ $citasAtendidas }}</p>
            <p class="text-green-600 text-sm">Atendidas</p>
        </div>
        <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded-r">
            <p class="text-red-800 font-semibold text-lg">{{ $citasCanceladas }}</p>
            <p class="text-red-600 text-sm">Canceladas</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Citas de Hoy --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                Citas de Hoy
            </h2>
            <a href="{{ route('admin.citas.index') }}" class="text-blue-600 hover:underline text-sm">
                Ver todas →
            </a>
        </div>

        @if($citasHoy->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($citasHoy as $cita)
                    <div class="border-l-4 
                        {{ $cita->estado == 'Programada' ? 'border-yellow-500 bg-yellow-50' : 
                           ($cita->estado == 'Confirmada' ? 'border-blue-500 bg-blue-50' : 
                           ($cita->estado == 'Atendida' ? 'border-green-500 bg-green-50' : 'border-gray-500 bg-gray-50')) }}
                        p-3 rounded-r hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">
                                    {{ $cita->hora->format('H:i') }} - 
                                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-user-md mr-1"></i>
                                    Dr(a). {{ $cita->medico->nombre }} {{ $cita->medico->apellido }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $cita->medico->especialidad->nombre }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $cita->estado == 'Programada' ? 'bg-yellow-200 text-yellow-800' : 
                                   ($cita->estado == 'Confirmada' ? 'bg-blue-200 text-blue-800' : 
                                   ($cita->estado == 'Atendida' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800')) }}">
                                {{ $cita->estado }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No hay citas programadas para hoy</p>
            </div>
        @endif
    </div>

    {{-- Alertas de Medicamentos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                Alertas de Medicamentos
            </h2>
            <a href="{{ route('admin.medicamentos.index') }}" class="text-blue-600 hover:underline text-sm">
                Ver inventario →
            </a>
        </div>

        <div class="space-y-3">
            @if($medicamentosSinStock > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-800">{{ $medicamentosSinStock }} medicamentos sin stock</p>
                            <p class="text-sm text-red-600">Requieren reabastecimiento inmediato</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($medicamentosStockBajo > 0)
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-orange-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-orange-800">{{ $medicamentosStockBajo }} medicamentos con stock bajo</p>
                            <p class="text-sm text-orange-600">Por debajo del stock mínimo</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($medicamentosVencidos > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-times text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-red-800">{{ $medicamentosVencidos }} medicamentos vencidos</p>
                            <p class="text-sm text-red-600">Requieren ser dados de baja</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($medicamentosPorVencer > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-yellow-800">{{ $medicamentosPorVencer }} medicamentos por vencer</p>
                            <p class="text-sm text-yellow-600">Vencen en los próximos 30 días</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($medicamentosSinStock == 0 && $medicamentosStockBajo == 0 && $medicamentosVencidos == 0 && $medicamentosPorVencer == 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                    <p class="text-green-600 font-semibold">¡Todo en orden!</p>
                    <p class="text-sm">No hay alertas de medicamentos</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Médicos Más Activos y Próximas Citas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Médicos Más Activos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-trophy text-yellow-600 mr-2"></i>
            Médicos Más Activos (Este Mes)
        </h2>

        @if($medicosActivos->count() > 0)
            <div class="space-y-3">
                @foreach($medicosActivos as $medico)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-user-md text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    Dr(a). {{ $medico->nombre }} {{ $medico->apellido }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $medico->especialidad->nombre }}</p>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $medico->citas_count }} citas
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 py-4">No hay datos disponibles este mes</p>
        @endif
    </div>

    {{-- Distribución por Especialidad --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-stethoscope text-purple-600 mr-2"></i>
            Médicos por Especialidad
        </h2>

        @if($especialidades->count() > 0)
            <div class="space-y-2">
                @foreach($especialidades as $especialidad)
                    <div class="flex items-center justify-between p-3 border-b border-gray-100">
                        <div>
                            <p class="font-medium text-gray-800">{{ $especialidad->nombre }}</p>
                        </div>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $especialidad->medicos_count }} 
                            {{ $especialidad->medicos_count == 1 ? 'médico' : 'médicos' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 py-4">No hay especialidades registradas</p>
        @endif
    </div>
</div>
@endsection