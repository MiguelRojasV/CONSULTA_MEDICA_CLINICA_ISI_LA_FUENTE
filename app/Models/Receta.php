<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Receta
 * Ubicación: app/Models/Receta.php
 * 
 * Gestiona las recetas médicas emitidas a los pacientes
 * Permite descarga en PDF
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campo agregado: observaciones, valida_hasta
 * - Tabla pivot actualizada: receta_medicamento con dosis, frecuencia, duracion
 * - Método de dispensación automática con reducción de stock
 * - Validaciones mejoradas
 */
class Receta extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'recetas';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'medico_id',
        'fecha_emision',
        'indicaciones',
        'observaciones',
        'estado',
        'valida_hasta',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'fecha_emision' => 'date',
        'valida_hasta' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con Cita
     * Una receta pertenece a una cita
     */
    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    /**
     * Relación con Paciente
     * Una receta pertenece a un paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relación con Médico
     * Una receta es emitida por un médico
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    /**
     * Relación muchos a muchos con Medicamentos
     * Una receta puede tener varios medicamentos
     * Tabla pivot: receta_medicamento
     * Campos pivot: cantidad, dosis, frecuencia, duracion, instrucciones_especiales
     */
    public function medicamentos(): BelongsToMany
    {
        return $this->belongsToMany(
            Medicamento::class, 
            'receta_medicamento',
            'receta_id',
            'medicamento_id'
        )
        ->withPivot([
            'cantidad', 
            'dosis', 
            'frecuencia', 
            'duracion',
            'instrucciones_especiales'
        ])
        ->withTimestamps();
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Recetas por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Recetas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    /**
     * Recetas dispensadas
     */
    public function scopeDispensadas($query)
    {
        return $query->where('estado', 'Dispensada');
    }

    /**
     * Recetas por paciente
     */
    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Recetas por médico
     */
    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    /**
     * Recetas del mes actual
     */
    public function scopeDelMesActual($query)
    {
        return $query->whereMonth('fecha_emision', now()->month)
                     ->whereYear('fecha_emision', now()->year);
    }

    // ============================================
    // MÉTODOS DE ACCIÓN
    // ============================================

    /**
     * Marca la receta como dispensada y reduce el stock de medicamentos
     */
    public function marcarComoDispensada(): void
    {
        // Cambiar estado
        $this->estado = 'Dispensada';
        $this->save();

        // Reducir stock de cada medicamento
        foreach ($this->medicamentos as $medicamento) {
            $cantidad = $medicamento->pivot->cantidad;
            
            // Reducir disponibilidad
            if ($medicamento->disponibilidad >= $cantidad) {
                $medicamento->disponibilidad -= $cantidad;
                $medicamento->save();
            }
        }
    }

    /**
     * Cancela la receta
     */
    public function cancelar(): void
    {
        $this->estado = 'Cancelada';
        $this->save();
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Verifica si la receta está vigente
     * @return bool
     */
    public function estaVigente(): bool
    {
        if (!$this->valida_hasta) {
            return true; // Si no tiene fecha de vencimiento, es válida
        }

        return $this->valida_hasta->isFuture();
    }

    /**
     * Verifica si la receta está vencida
     * @return bool
     */
    public function estaVencida(): bool
    {
        if (!$this->valida_hasta) {
            return false;
        }

        return $this->valida_hasta->isPast();
    }

    /**
     * Verifica si la receta fue dispensada
     * @return bool
     */
    public function fueDispensada(): bool
    {
        return $this->estado === 'Dispensada';
    }

    /**
     * Verifica si la receta está pendiente
     * @return bool
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'Pendiente';
    }

    /**
     * Cuenta el total de medicamentos en la receta
     * @return int
     */
    public function totalMedicamentos(): int
    {
        return $this->medicamentos()->count();
    }

    /**
     * Calcula el costo total de la receta
     * @return float
     */
    public function costoTotal(): float
    {
        $total = 0;
        
        foreach ($this->medicamentos as $medicamento) {
            $cantidad = $medicamento->pivot->cantidad;
            $precioUnitario = $medicamento->precio_unitario ?? 0;
            $total += $cantidad * $precioUnitario;
        }

        return round($total, 2);
    }

    /**
     * Formatea la fecha de emisión
     * @return string
     */
    public function fechaEmisionFormateada(): string
    {
        return $this->fecha_emision->format('d/m/Y');
    }

    /**
     * Obtiene el nombre completo del médico
     * @return string
     */
    public function nombreMedico(): string
    {
        return $this->medico ? $this->medico->nombre_completo : 'No asignado';
    }

    /**
     * Obtiene el nombre completo del paciente
     * @return string
     */
    public function nombrePaciente(): string
    {
        return $this->paciente ? $this->paciente->nombre_completo : 'No asignado';
    }
}