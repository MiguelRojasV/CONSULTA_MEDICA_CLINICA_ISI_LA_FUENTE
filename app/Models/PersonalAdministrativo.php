<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo PersonalAdministrativo
 * Ubicación: app/Models/PersonalAdministrativo.php
 * 
 * Gestiona la información del personal administrativo
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos agregados: apellido, email, fecha_contratacion, estado
 * - Validaciones mejoradas
 * - Métodos auxiliares
 */
class PersonalAdministrativo extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'personal_administrativo';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'user_id',
        'ci',
        'nombre',
        'apellido',
        'cargo',
        'edad',
        'telefono',
        'email',
        'fecha_contratacion',
        'estado',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'edad' => 'integer',
        'fecha_contratacion' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con User
     * Un personal administrativo pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Personal activo
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Personal por cargo
     */
    public function scopePorCargo($query, $cargo)
    {
        return $query->where('cargo', $cargo);
    }

    /**
     * Búsqueda por nombre o CI
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                     ->orWhere('apellido', 'like', "%{$termino}%")
                     ->orWhere('ci', 'like', "%{$termino}%");
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtiene el nombre completo
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Verifica si el personal está activo
     * @return bool
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'Activo';
    }

    /**
     * Calcula los años de antigüedad
     * @return int
     */
    public function aniosAntiguedad(): int
    {
        if (!$this->fecha_contratacion) {
            return 0;
        }

        return $this->fecha_contratacion->diffInYears(now());
    }

    /**
     * Formatea la fecha de contratación
     * @return string|null
     */
    public function fechaContratacionFormateada(): ?string
    {
        return $this->fecha_contratacion ? 
               $this->fecha_contratacion->format('d/m/Y') : 
               null;
    }
}