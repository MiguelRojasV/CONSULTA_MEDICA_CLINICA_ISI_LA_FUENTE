<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo HistorialMedico
 * Ubicación: app/Models/HistorialMedico.php
 * 
 * Registro completo de atenciones médicas del paciente
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos agregados: sintomas, signos_vitales, examenes_solicitados
 * - Métodos de búsqueda y filtrado mejorados
 * - Relaciones completas con médico y especialidad
 */
class HistorialMedico extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'historial_medico';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'paciente_id',
        'cita_id',
        'medico_id',
        'fecha',
        'tipo_atencion',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'sintomas',
        'signos_vitales',
        'examenes_solicitados',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'fecha' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con Paciente
     * Un historial pertenece a un paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relación con Cita
     * Un historial puede estar asociado a una cita
     */
    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    /**
     * Relación con Médico
     * Un historial fue registrado por un médico
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Historial por paciente
     */
    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Historial por médico
     */
    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    /**
     * Historial por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Historial del último mes
     */
    public function scopeUltimoMes($query)
    {
        return $query->where('fecha', '>=', now()->subMonth());
    }

    /**
     * Historial del último año
     */
    public function scopeUltimoAnio($query)
    {
        return $query->where('fecha', '>=', now()->subYear());
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Verifica si tiene síntomas registrados
     * @return bool
     */
    public function tieneSintomas(): bool
    {
        return !empty($this->sintomas);
    }

    /**
     * Verifica si tiene signos vitales registrados
     * @return bool
     */
    public function tieneSignosVitales(): bool
    {
        return !empty($this->signos_vitales);
    }

    /**
     * Verifica si tiene exámenes solicitados
     * @return bool
     */
    public function tieneExamenesSolicitados(): bool
    {
        return !empty($this->examenes_solicitados);
    }

    /**
     * Obtiene el nombre del médico que atendió
     * @return string
     */
    public function nombreMedico(): string
    {
        if (!$this->medico) {
            return 'Médico no asignado';
        }

        return $this->medico->nombre_completo;
    }

    /**
     * Obtiene la especialidad del médico
     * @return string
     */
    public function especialidadMedico(): string
    {
        if (!$this->medico || !$this->medico->especialidad) {
            return 'No especificada';
        }

        return $this->medico->especialidad->nombre;
    }

    /**
     * Formatea la fecha de atención
     * @return string
     */
    public function fechaFormateada(): string
    {
        return $this->fecha->format('d/m/Y');
    }
}