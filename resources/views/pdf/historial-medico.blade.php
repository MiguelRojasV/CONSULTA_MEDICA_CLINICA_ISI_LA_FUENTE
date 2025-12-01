{{-- ============================================ --}}
{{-- 2. resources/views/pdf/historial-medico.blade.php --}}
{{-- PDF de Historial Médico del Paciente --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico - {{ $paciente->nombre_completo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #059669;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #059669;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 10px;
        }
        .patient-info {
            background-color: #f0fdf4;
            border-left: 4px solid #059669;
            padding: 15px;
            margin-bottom: 20px;
        }
        .patient-info h2 {
            color: #059669;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 3px 10px 3px 0;
            width: 25%;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
        }
        .alert-section {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 10px;
            margin: 15px 0;
        }
        .alert-section h3 {
            color: #dc2626;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .section-title {
            background-color: #059669;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        .registro {
            border: 1px solid #d1d5db;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .registro-header {
            background-color: #f3f4f6;
            padding: 10px;
            border-bottom: 2px solid #059669;
        }
        .registro-body {
            padding: 10px;
        }
        .registro-fecha {
            font-weight: bold;
            color: #059669;
            font-size: 12px;
        }
        .registro-medico {
            color: #666;
            font-size: 10px;
        }
        .field-label {
            font-weight: bold;
            color: #374151;
            margin-top: 8px;
            display: block;
        }
        .field-value {
            color: #1f2937;
            margin-top: 2px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .no-records {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>{{ $clinica->nombre ?? 'Clínica ISI La Fuente' }}</h1>
        <p>{{ $clinica->direccion ?? 'Oruro, Bolivia' }}</p>
        <p>Tel: {{ $clinica->telefono ?? '02-5252525' }} | Email: {{ $clinica->email ?? 'info@clinica.com' }}</p>
        <p style="margin-top: 10px; font-size: 16px; font-weight: bold;">HISTORIAL MÉDICO</p>
    </div>

    {{-- Información del Paciente --}}
    <div class="patient-info">
        <h2>DATOS DEL PACIENTE</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre Completo:</div>
                <div class="info-value">{{ $paciente->nombre_completo }}</div>
                <div class="info-label">CI:</div>
                <div class="info-value">{{ $paciente->ci }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Nacimiento:</div>
                <div class="info-value">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</div>
                <div class="info-label">Edad:</div>
                <div class="info-value">{{ $paciente->edad }} años</div>
            </div>
            <div class="info-row">
                <div class="info-label">Género:</div>
                <div class="info-value">{{ $paciente->genero }}</div>
                @if($paciente->grupo_sanguineo)
                <div class="info-label">Grupo Sanguíneo:</div>
                <div class="info-value"><strong>{{ $paciente->grupo_sanguineo }}</strong></div>
                @endif
            </div>
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $paciente->telefono }}</div>
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $paciente->email }}</div>
            </div>
        </div>
    </div>

    {{-- Alertas Médicas --}}
    @if($paciente->alergias || $paciente->antecedentes)
    <div class="alert-section">
        <h3>⚠️ ALERTAS MÉDICAS IMPORTANTES</h3>
        @if($paciente->alergias)
        <p><strong>Alergias:</strong> {{ $paciente->alergias }}</p>
        @endif
        @if($paciente->antecedentes)
        <p><strong>Antecedentes:</strong> {{ $paciente->antecedentes }}</p>
        @endif
    </div>
    @endif

    {{-- Resumen --}}
    <div style="background-color: #f9fafb; padding: 10px; margin-bottom: 15px; border-left: 4px solid #6366f1;">
        <strong>Resumen del Historial:</strong> 
        {{ $historial->count() }} registro(s) médico(s) | 
        Última atención: {{ $historial->first() ? $historial->first()->fecha->format('d/m/Y') : 'Sin registros' }}
    </div>

    {{-- Registros del Historial --}}
    <div class="section-title">REGISTROS MÉDICOS</div>

    @forelse($historial as $registro)
    <div class="registro">
        <div class="registro-header">
            <div class="registro-fecha">
                📅 {{ $registro->fecha->format('d/m/Y') }} - {{ $registro->tipo_atencion }}
            </div>
            <div class="registro-medico">
                👨‍⚕️ Atendido por: Dr(a). {{ $registro->medico ? $registro->medico->nombre_completo : 'No especificado' }}
                @if($registro->medico && $registro->medico->especialidad)
                    | {{ $registro->medico->especialidad->nombre }}
                @endif
            </div>
        </div>
        <div class="registro-body">
            @if($registro->sintomas)
            <span class="field-label">Síntomas:</span>
            <div class="field-value">{{ $registro->sintomas }}</div>
            @endif

            @if($registro->signos_vitales)
            <span class="field-label">Signos Vitales:</span>
            <div class="field-value">{{ $registro->signos_vitales }}</div>
            @endif

            <span class="field-label">Diagnóstico:</span>
            <div class="field-value">{{ $registro->diagnostico }}</div>

            @if($registro->tratamiento)
            <span class="field-label">Tratamiento:</span>
            <div class="field-value">{{ $registro->tratamiento }}</div>
            @endif

            @if($registro->examenes_solicitados)
            <span class="field-label">Exámenes Solicitados:</span>
            <div class="field-value">{{ $registro->examenes_solicitados }}</div>
            @endif

            @if($registro->observaciones)
            <span class="field-label">Observaciones:</span>
            <div class="field-value" style="font-style: italic;">{{ $registro->observaciones }}</div>
            @endif
        </div>
    </div>
    @empty
    <div class="no-records">
        No hay registros médicos disponibles para este paciente
    </div>
    @endforelse

    {{-- Pie de página --}}
    <div class="footer">
        <p><strong>DOCUMENTO CONFIDENCIAL - USO MÉDICO EXCLUSIVO</strong></p>
        <p>Este historial médico contiene información sensible y está protegido por la ley de confidencialidad médica</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }} | Total de registros: {{ $historial->count() }}</p>
        <p style="margin-top: 10px;">{{ $clinica->nombre ?? 'Clínica ISI La Fuente' }} - Sistema de Gestión Médica</p>
    </div>
</body>
</html>