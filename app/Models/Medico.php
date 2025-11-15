<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medico extends Model
{
    protected $table = 'medicos';
    
    protected $fillable = [
        'ci',
        'nombre',
        'especialidad',
        'turno',
        'formacion_continua'
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }
}