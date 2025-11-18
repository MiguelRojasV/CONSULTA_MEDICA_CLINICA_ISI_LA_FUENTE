<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\InformacionClinica;
use App\Models\Medico;

/**
 * HomeController
 * Ubicación: app/Http/Controllers/HomeController.php
 * 
 * Gestiona la página de inicio (landing page)
 * Muestra información general de la clínica
 * Accesible para todos los usuarios (público)
 * 
 * ACTUALIZADO: Compatible con nueva estructura 3FN
 * - Usa relación medico->especialidad
 * - Filtra médicos activos
 * - Campos actualizados según nueva BD
 */
class HomeController extends Controller
{
    /**
     * Muestra la página de inicio
     * @return View
     */
    public function index(): View
    {
        // Obtener la información de la clínica
        $clinica = InformacionClinica::obtenerInfo();

        // Si no existe, crear información por defecto
        if (!$clinica) {
            $clinica = $this->crearInformacionPorDefecto();
        }

        // Obtener médicos activos con su especialidad
        // Solo médicos con estado 'Activo' y cargar la relación especialidad
        $medicos = Medico::with('especialidad')
            ->where('estado', 'Activo')
            ->select('id', 'nombre', 'apellido', 'especialidad_id', 'consultorio', 'turno')
            ->take(6) // Mostrar solo 6 médicos
            ->get();

        // Convertir servicios a array
        $servicios = $this->obtenerServicios($clinica);

        // Retornar la vista del home con los datos
        return view('home', compact('clinica', 'medicos', 'servicios'));
    }

    /**
     * Crea información por defecto si no existe
     * @return InformacionClinica
     */
    private function crearInformacionPorDefecto(): InformacionClinica
    {
        return InformacionClinica::create([
            'nombre' => 'Clínica ISI La Fuente',
            'slogan' => 'Tu salud, nuestra prioridad',
            'descripcion' => 'Clínica especializada en atención médica integral con enfoque en medicina preventiva y atención de calidad. Contamos con profesionales altamente capacitados y tecnología de punta.',
            'mision' => 'Brindar atención médica de excelencia, con calidez humana, a través de un equipo de salud comprometido con la capacitación continua y la innovación tecnológica, garantizando el bienestar de nuestros pacientes.',
            'vision' => 'Ser referentes a nivel nacional e internacional en servicios de salud, brindando atención de calidad, con seguridad y plena satisfacción del paciente, siendo reconocidos por nuestra ética profesional y compromiso social.',
            'valores' => 'Ética profesional, Compromiso, Calidad, Calidez humana, Innovación',
            'direccion' => 'Calle Beni entre 6 de octubre y Potosí, Nro. 60, Oruro, Bolivia',
            'telefono' => '+591 2 5252525',
            'telefono_emergencias' => '+591 2 5252526',
            'email' => 'info@clinicaislafuente.com',
            'horario_atencion' => 'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 12:00 PM | Domingos y Feriados: Cerrado',
            'servicios' => 'Consultas médicas generales, Medicina preventiva, Chequeos ocupacionales, Atención pediátrica, Cardiología, Ginecología, Traumatología, Laboratorio clínico, Farmacia, Ecografías, Radiografías',
            'whatsapp' => '+59170123456',
        ]);
    }

    /**
     * Obtener lista de servicios como array
     * @param InformacionClinica $clinica
     * @return array
     */
    private function obtenerServicios(InformacionClinica $clinica): array
    {
        if (empty($clinica->servicios)) {
            return [];
        }

        // Si servicios es un string separado por comas, convertir a array
        if (is_string($clinica->servicios)) {
            return array_map('trim', explode(',', $clinica->servicios));
        }

        return $clinica->servicios;
    }
}

/**
 * EXPLICACIÓN DEL CONTROLADOR:
 * 
 * Este controlador maneja la página de inicio (landing page) del sistema.
 * 
 * FUNCIONES PRINCIPALES:
 * 1. Obtiene información de la clínica desde la BD
 * 2. Muestra 6 médicos activos con sus especialidades
 * 3. Lista los servicios ofrecidos
 * 4. Crea datos por defecto si no existen
 * 
 * LA VISTA 'home' MOSTRARÁ:
 * - Nombre, slogan y descripción de la clínica
 * - Misión, visión y valores
 * - Dirección y datos de contacto (teléfono, emergencias, email)
 * - Horarios de atención
 * - Lista de servicios médicos
 * - Médicos destacados con su especialidad, consultorio y turno
 * - Redes sociales (Facebook, Instagram, WhatsApp)
 * - Imagen principal o logo de la clínica
 * 
 * CAMBIOS EN ESTA ACTUALIZACIÓN:
 * - Compatible con nueva BD normalizada (3FN)
 * - Usa relación Medico->Especialidad (en lugar de campo directo)
 * - Filtra solo médicos con estado 'Activo'
 * - Incluye campos nuevos: apellido, consultorio, turno
 * - Agrega slogan, valores, teléfono_emergencias, whatsapp
 * 
 * Esta página es PÚBLICA y no requiere autenticación
 */