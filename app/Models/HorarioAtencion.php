<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modelo HorarioAtencion
 * Ubicación: app/Models/HorarioAtencion.php
 * 
 * Define los horarios de disponibilidad de cada médico
 * Permite generar slots de tiempo para agendar citas
 */
class HorarioAtencion extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'horarios_atencion';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'medico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con Médico
     * Un horario pertenece a un médico
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Horarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Horarios por día
     */
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    /**
     * Horarios de un médico
     */
    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    // ============================================
    // MÉTODOS DE VALIDACIÓN
    // ============================================

    /**
     * Verifica si el horario está activo para un día específico
     * @param string $dia Nombre del día (ej: 'Lunes')
     * @return bool
     */
    public function estaActivoEn(string $dia): bool
    {
        return $this->activo && $this->dia_semana === $dia;
    }

    /**
     * Verifica si el horario es válido (hora_fin > hora_inicio)
     * @return bool
     */
    public function esValido(): bool
    {
        $inicio = Carbon::parse($this->hora_inicio);
        $fin = Carbon::parse($this->hora_fin);
        return $fin->greaterThan($inicio);
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Genera los slots de tiempo disponibles para este horario
     * @param int $duracionConsulta Duración en minutos (default: 30)
     * @return array Array de strings con formato 'HH:MM'
     */
    public function generarSlots(int $duracionConsulta = 30): array
    {
        if (!$this->activo || !$this->esValido()) {
            return [];
        }

        $slots = [];
        $horaActual = Carbon::parse($this->hora_inicio);
        $horaFin = Carbon::parse($this->hora_fin);

        while ($horaActual->lessThan($horaFin)) {
            $slots[] = $horaActual->format('H:i');
            $horaActual->addMinutes($duracionConsulta);
        }

        return $slots;
    }

    /**
     * Genera slots con información de disponibilidad
     * @param string $fecha Fecha en formato Y-m-d
     * @param int $duracionConsulta Duración en minutos
     * @return array
     */
    public function generarSlotsConDisponibilidad(string $fecha, int $duracionConsulta = 30): array
    {
        $slots = $this->generarSlots($duracionConsulta);
        $slotsConDisponibilidad = [];

        foreach ($slots as $hora) {
            // Verificar si hay cita en ese horario
            $citaExistente = Cita::where('medico_id', $this->medico_id)
                ->where('fecha', $fecha)
                ->where('hora', $hora)
                ->whereNotIn('estado', ['Cancelada'])
                ->exists();

            $slotsConDisponibilidad[] = [
                'hora' => $hora,
                'disponible' => !$citaExistente,
            ];
        }

        return $slotsConDisponibilidad;
    }

    /**
     * Calcula la duración total del horario en minutos
     * @return int
     */
    public function duracionTotal(): int
    {
        $inicio = Carbon::parse($this->hora_inicio);
        $fin = Carbon::parse($this->hora_fin);
        return $inicio->diffInMinutes($fin);
    }

    /**
     * Formatea el horario como string legible
     * @return string Ejemplo: "08:00 - 12:00"
     */
    public function horarioFormateado(): string
    {
        $inicio = Carbon::parse($this->hora_inicio)->format('H:i');
        $fin = Carbon::parse($this->hora_fin)->format('H:i');
        return "{$inicio} - {$fin}";
    }

    /**
     * Obtiene el día de la semana en formato corto
     * @return string Ejemplo: "Lun"
     */
    public function diaCorto(): string
    {
        $dias = [
            'Lunes' => 'Lun',
            'Martes' => 'Mar',
            'Miércoles' => 'Mié',
            'Jueves' => 'Jue',
            'Viernes' => 'Vie',
            'Sábado' => 'Sáb',
            'Domingo' => 'Dom',
        ];

        return $dias[$this->dia_semana] ?? $this->dia_semana;
    }

    /**
     * Verifica si un horario específico está dentro de este rango
     * @param string $hora Hora en formato HH:MM
     * @return bool
     */
    public function incluyeHora(string $hora): bool
    {
        $horaCheck = Carbon::parse($hora);
        $inicio = Carbon::parse($this->hora_inicio);
        $fin = Carbon::parse($this->hora_fin);

        return $horaCheck->greaterThanOrEqualTo($inicio) && 
               $horaCheck->lessThan($fin);
    }
}