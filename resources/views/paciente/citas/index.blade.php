{{-- ============================================ --}}
{{-- resources/views/paciente/citas/index.blade.php --}}
{{-- Vista: Lista de Citas del Paciente --}}
{{-- ============================================ --}}

@extends('layouts.paciente')

@section('title', 'Mis Citas Médicas')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mis Citas Médicas</h1>
            <p class="text-gray-600 mt-2">Gestione sus consultas médicas programadas</p>
        </div>
        <a href="{{ route('paciente.citas.create') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>Agendar Nueva Cita
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form action="{{ route('paciente.citas.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                <option value="Programada" {{ request('estado') == 'Programada' ? 'selected' : '' }}>Programada</option>
                <option value="Confirmada" {{ request('estado') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="Atendida" {{ request('estado') == 'Atendida' ? 'selected' : '' }}>Atendida</option>
                <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-filter mr-2"></i>Filtrar
        </button>
        <a href="{{ route('paciente.citas.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-redo mr-2"></i>Limpiar
        </a>
    </form>
</div>

{{-- Tabs: Próximas / Historial --}}
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="?tab=proximas" 
               class="border-b-2 py-4 px-1 text-sm font-medium {{ request('tab', 'proximas') == 'proximas' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-calendar-check mr-2"></i>
                Próximas Citas ({{ $proximasCitas->count() }})
            </a>
            <a href="?tab=historial" 
               class="border-b-2 py-4 px-1 text-sm font-medium {{ request('tab') == 'historial' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-history mr-2"></i>
                Historial ({{ $citasHistorial->count() }})
            </a>
        </nav>
    </div>
</div>

{{-- Contenido según tab seleccionada --}}
@if(request('tab') == 'historial')
    {{-- HISTORIAL DE CITAS --}}
    @if($citasHistorial->count() > 0)
        <div class="space-y-4">
            @foreach($citasHistorial as $cita)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-lg font-bold text-gray-800">
                                    <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                    {{ $cita->fecha->format('d/m/Y') }}
                                </span>
                                <span class="text-lg font-semibold text-gray-700">
                                    <i class="fas fa-clock mr-2 text-green-600"></i>
                                    {{ $cita->hora->format('H:i') }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $cita->estado == 'Atendida' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $cita->estado }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Médico</p>
                                    <p class="font-semibold text-gray-800">
                                        <i class="fas fa-user-md mr-2 text-blue-600"></i>
                                        Dr(a). {{ $cita->medico->nombre }} {{ $cita->medico->apellido }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $cita->medico->especialidad->nombre }}</p>
                                </div>

                                @if($cita->diagnostico)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Diagnóstico</p>
                                    <p class="text-gray-800">{{ Str::limit($cita->diagnostico, 100) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('paciente.citas.show', $cita) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-eye mr-2"></i>Ver Detalles
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No tiene citas en el historial</p>
        </div>
    @endif

@else
    {{-- PRÓXIMAS CITAS --}}
    @if($proximasCitas->count() > 0)
        <div class="space-y-4">
            @foreach($proximasCitas as $cita)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4
                    {{ $cita->estado == 'Programada' ? 'border-blue-500' : 'border-green-500' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-lg font-bold text-gray-800">
                                    <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                    {{ $cita->fecha->format('d/m/Y') }}
                                </span>
                                <span class="text-lg font-semibold text-gray-700">
                                    <i class="fas fa-clock mr-2 text-green-600"></i>
                                    {{ $cita->hora->format('H:i') }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $cita->estado == 'Programada' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $cita->estado }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Médico</p>
                                    <p class="font-semibold text-gray-800">
                                        <i class="fas fa-user-md mr-2 text-blue-600"></i>
                                        Dr(a). {{ $cita->medico->nombre }} {{ $cita->medico->apellido }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $cita->medico->especialidad->nombre }}</p>
                                </div>

                                @if($cita->consultorio)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Consultorio</p>
                                    <p class="text-gray-800">
                                        <i class="fas fa-door-open mr-2 text-purple-600"></i>
                                        {{ $cita->medico->consultorio }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            @if($cita->motivo)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Motivo de consulta</p>
                                <p class="text-gray-800">{{ $cita->motivo }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-col space-y-2 ml-4">
                            <a href="{{ route('paciente.citas.show', $cita) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm text-center">
                                <i class="fas fa-eye mr-2"></i>Ver
                            </a>
                            @if($cita->estado != 'Cancelada' && $cita->estado != 'Atendida')
                                <form action="{{ route('paciente.citas.cancelar', $cita) }}" method="POST" 
                                      onsubmit="return confirm('¿Está seguro de cancelar esta cita?')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                                        <i class="fas fa-times mr-2"></i>Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-calendar-plus text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">No tiene citas próximas programadas</p>
            <a href="{{ route('paciente.citas.create') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Agendar Nueva Cita
            </a>
        </div>
    @endif
@endif

{{-- Paginación si es necesaria --}}
@if(request('tab') == 'historial' && $citasHistorial->hasPages())
    <div class="mt-6">
        {{ $citasHistorial->links() }}
    </div>
@elseif($proximasCitas->hasPages())
    <div class="mt-6">
        {{ $proximasCitas->links() }}
    </div>
@endif

@endsection

{{-- 
CARACTERÍSTICAS:
1. Tabs para separar próximas citas e historial
2. Filtros por estado
3. Tarjetas con toda la información de la cita
4. Botón para cancelar citas (si aplica)
5. Colores distintivos por estado
6. Información del médico con especialidad
7. Diseño responsive
8. Mensajes cuando no hay citas
9. Paginación incluida
--}}
