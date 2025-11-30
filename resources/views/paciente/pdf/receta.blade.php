{{-- ============================================ --}}
{{-- 1. resources/views/pdf/receta.blade.php --}}
{{-- PDF de Receta Médica --}}
{{-- ============================================ --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta Médica - {{ $receta->paciente->nombre_completo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #2563eb;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px;
            width: 30%;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .medicamentos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .medicamentos-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }
        .medicamentos-table td {
            padding: 10px;
            border: 1px solid #d1d5db;
        }
        .medicamentos-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .indicaciones-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-top: 15px;
        }
        .firma-section {
            margin-top: 60px;
            text-align: center;
        }
        .firma-line {
            border-top: 2px solid #333;
            width: 300px;
            margin: 0 auto 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-dispensada {
            background-color: #d1fae5;
            color: #065f46;
        }
        .alert-box {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>{{ $clinica->nombre ?? 'Clínica ISI La Fuente' }}</h1>
        <p>{{ $clinica->direccion ?? 'Oruro, Bolivia' }}</p>
        <p>Tel: {{ $clinica->telefono ?? '02-5252525' }} | Email: {{ $clinica->email ?? 'info@clinica.com' }}</p>
        <p style="margin-top: 10px; font-size: 16px; font-weight: bold;">RECETA MÉDICA</p>
    </div>

    {{-- Información del Paciente --}}
    <div class="section">
        <div class="section-title">DATOS DEL PACIENTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre Completo:</div>
                <div class="info-value">{{ $receta->paciente->nombre_completo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">CI:</div>
                <div class="info-value">{{ $receta->paciente->ci }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Edad:</div>
                <div class="info-value">{{ $receta->paciente->edad }} años</div>
            </div>
            @if($receta->paciente->grupo_sanguineo)
            <div class="info-row">
                <div class="info-label">Grupo Sanguíneo:</div>
                <div class="info-value">{{ $receta->paciente->grupo_sanguineo }}</div>
            </div>
            @endif
            @if($receta->paciente->alergias)
            <div class="info-row">
                <div class="info-label" style="color: #dc2626;">Alergias:</div>
                <div class="info-value" style="color: #dc2626; font-weight: bold;">{{ $receta->paciente->alergias }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Información del Médico --}}
    <div class="section">
        <div class="section-title">MÉDICO TRATANTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">Dr(a). {{ $receta->medico->nombre_completo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Especialidad:</div>
                <div class="info-value">{{ $receta->medico->especialidad->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matrícula:</div>
                <div class="info-value">{{ $receta->medico->matricula }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Emisión:</div>
                <div class="info-value">{{ $receta->fecha_emision->format('d/m/Y') }}</div>
            </div>
            @if($receta->valida_hasta)
            <div class="info-row">
                <div class="info-label">Válida Hasta:</div>
                <div class="info-value">{{ $receta->valida_hasta->format('d/m/Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Medicamentos Prescritos --}}
    <div class="section">
        <div class="section-title">MEDICAMENTOS PRESCRITOS</div>
        <table class="medicamentos-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Medicamento</th>
                    <th style="width: 15%;">Cantidad</th>
                    <th style="width: 20%;">Dosis</th>
                    <th style="width: 20%;">Frecuencia</th>
                    <th style="width: 15%;">Duración</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receta->medicamentos as $medicamento)
                <tr>
                    <td>
                        <strong>{{ $medicamento->nombre_generico }}</strong>
                        @if($medicamento->nombre_comercial)
                            <br><small>({{ $medicamento->nombre_comercial }})</small>
                        @endif
                        <br><small>{{ $medicamento->presentacion }}</small>
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ $medicamento->pivot->cantidad }}</td>
                    <td>{{ $medicamento->pivot->dosis }}</td>
                    <td>{{ $medicamento->pivot->frecuencia }}</td>
                    <td>{{ $medicamento->pivot->duracion }}</td>
                </tr>
                @if($medicamento->pivot->instrucciones_especiales)
                <tr>
                    <td colspan="5" style="background-color: #fef3c7; font-style: italic;">
                        <strong>Instrucciones:</strong> {{ $medicamento->pivot->instrucciones_especiales }}
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Indicaciones Generales --}}
    @if($receta->indicaciones)
    <div class="indicaciones-box">
        <strong>INDICACIONES GENERALES:</strong><br>
        {{ $receta->indicaciones }}
    </div>
    @endif

    {{-- Observaciones --}}
    @if($receta->observaciones)
    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <p>{{ $receta->observaciones }}</p>
    </div>
    @endif

    {{-- Advertencias --}}
    <div class="alert-box">
        <strong>IMPORTANTE:</strong>
        <ul style="margin-left: 20px; margin-top: 5px;">
            <li>No automedicarse</li>
            <li>Seguir estrictamente las indicaciones del médico</li>
            <li>Completar el tratamiento según indicaciones</li>
            <li>En caso de efectos adversos, consultar inmediatamente</li>
        </ul>
    </div>

    {{-- Firma del Médico --}}
    <div class="firma-section">
        <div class="firma-line"></div>
        <strong>Dr(a). {{ $receta->medico->nombre_completo }}</strong><br>
        {{ $receta->medico->especialidad->nombre }}<br>
        Matrícula: {{ $receta->medico->matricula }}
    </div>

    {{-- Pie de página --}}
    <div class="footer">
        <p>Este documento es un registro oficial de la prescripción médica</p>
        <p>Receta N° {{ str_pad($receta->id, 6, '0', STR_PAD_LEFT) }} | Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <p style="margin-top: 5px;">
            Estado: 
            <span class="status-badge {{ $receta->estado == 'Dispensada' ? 'status-dispensada' : 'status-pendiente' }}">
                {{ $receta->estado }}
            </span>
        </p>
    </div>
</body>
</html>