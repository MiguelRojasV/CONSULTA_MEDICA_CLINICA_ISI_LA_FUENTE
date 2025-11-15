<?php

// ============================================
// app/Models/Paciente.php
// ============================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'pacientes';
    
    protected $fillable = [
        'ci',
        'nombre',
        'edad',
        'antecedentes',
        'alergias',
        'contacto_emergencia'
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }
}