<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicamento extends Model
{
    protected $table = 'medicamentos';
    
    protected $fillable = [
        'nombre_generico',
        'tipo',
        'dosis',
        'disponibilidad',
        'caducidad'
    ];

    protected $casts = [
        'caducidad' => 'date',
        'disponibilidad' => 'integer'
    ];

    public function recetas(): BelongsToMany
    {
        return $this->belongsToMany(Receta::class, 'receta_medicamento')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    /**
     * Verifica si el medicamento está vencido
     */
    public function estaVencido(): bool
    {
        return $this->caducidad && $this->caducidad->isPast();
    }

    /**
     * Verifica si el medicamento está por vencer (30 días)
     */
    public function estaPorVencer(): bool
    {
        if (!$this->caducidad) {
            return false;
        }
        
        return $this->caducidad->isFuture() && 
               $this->caducidad->diffInDays(now()) <= 30;
    }

    /**
     * Verifica si hay stock disponible
     */
    public function tieneStock(): bool
    {
        return $this->disponibilidad > 0;
    }

    /**
     * Verifica si el stock es bajo (menos de 20 unidades)
     */
    public function stockBajo(): bool
    {
        return $this->disponibilidad < 20 && $this->disponibilidad > 0;
    }
}