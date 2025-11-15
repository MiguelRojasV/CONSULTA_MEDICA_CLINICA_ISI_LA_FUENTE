<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receta extends Model
{
    protected $table = 'recetas';
    
    protected $fillable = [
        'cita_id',
        'fecha_emision',
        'indicaciones',
        'dosis'
    ];

    protected $casts = [
        'fecha_emision' => 'date'
    ];

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function medicamentos(): BelongsToMany
    {
        return $this->belongsToMany(Medicamento::class, 'receta_medicamento')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}