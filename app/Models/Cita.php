<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo Cita
 * Ubicación: app/Models/Cita.php
 * 
 * Gestiona las citas médicas entre pacientes y médicos
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos agregados: tipo_cita, duracion_estimada, costo
 * - Validación mejorada de conflictos de horario
 * - Creación automática de historial al marcar como atendida
 */
class Cita extends Model
{
    /**
     * Tabla asociada
     */
    protected $table = 'citas';
    
    /**
     * Campos asignables
     */
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha',
        'hora',
        'motivo',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'estado',
        'tipo_cita',
        'duracion_estimada',
        'costo',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime',
        'costo' => 'decimal:2',
        'duracion_estimada' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Una cita pertenece a un paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Una cita pertenece a un médico
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    /**
     * Una cita puede tener muchas recetas
     */
    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class, 'cita_id');
    }

    /**
     * Una cita puede tener una entrada en historial médico
     */
    public function historialMedico(): HasOne
    {
        return $this->hasOne(HistorialMedico::class, 'cita_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Citas por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Citas de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    /**
     * Citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha', '>=', today());
    }

    /**
     * Citas pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('fecha', '<', today());
    }

    // ============================================
    // MÉTODOS DE VALIDACIÓN
    // ============================================

    /**
     * Valida que no haya conflictos de horario
     * @return bool
     */
    public function validarCita(): bool
    {
        // No permitir citas en fechas pasadas (solo al crear)
        if (!$this->exists && $this->fecha < today()) {
            return false;
        }
        
        // Verificar conflictos de horario con el médico
        $citasConflicto = self::where('medico_id', $this->medico_id)
            ->where('fecha', $this->fecha)
            ->where('hora', $this->hora)
            ->where('id', '!=', $this->id ?? 0)
            ->whereNotIn('estado', ['Cancelada'])
            ->count();
            
        return $citasConflicto === 0;
    }

    // ============================================
    // MÉTODOS DE ACCIÓN
    // ============================================

    /**
     * Marca la cita como atendida y crea historial automáticamente
     */
    public function marcarComoAtendida(): void
    {
        // Cambiar estado
        $this->estado = 'Atendida';
        $this->save();

        // Crear entrada en historial médico si tiene diagnóstico
        if ($this->diagnostico && !$this->historialMedico) {
            HistorialMedico::create([
                'paciente_id' => $this->paciente_id,
                'cita_id' => $this->id,
                'medico_id' => $this->medico_id,
                'fecha' => $this->fecha,
                'tipo_atencion' => $this->tipo_cita ?? 'Consulta',
                'diagnostico' => $this->diagnostico,
                'tratamiento' => $this->tratamiento,
                'observaciones' => $this->observaciones,
            ]);
        }
    }

    /**
     * Cancela la cita
     */
    public function cancelar(): void
    {
        $this->estado = 'Cancelada';
        $this->save();
    }

    /**
     * Confirma la cita
     */
    public function confirmar(): void
    {
        $this->estado = 'Confirmada';
        $this->save();
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Verifica si la cita está activa (no cancelada)
     * @return bool
     */
    public function estaActiva(): bool
    {
        return $this->estado !== 'Cancelada';
    }

    /**
     * Verifica si la cita ya fue atendida
     * @return bool
     */
    public function fueAtendida(): bool
    {
        return $this->estado === 'Atendida';
    }

    /**
     * Verifica si la cita es hoy
     * @return bool
     */
    public function esHoy(): bool
    {
        return $this->fecha->isToday();
    }

    /**
     * Verifica si la cita ya pasó
     * @return bool
     */
    public function yaPaso(): bool
    {
        return $this->fecha->isPast();
    }

    /**
     * Obtiene la hora formateada
     * @return string
     */
    public function horaFormateada(): string
    {
        return $this->hora->format('H:i');
    }
}