<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\InformacionClinica;
use App\Models\Medico;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio
     */
    public function index(): View
    {
        // Obtener información de la clínica
        $clinica = InformacionClinica::first();

        // Si no existe, crear información por defecto
        if (!$clinica) {
            $clinica = $this->crearInformacionPorDefecto();
        }

        // Obtener médicos activos con su especialidad
        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->take(6)
            ->get();

        // Convertir servicios a array
        $servicios = $this->obtenerServicios($clinica);

        return view('home', compact('clinica', 'medicos', 'servicios'));
    }

    /**
     * Crear información por defecto
     */
    private function crearInformacionPorDefecto(): InformacionClinica
    {
        return InformacionClinica::create([
            'nombre' => 'Clínica ISI La Fuente',
            'slogan' => 'Tu salud, nuestra prioridad',
            'descripcion' => 'Clínica especializada en atención médica integral con enfoque en medicina preventiva y atención de calidad.',
            'mision' => 'Brindar atención médica de excelencia, con calidez humana, a través de un equipo de salud comprometido con la capacitación continua y la innovación tecnológica.',
            'vision' => 'Ser referentes a nivel nacional e internacional, brindando atención de calidad, con seguridad y plena satisfacción del paciente.',
            'direccion' => 'Calle Beni entre 6 de octubre y Potosí, Nro. 60, Oruro, Bolivia',
            'telefono' => '+591 2 5252525',
            'telefono_emergencias' => '+591 2 5252526',
            'email' => 'info@clinicaislafuente.com',
            'horario_atencion' => 'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 12:00 PM',
            'servicios' => 'Consultas médicas generales, Medicina preventiva, Chequeos ocupacionales, Atención pediátrica, Cardiología, Ginecología',
        ]);
    }

    /**
     * Obtener servicios como array
     */
    private function obtenerServicios($clinica): array
    {
        if (empty($clinica->servicios)) {
            return [];
        }

        if (is_string($clinica->servicios)) {
            return array_map('trim', explode(',', $clinica->servicios));
        }

        return $clinica->servicios;
    }
}