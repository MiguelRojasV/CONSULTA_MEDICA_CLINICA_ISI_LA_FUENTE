<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Medicamento
 * Ubicación: app/Models/Medicamento.php
 * 
 * Gestiona el inventario de medicamentos de la clínica
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos agregados: concentracion, via_administracion, stock_minimo, 
 *   requiere_receta, contraindicaciones
 * - Sistema de alertas de stock bajo
 * - Validación de caducidad
 * - Métodos de control de inventario
 */
class Medicamento extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'medicamentos';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre_generico',
        'nombre_comercial',
        'tipo',
        'presentacion',
        'dosis',
        'concentracion',
        'via_administracion',
        'disponibilidad',
        'stock_minimo',
        'precio_unitario',
        'caducidad',
        'lote',
        'laboratorio',
        'requiere_receta',
        'contraindicaciones',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'caducidad' => 'date',
        'disponibilidad' => 'integer',
        'stock_minimo' => 'integer',
        'precio_unitario' => 'decimal:2',
        'requiere_receta' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con Recetas (muchos a muchos)
     * Un medicamento puede estar en muchas recetas
     */
    public function recetas(): BelongsToMany
    {
        return $this->belongsToMany(
            Receta::class, 
            'receta_medicamento',
            'medicamento_id',
            'receta_id'
        )
        ->withPivot([
            'cantidad', 
            'dosis', 
            'frecuencia', 
            'duracion',
            'instrucciones_especiales'
        ])
        ->withTimestamps();
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Medicamentos disponibles (con stock)
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponibilidad', '>', 0);
    }

    /**
     * Medicamentos sin stock
     */
    public function scopeSinStock($query)
    {
        return $query->where('disponibilidad', '=', 0);
    }

    /**
     * Medicamentos con stock bajo
     */
    public function scopeStockBajo($query)
    {
        return $query->whereColumn('disponibilidad', '<', 'stock_minimo')
                     ->where('disponibilidad', '>', 0);
    }

    /**
     * Medicamentos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('caducidad', '<', now());
    }

    /**
     * Medicamentos por vencer (30 días)
     */
    public function scopePorVencer($query)
    {
        return $query->where('caducidad', '>', now())
                     ->where('caducidad', '<=', now()->addDays(30));
    }

    /**
     * Medicamentos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Búsqueda por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre_generico', 'like', "%{$termino}%")
                     ->orWhere('nombre_comercial', 'like', "%{$termino}%");
    }

    // ============================================
    // MÉTODOS DE VALIDACIÓN
    // ============================================

    /**
     * Verifica si el medicamento está vencido
     * @return bool
     */
    public function estaVencido(): bool
    {
        return $this->caducidad && $this->caducidad->isPast();
    }

    /**
     * Verifica si el medicamento está por vencer (30 días)
     * @return bool
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
     * @return bool
     */
    public function tieneStock(): bool
    {
        return $this->disponibilidad > 0;
    }

    /**
     * Verifica si el stock es bajo (menor al mínimo)
     * @return bool
     */
    public function stockBajo(): bool
    {
        return $this->disponibilidad < $this->stock_minimo && $this->disponibilidad > 0;
    }

    /**
     * Verifica si el stock es crítico (menos de 5 unidades)
     * @return bool
     */
    public function stockCritico(): bool
    {
        return $this->disponibilidad > 0 && $this->disponibilidad < 5;
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtiene el nivel de stock como texto
     * @return string
     */
    public function nivelStock(): string
    {
        if ($this->disponibilidad == 0) {
            return 'Sin Stock';
        } elseif ($this->disponibilidad < 5) {
            return 'Stock Crítico';
        } elseif ($this->disponibilidad < $this->stock_minimo) {
            return 'Stock Bajo';
        } elseif ($this->disponibilidad < ($this->stock_minimo * 2)) {
            return 'Stock Medio';
        }
        return 'Stock Suficiente';
    }

    /**
     * Obtiene el color de alerta según el stock
     * @return string (clase CSS de Tailwind)
     */
    public function colorStock(): string
    {
        if ($this->disponibilidad == 0) {
            return 'red'; // Sin stock
        } elseif ($this->stockCritico()) {
            return 'orange'; // Crítico
        } elseif ($this->stockBajo()) {
            return 'yellow'; // Bajo
        }
        return 'green'; // Suficiente
    }

    /**
     * Obtiene el estado de caducidad como texto
     * @return string
     */
    public function estadoCaducidad(): string
    {
        if (!$this->caducidad) {
            return 'No especificada';
        }

        if ($this->estaVencido()) {
            return 'Vencido';
        }

        if ($this->estaPorVencer()) {
            $dias = $this->caducidad->diffInDays(now());
            return "Por vencer ({$dias} días)";
        }

        return 'Vigente';
    }

    /**
     * Calcula el valor total del inventario de este medicamento
     * @return float
     */
    public function valorInventario(): float
    {
        return round($this->disponibilidad * $this->precio_unitario, 2);
    }

    /**
     * Obtiene el nombre completo (genérico + comercial)
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->nombre_comercial) {
            return "{$this->nombre_generico} ({$this->nombre_comercial})";
        }
        return $this->nombre_generico;
    }

    /**
     * Reduce el stock del medicamento
     * @param int $cantidad
     * @return bool
     */
    public function reducirStock(int $cantidad): bool
    {
        if ($this->disponibilidad >= $cantidad) {
            $this->disponibilidad -= $cantidad;
            return $this->save();
        }
        return false;
    }

    /**
     * Aumenta el stock del medicamento
     * @param int $cantidad
     * @return bool
     */
    public function aumentarStock(int $cantidad): bool
    {
        $this->disponibilidad += $cantidad;
        return $this->save();
    }
}