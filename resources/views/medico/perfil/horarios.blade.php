{{-- ============================================ --}}
{{-- resources/views/medico/perfil/horarios.blade.php --}}
{{-- Vista: Horarios de Atención del Médico --}}
{{-- ============================================ --}}

@extends('layouts.medico')

@section('title', 'Mis Horarios de Atención')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Mis Horarios de Atención</h1>
            <p class="text-gray-600 mt-2">Consulte su disponibilidad semanal</p>
        </div>
        <a href="{{ route('medico.perfil.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver al Perfil
        </a>
    </div>
</div>

{{-- Información General --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-calendar-week text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Días Laborables</p>
                <p class="text-2xl font-bold text-gray-800">{{ $horarios->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-clock text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Turno Asignado</p>
                <p class="text-2xl font-bold text-gray-800">{{ $medico->turno ?? 'No asignado' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-door-open text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Consultorio</p>
                <p class="text-2xl font-bold text-gray-800">{{ $medico->consultorio ?? 'No asignado' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de Horarios --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b bg-gradient-to-r from-green-50 to-white">
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
            Horario Semanal de Atención
        </h2>
    </div>

    @if($horarios->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar-day mr-2"></i>Día
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2"></i>Hora Inicio
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2"></i>Hora Fin
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hourglass-half mr-2"></i>Duración
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Estado
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($horarios as $horario)
                        <tr class="hover:bg-gray-50 transition {{ $horario->activo ? '' : 'opacity-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-800">
                                    {{ $horario->dia_semana }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700">
                                    <i class="fas fa-arrow-right text-green-600 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700">
                                    <i class="fas fa-arrow-left text-red-600 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700">
                                    @php
                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                        $duracion = $inicio->diffInHours($fin);
                                    @endphp
                                    {{ $duracion }} hora{{ $duracion > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $horario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No tiene horarios configurados</p>
            <p class="text-gray-400 text-sm mt-2">Contacte al administrador para configurar sus horarios de atención</p>
        </div>
    @endif
</div>

{{-- Vista de Calendario Semanal --}}
@if($horarios->count() > 0)
<div class="mt-8 bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-calendar text-blue-600 mr-2"></i>
        Vista de Calendario Semanal
    </h2>

    <div class="grid grid-cols-7 gap-2">
        @php
            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        @endphp

        @foreach($dias as $dia)
            @php
                $horarioDia = $horarios->firstWhere('dia_semana', $dia);
            @endphp
            <div class="border rounded-lg p-3 {{ $horarioDia ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="text-center">
                    <p class="font-semibold text-sm text-gray-800 mb-2">{{ $dia }}</p>
                    @if($horarioDia)
                        <div class="text-xs text-gray-700">
                            <p class="mb-1">
                                <i class="fas fa-clock text-green-600 mr-1"></i>
                                {{ \Carbon\Carbon::parse($horarioDia->hora_inicio)->format('H:i') }}
                            </p>
                            <p>
                                <i class="fas fa-clock text-red-600 mr-1"></i>
                                {{ \Carbon\Carbon::parse($horarioDia->hora_fin)->format('H:i') }}
                            </p>
                            @if($horarioDia->activo)
                                <span class="inline-block mt-2 px-2 py-1 bg-green-500 text-white rounded text-xs">
                                    <i class="fas fa-check"></i> Disponible
                                </span>
                            @else
                                <span class="inline-block mt-2 px-2 py-1 bg-gray-400 text-white rounded text-xs">
                                    <i class="fas fa-times"></i> Inactivo
                                </span>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-gray-400">
                            <i class="fas fa-times-circle"></i><br>
                            Sin horario
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Información adicional --}}
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <p class="text-sm text-gray-700">
        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
        <strong>Nota:</strong> Los horarios mostrados son configurados por el administrador del sistema. Si necesita modificar sus horarios de atención, por favor contacte al personal administrativo.
    </p>
</div>
@endsection

{{-- 
CARACTERÍSTICAS DE ESTA VISTA:
1. Muestra horarios semanales del médico
2. Tabla ordenada por día de la semana
3. Calcula automáticamente la duración de cada jornada
4. Vista de calendario semanal visual
5. Indicadores de estado (Activo/Inactivo)
6. Información resumida en tarjetas superiores
7. Diseño responsive
8. Colores distintivos para días con/sin horario
9. Compatible con la tabla horarios_atencion
10. Mensaje cuando no hay horarios configurados
--}}