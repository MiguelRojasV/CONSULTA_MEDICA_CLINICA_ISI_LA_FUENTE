<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cita extends Model
{
    protected $table = 'citas';
    
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha',
        'hora',
        'motivo',
        'diagnostico',
        'tratamiento',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i'
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class);
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class);
    }

    public function validarCita(): bool
    {
        if ($this->fecha < now()->toDateString()) {
            return false;
        }
        
        $citasConflicto = self::where('medico_id', $this->medico_id)
            ->where('fecha', $this->fecha)
            ->where('hora', $this->hora)
            ->where('id', '!=', $this->id)
            ->where('estado', '!=', 'Cancelada')
            ->count();
            
        return $citasConflicto === 0;
    }
}