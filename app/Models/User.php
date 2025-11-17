<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo User - Gestiona la autenticación y roles del sistema
 * Ubicación: app/Models/User.php
 * 
 * ROLES DISPONIBLES:
 * - paciente: Puede agendar citas, ver su historial, descargar recetas
 * - medico: Puede ver pacientes, emitir recetas, gestionar consultas
 * - administrador: Acceso total al sistema
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Compatible con nueva estructura de BD normalizada 3FN
 * - Relaciones actualizadas con especialidades
 * - Métodos auxiliares mejorados
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'users';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Campos ocultos en las respuestas JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con Paciente
     * Un usuario puede ser un paciente (1:1)
     */
    public function paciente(): HasOne
    {
        return $this->hasOne(Paciente::class, 'user_id');
    }

    /**
     * Relación con Médico
     * Un usuario puede ser un médico (1:1)
     */
    public function medico(): HasOne
    {
        return $this->hasOne(Medico::class, 'user_id');
    }

    /**
     * Relación con Personal Administrativo
     * Un usuario puede ser administrador (1:1)
     */
    public function personalAdministrativo(): HasOne
    {
        return $this->hasOne(PersonalAdministrativo::class, 'user_id');
    }

    // ============================================
    // MÉTODOS DE VERIFICACIÓN DE ROL
    // ============================================

    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public function esAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    /**
     * Verifica si el usuario es médico
     * @return bool
     */
    public function esMedico(): bool
    {
        return $this->role === 'medico';
    }

    /**
     * Verifica si el usuario es paciente
     * @return bool
     */
    public function esPaciente(): bool
    {
        return $this->role === 'paciente';
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtiene el perfil completo según el rol
     * Retorna el modelo relacionado (Paciente, Medico o PersonalAdministrativo)
     * @return Paciente|Medico|PersonalAdministrativo|null
     */
    public function perfil()
    {
        return match($this->role) {
            'paciente' => $this->paciente,
            'medico' => $this->medico,
            'administrador' => $this->personalAdministrativo,
            default => null,
        };
    }

    /**
     * Obtiene el nombre formateado según el rol
     * @return string
     */
    public function nombreFormateado(): string
    {
        $perfil = $this->perfil();
        
        if (!$perfil) {
            return $this->name;
        }

        if ($this->esMedico()) {
            return "Dr(a). {$perfil->nombre} {$perfil->apellido}";
        }

        return "{$perfil->nombre} {$perfil->apellido}";
    }

    /**
     * Verifica si el usuario tiene un perfil completo
     * @return bool
     */
    public function tienePerfilCompleto(): bool
    {
        return $this->perfil() !== null;
    }

    /**
     * Obtiene la ruta del dashboard según el rol
     * @return string
     */
    public function rutaDashboard(): string
    {
        return match($this->role) {
            'paciente' => route('paciente.dashboard'),
            'medico' => route('medico.dashboard'),
            'administrador' => route('admin.dashboard'),
            default => route('home'),
        };
    }
}