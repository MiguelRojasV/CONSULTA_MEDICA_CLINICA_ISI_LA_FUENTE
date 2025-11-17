<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Especialidad
 * Ubicación: app/Models/Especialidad.php
 */
class Especialidad extends Model
{
    protected $table = 'especialidades';

    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function medicos(): HasMany
    {
        return $this->hasMany(Medico::class, 'especialidad_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function contarMedicos(): int
    {
        return $this->medicos()->count();
    }

    public function contarMedicosActivos(): int
    {
        return $this->medicos()->where('estado', 'Activo')->count();
    }
}