<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Modelo Paciente
 * Ubicación: app/Models/Paciente.php
 * 
 * Gestiona la información médica y personal de los pacientes
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos actualizados según schema 3FN (agregado: apellido, email, teléfono_emergencia)
 * - Cálculo automático de edad desde fecha_nacimiento
 * - Validaciones mejoradas
 * - Métodos auxiliares expandidos
 */
class Paciente extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'pacientes';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'user_id',
        'ci',
        'nombre',
        'apellido',
        'edad',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'telefono',
        'email',
        'antecedentes',
        'alergias',
        'contacto_emergencia',
        'telefono_emergencia',
        'grupo_sanguineo',
        'estado_civil',
        'ocupacion',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'edad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con User
     * Un paciente pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con Citas
     * Un paciente puede tener muchas citas
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Relación con Historial Médico
     * Un paciente tiene un historial médico
     */
    public function historialMedico(): HasMany
    {
        return $this->hasMany(HistorialMedico::class, 'paciente_id');
    }

    /**
     * Relación con Recetas
     * Un paciente puede tener muchas recetas
     */
    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class, 'paciente_id');
    }

    // ============================================
    // SCOPES (Consultas reutilizables)
    // ============================================

    /**
     * Scope para buscar pacientes por CI o nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('ci', 'like', "%{$termino}%")
                     ->orWhere('nombre', 'like', "%{$termino}%")
                     ->orWhere('apellido', 'like', "%{$termino}%");
    }

    /**
     * Scope para pacientes por género
     */
    public function scopePorGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }

    // ============================================
    // ACCESSORS (Atributos calculados)
    // ============================================

    /**
     * Obtiene el nombre completo del paciente
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Calcula la edad actual desde la fecha de nacimiento
     * Este accessor se ejecuta automáticamente cuando se accede a $paciente->edad_actual
     */
    public function getEdadActualAttribute(): int
    {
        if (!$this->fecha_nacimiento) {
            return $this->edad ?? 0;
        }
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtiene las citas próximas del paciente
     */
    public function citasProximas()
    {
        return $this->citas()
            ->with('medico.especialidad')
            ->where('fecha', '>=', today())
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    /**
     * Obtiene el historial de citas atendidas
     */
    public function citasAtendidas()
    {
        return $this->citas()
            ->with('medico.especialidad')
            ->where('estado', 'Atendida')
            ->orderBy('fecha', 'desc');
    }

    /**
     * Obtiene la última cita atendida
     */
    public function ultimaCitaAtendida()
    {
        return $this->citasAtendidas()->first();
    }

    /**
     * Verifica si el paciente tiene citas pendientes
     * @return bool
     */
    public function tieneCitasPendientes(): bool
    {
        return $this->citasProximas()->exists();
    }

    /**
     * Cuenta el total de citas del paciente
     * @return int
     */
    public function totalCitas(): int
    {
        return $this->citas()->count();
    }

    /**
     * Verifica si tiene alergias registradas
     * @return bool
     */
    public function tieneAlergias(): bool
    {
        return !empty($this->alergias);
    }

    /**
     * Verifica si tiene antecedentes médicos
     * @return bool
     */
    public function tieneAntecedentes(): bool
    {
        return !empty($this->antecedentes);
    }

    /**
     * Obtiene las recetas del último mes
     */
    public function recetasRecientes()
    {
        return $this->recetas()
            ->with('medico', 'medicamentos')
            ->where('fecha_emision', '>=', now()->subMonth())
            ->orderBy('fecha_emision', 'desc');
    }

    /**
     * Obtiene el historial médico reciente (últimos 6 meses)
     */
    public function historialReciente()
    {
        return $this->historialMedico()
            ->with('medico.especialidad', 'cita')
            ->where('fecha', '>=', now()->subMonths(6))
            ->orderBy('fecha', 'desc');
    }
}