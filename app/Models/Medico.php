<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Modelo Medico
 * Representa a un médico en el sistema
 * Vinculado a un usuario y tiene una especialidad
 */
class Medico extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'medicos';

    /**
     * Campos asignables en masa
     */
    /**
     * Campos asignables masivamente
     * ACTUALIZADO: Incluye apellido, email, años_experiencia, fecha_contratacion, estado
     */
    protected $fillable = [
        'user_id',
        'ci',
        'nombre',
        'apellido',
        'especialidad_id',
        'matricula',
        'registro_profesional',
        'años_experiencia',
        'turno',
        'consultorio',
        'telefono',
        'email',
        'formacion_continua',
        'fecha_contratacion',
        'estado',
    ];

    /**
     * Campos que deben ser tratados como fechas
     */
    protected $casts = [
        'fecha_contratacion' => 'date',
        'años_experiencia' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con el usuario (autenticación)
     * Un médico pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la especialidad
     * Un médico pertenece a una especialidad
     */
    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    /**
     * Relación con citas
     * Un médico tiene muchas citas
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    /**
     * Relación con recetas
     * Un médico emite muchas recetas
     */
    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class, 'medico_id');
    }

    /**
     * Relación con historial médico
     * Un médico registra muchos historiales
     */
    public function historiales(): HasMany
    {
        return $this->hasMany(HistorialMedico::class, 'medico_id');
    }

    /**
     * Relación con horarios de atención
     * Un médico tiene muchos horarios
     */
    public function horarios(): HasMany
    {
        return $this->hasMany(HorarioAtencion::class, 'medico_id');
    }

    // ============================================
    // SCOPES (Consultas reutilizables)
    // ============================================

    /**
     * Scope para médicos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Scope para médicos por especialidad
     */
    public function scopePorEspecialidad($query, $especialidadId)
    {
        return $query->where('especialidad_id', $especialidadId);
    }

    /**
     * Scope para médicos por turno
     */
    public function scopePorTurno($query, $turno)
    {
        return $query->where('turno', $turno);
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtener citas de hoy
     */
    public function citasHoy()
    {
        return $this->citas()
            ->whereDate('fecha', today())
            ->orderBy('hora');
    }

    /**
     * Obtener citas pendientes
     */
    public function citasPendientes()
    {
        return $this->citas()
            ->whereIn('estado', ['Programada', 'Confirmada'])
            ->where('fecha', '>=', today())
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    /**
     * Obtener nombre completo del médico
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Obtener nombre con título
     */
    public function getNombreConTituloAttribute(): string
    {
        return "Dr(a). {$this->nombre} {$this->apellido}";
    }

    /**
     * Verificar si tiene citas hoy
     */
    public function tieneCitasHoy(): bool
    {
        return $this->citasHoy()->exists();
    }

    /**
     * Contar pacientes atendidos
     */
    public function contarPacientesAtendidos(): int
    {
        return $this->citas()
            ->where('estado', 'Atendida')
            ->distinct('paciente_id')
            ->count('paciente_id');
    }

    /**
     * Verificar disponibilidad en fecha y hora
     */
    public function estaDisponible(string $fecha, string $hora): bool
    {
        return !$this->citas()
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->whereNotIn('estado', ['Cancelada'])
            ->exists();
    }

    /**
     * Obtener horarios de hoy
     */
    public function horariosHoy()
    {
        $diaSemana = Carbon::now()->locale('es')->dayName;
        $diasMap = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];
        
        $diaEspanol = $diasMap[Carbon::now()->englishDayOfWeek] ?? 'Lunes';
        
        return $this->horarios()
            ->where('dia_semana', $diaEspanol)
            ->where('activo', true)
            ->get();
    }
}