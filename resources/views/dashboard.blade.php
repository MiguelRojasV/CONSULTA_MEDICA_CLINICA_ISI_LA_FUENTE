@extends('layouts.app')

@section('title', 'Dashboard - Clínica ISI La Fuente')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Panel de Control</h1>
    <p class="text-gray-600 mt-2">Sistema de Información de Consulta Médica</p>
</div>

{{-- Tarjetas de resumen --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Pacientes --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-users text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Total Pacientes</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Paciente::count() }}
                </p>
            </div>
        </div>
        <a href="{{ route('pacientes.index') }}" class="text-blue-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
    </div>

    {{-- Médicos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-user-md text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Médicos</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Medico::count() }}
                </p>
            </div>
        </div>
        <a href="{{ route('medicos.index') }}" class="text-green-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
    </div>

    {{-- Citas de hoy --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-calendar-alt text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Citas Hoy</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Cita::whereDate('fecha', now()->toDateString())->count() }}
                </p>
            </div>
        </div>
        <a href="{{ route('citas.index') }}" class="text-yellow-600 text-sm mt-4 inline-block hover:underline">
            Ver todas →
        </a>
    </div>

    {{-- Medicamentos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-pills text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Medicamentos</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Medicamento::count() }}
                </p>
            </div>
        </div>
        <a href="{{ route('medicamentos.index') }}" class="text-purple-600 text-sm mt-4 inline-block hover:underline">
            Ver todos →
        </a>
    </div>
</div>

{{-- Sección inferior --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Próximas citas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Próximas Citas</h2>
        @php
            use Carbon\Carbon;
            $proximasCitas = \App\Models\Cita::with(['paciente', 'medico'])
                ->whereDate('fecha', '>=', Carbon::today())
                ->where('estado', '!=', 'Cancelada')
                ->orderBy('fecha')
                ->orderBy('hora')
                ->limit(5)
                ->get();
        @endphp

        @if($proximasCitas->isNotEmpty())
            <div class="space-y-3">
                @foreach($proximasCitas as $cita)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="font-semibold text-gray-800">{{ $cita->paciente->nombre ?? 'Sin paciente' }}</p>
                        <p class="text-sm text-gray-600">
                            Dr(a). {{ $cita->medico->nombre ?? 'No asignado' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No hay citas programadas</p>
        @endif
    </div>

    {{-- Acceso rápido --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Acceso Rápido</h2>
        <div class="space-y-3">
            <a href="{{ route('citas.create') }}" 
               class="block bg-blue-600 text-white rounded-lg px-4 py-3 hover:bg-blue-700 transition">
                <i class="fas fa-plus-circle mr-2"></i> Nueva Cita
            </a>

            <a href="{{ route('pacientes.create') }}" 
               class="block bg-green-600 text-white rounded-lg px-4 py-3 hover:bg-green-700 transition">
                <i class="fas fa-user-plus mr-2"></i> Registrar Paciente
            </a>

            <a href="{{ route('medicos.create') }}" 
               class="block bg-purple-600 text-white rounded-lg px-4 py-3 hover:bg-purple-700 transition">
                <i class="fas fa-user-md mr-2"></i> Registrar Médico
            </a>
        </div>
    </div>
</div>
@endsection
