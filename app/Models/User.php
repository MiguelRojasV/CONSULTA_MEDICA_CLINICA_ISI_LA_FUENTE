<?php
#Models/user
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    public function paciente(): HasOne
    {
        return $this->hasOne(Paciente::class, 'user_id');
    }

    public function medico(): HasOne
    {
        return $this->hasOne(Medico::class, 'user_id');
    }

    public function personalAdministrativo(): HasOne
    {
        return $this->hasOne(PersonalAdministrativo::class, 'user_id');
    }

    // ============================================
    // MÉTODOS DE VERIFICACIÓN DE ROL
    // ============================================

    public function esAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function esMedico(): bool
    {
        return $this->role === 'medico';
    }

    public function esPaciente(): bool
    {
        return $this->role === 'paciente';
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    public function perfil()
    {
        return match($this->role) {
            'paciente' => $this->paciente()->first(),
            'medico' => $this->medico()->first(),
            'administrador' => $this->personalAdministrativo()->first(),
            default => null,
        };
    }

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
     * CORRECCIÓN: Verifica perfil completo correctamente
     */
    public function tienePerfilCompleto(): bool
    {
        return match ($this->role) {
            'paciente' => $this->paciente()->exists(),
            'medico' => $this->medico()->exists(),
            'administrador' => $this->personalAdministrativo()->exists(),
            default => false,
        };
    }

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
