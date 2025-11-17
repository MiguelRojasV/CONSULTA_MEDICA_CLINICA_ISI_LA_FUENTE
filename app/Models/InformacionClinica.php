<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo InformacionClinica
 * Ubicación: app/Models/InformacionClinica.php
 * 
 * Almacena la información que se muestra en la página de inicio
 * Solo debe existir un registro (Singleton)
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Campos agregados: slogan, valores, telefono_emergencias, twitter, mapa_ubicacion, logo
 * - Métodos mejorados para manejo de servicios
 * - Validaciones de imágenes
 */
class InformacionClinica extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'informacion_clinica';
    
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre',
        'slogan',
        'descripcion',
        'mision',
        'vision',
        'valores',
        'direccion',
        'telefono',
        'telefono_emergencias',
        'email',
        'horario_atencion',
        'servicios',
        'imagen_principal',
        'logo',
        'facebook',
        'instagram',
        'twitter',
        'whatsapp',
        'mapa_ubicacion',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================
    // MÉTODOS ESTÁTICOS
    // ============================================

    /**
     * Obtiene la información de la clínica (singleton)
     * @return InformacionClinica|null
     */
    public static function obtenerInfo()
    {
        return self::first();
    }

    /**
     * Crea o actualiza la información de la clínica
     * @param array $datos
     * @return InformacionClinica
     */
    public static function actualizarInfo(array $datos): InformacionClinica
    {
        $info = self::first();
        
        if ($info) {
            $info->update($datos);
            return $info;
        }

        return self::create($datos);
    }

    // ============================================
    // MÉTODOS DE SERVICIOS
    // ============================================

    /**
     * Obtiene la lista de servicios como array
     * @return array
     */
    public function listaServicios(): array
    {
        if (!$this->servicios) {
            return [];
        }
        
        // Los servicios están separados por comas en la BD
        return array_map('trim', explode(',', $this->servicios));
    }

    /**
     * Agrega un nuevo servicio
     * @param string $servicio
     * @return bool
     */
    public function agregarServicio(string $servicio): bool
    {
        $servicios = $this->listaServicios();
        
        if (!in_array($servicio, $servicios)) {
            $servicios[] = $servicio;
            $this->servicios = implode(', ', $servicios);
            return $this->save();
        }

        return false;
    }

    /**
     * Elimina un servicio
     * @param string $servicio
     * @return bool
     */
    public function eliminarServicio(string $servicio): bool
    {
        $servicios = $this->listaServicios();
        $key = array_search($servicio, $servicios);
        
        if ($key !== false) {
            unset($servicios[$key]);
            $this->servicios = implode(', ', $servicios);
            return $this->save();
        }

        return false;
    }

    // ============================================
    // MÉTODOS DE IMÁGENES
    // ============================================

    /**
     * Verifica si tiene imagen principal
     * @return bool
     */
    public function tieneImagen(): bool
    {
        return !empty($this->imagen_principal);
    }

    /**
     * Verifica si tiene logo
     * @return bool
     */
    public function tieneLogo(): bool
    {
        return !empty($this->logo);
    }

    /**
     * Obtiene la URL completa de la imagen principal
     * @return string|null
     */
    public function urlImagen(): ?string
    {
        if (!$this->tieneImagen()) {
            return null;
        }
        
        return asset('storage/' . $this->imagen_principal);
    }

    /**
     * Obtiene la URL completa del logo
     * @return string|null
     */
    public function urlLogo(): ?string
    {
        if (!$this->tieneLogo()) {
            return null;
        }
        
        return asset('storage/' . $this->logo);
    }

    // ============================================
    // MÉTODOS DE REDES SOCIALES
    // ============================================

    /**
     * Verifica si tiene redes sociales configuradas
     * @return bool
     */
    public function tieneRedesSociales(): bool
    {
        return !empty($this->facebook) || 
               !empty($this->instagram) || 
               !empty($this->twitter) || 
               !empty($this->whatsapp);
    }

    /**
     * Obtiene las redes sociales configuradas
     * @return array
     */
    public function redesSociales(): array
    {
        $redes = [];

        if ($this->facebook) {
            $redes['facebook'] = [
                'url' => $this->facebook,
                'icon' => 'fab fa-facebook',
                'color' => 'blue',
            ];
        }

        if ($this->instagram) {
            $redes['instagram'] = [
                'url' => $this->instagram,
                'icon' => 'fab fa-instagram',
                'color' => 'pink',
            ];
        }

        if ($this->twitter) {
            $redes['twitter'] = [
                'url' => $this->twitter,
                'icon' => 'fab fa-twitter',
                'color' => 'sky',
            ];
        }

        if ($this->whatsapp) {
            $redes['whatsapp'] = [
                'url' => "https://wa.me/{$this->whatsapp}",
                'icon' => 'fab fa-whatsapp',
                'color' => 'green',
            ];
        }

        return $redes;
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtiene el horario de atención formateado
     * @return string
     */
    public function horarioFormateado(): string
    {
        return $this->horario_atencion ?? 'No especificado';
    }

    /**
     * Verifica si tiene ubicación en mapa
     * @return bool
     */
    public function tieneMapa(): bool
    {
        return !empty($this->mapa_ubicacion);
    }

    /**
     * Obtiene el iframe del mapa
     * @return string|null
     */
    public function iframeMapa(): ?string
    {
        if (!$this->tieneMapa()) {
            return null;
        }

        return $this->mapa_ubicacion;
    }
}