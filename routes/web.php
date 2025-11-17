<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Controladores de Paciente
use App\Http\Controllers\Paciente\PacienteDashboardController;
use App\Http\Controllers\Paciente\PacienteCitaController;
use App\Http\Controllers\Paciente\PacienteRecetaController;
use App\Http\Controllers\Paciente\PacientePerfilController;
use App\Http\Controllers\Paciente\PacienteHistorialController;

// Controladores de Médico
use App\Http\Controllers\Medico\MedicoDashboardController;
use App\Http\Controllers\Medico\MedicoCitaController;
use App\Http\Controllers\Medico\MedicoRecetaController;
use App\Http\Controllers\Medico\MedicoPerfilController;
use App\Http\Controllers\Medico\MedicoPacienteController;

// Controladores de Administrador
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPacienteController;
use App\Http\Controllers\Admin\AdminMedicoController;
use App\Http\Controllers\Admin\AdminCitaController;
use App\Http\Controllers\Admin\AdminMedicamentoController;
use App\Http\Controllers\Admin\AdminRecetaController;
use App\Http\Controllers\Admin\AdminReporteController;

// ============================================
// RUTAS PÚBLICAS (sin autenticación)
// ============================================

/**
 * Página de inicio (Home)
 * Muestra información de la clínica
 * Accesible para todos los usuarios
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Rutas de Autenticación
 * Login, Registro y Logout
 */
// Mostrar formulario de login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest'); // Solo usuarios no autenticados

// Procesar login
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

// Mostrar formulario de registro (solo para pacientes)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('guest');

// Procesar registro
Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.submit');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================
// RUTAS DE PACIENTE
// Solo accesibles para usuarios con rol 'paciente'
// ============================================
Route::prefix('paciente')->name('paciente.')->middleware(['auth', 'role:paciente'])->group(function () {
    
    /**
     * Dashboard del Paciente
     * Vista principal con resumen de información
     */
    Route::get('/dashboard', [PacienteDashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * Gestión de Citas del Paciente
     * El paciente puede ver, agendar y cancelar sus citas
     */
    // Listar mis citas
    Route::get('/citas', [PacienteCitaController::class, 'index'])
        ->name('citas.index');
    
    // Formulario para agendar nueva cita
    Route::get('/citas/crear', [PacienteCitaController::class, 'create'])
        ->name('citas.create');
    
    // Guardar nueva cita
    Route::post('/citas', [PacienteCitaController::class, 'store'])
        ->name('citas.store');
    
    // Ver detalles de una cita
    Route::get('/citas/{cita}', [PacienteCitaController::class, 'show'])
        ->name('citas.show');
    
    // Cancelar cita
    Route::post('/citas/{cita}/cancelar', [PacienteCitaController::class, 'cancelar'])
        ->name('citas.cancelar');

    /**
     * Gestión de Recetas
     * El paciente puede ver y descargar sus recetas en PDF
     */
    // Listar mis recetas
    Route::get('/recetas', [PacienteRecetaController::class, 'index'])
        ->name('recetas.index');
    
    // Ver detalles de una receta
    Route::get('/recetas/{receta}', [PacienteRecetaController::class, 'show'])
        ->name('recetas.show');
    
    // Descargar receta en PDF
    Route::get('/recetas/{receta}/pdf', [PacienteRecetaController::class, 'descargarPDF'])
        ->name('recetas.pdf');

    /**
     * Historial Médico
     * El paciente puede ver y descargar su historial médico
     */
    // Ver historial médico
    Route::get('/historial', [PacienteHistorialController::class, 'index'])
        ->name('historial.index');
    
    // Descargar historial en PDF
    Route::get('/historial/pdf', [PacienteHistorialController::class, 'descargarPDF'])
        ->name('historial.pdf');

    /**
     * Perfil del Paciente
     * Ver y editar información personal
     */
    // Ver perfil
    Route::get('/perfil', [PacientePerfilController::class, 'show'])
        ->name('perfil.show');
    
    // Editar perfil
    Route::get('/perfil/editar', [PacientePerfilController::class, 'edit'])
        ->name('perfil.edit');
    
    // Actualizar perfil
    Route::put('/perfil', [PacientePerfilController::class, 'update'])
        ->name('perfil.update');
});

// ============================================
// RUTAS DE MÉDICO
// Solo accesibles para usuarios con rol 'medico'
// ============================================
Route::prefix('medico')->name('medico.')->middleware(['auth', 'role:medico'])->group(function () {
    
    /**
     * Dashboard del Médico
     * Vista principal con agenda del día y estadísticas
     */
    Route::get('/dashboard', [MedicoDashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * Gestión de Citas del Médico
     * Ver agenda, atender pacientes, registrar consultas
     */
    // Listar citas (agenda)
    Route::get('/citas', [MedicoCitaController::class, 'index'])
        ->name('citas.index');
    
    // Ver detalles de cita
    Route::get('/citas/{cita}', [MedicoCitaController::class, 'show'])
        ->name('citas.show');
    
    // Editar cita (agregar diagnóstico y tratamiento)
    Route::get('/citas/{cita}/editar', [MedicoCitaController::class, 'edit'])
        ->name('citas.edit');
    
    // Actualizar cita
    Route::put('/citas/{cita}', [MedicoCitaController::class, 'update'])
        ->name('citas.update');
    
    // Marcar como atendida
    Route::post('/citas/{cita}/atender', [MedicoCitaController::class, 'marcarAtendida'])
        ->name('citas.atender');

    /**
     * Gestión de Pacientes
     * Ver información de pacientes e historial
     */
    // Listar pacientes
    Route::get('/pacientes', [MedicoPacienteController::class, 'index'])
        ->name('pacientes.index');
    
    // Ver perfil de paciente
    Route::get('/pacientes/{paciente}', [MedicoPacienteController::class, 'show'])
        ->name('pacientes.show');
    
    // Ver historial médico de paciente
    Route::get('/pacientes/{paciente}/historial', [MedicoPacienteController::class, 'historial'])
        ->name('pacientes.historial');

    /**
     * Gestión de Recetas
     * Emitir y gestionar recetas médicas
     */
    // Listar recetas emitidas
    Route::get('/recetas', [MedicoRecetaController::class, 'index'])
        ->name('recetas.index');
    
    // Formulario para crear receta
    Route::get('/recetas/crear', [MedicoRecetaController::class, 'create'])
        ->name('recetas.create');
    
    // Guardar receta
    Route::post('/recetas', [MedicoRecetaController::class, 'store'])
        ->name('recetas.store');
    
    // Ver receta
    Route::get('/recetas/{receta}', [MedicoRecetaController::class, 'show'])
        ->name('recetas.show');
    
    // Descargar receta en PDF
    Route::get('/recetas/{receta}/pdf', [MedicoRecetaController::class, 'descargarPDF'])
        ->name('recetas.pdf');

    /**
     * Perfil del Médico
     * Ver y editar información profesional
     */
    Route::get('/perfil', [MedicoPerfilController::class, 'show'])
        ->name('perfil.show');
    
    Route::get('/perfil/editar', [MedicoPerfilController::class, 'edit'])
        ->name('perfil.edit');
    
    Route::put('/perfil', [MedicoPerfilController::class, 'update'])
        ->name('perfil.update');
});

// ============================================
// RUTAS DE ADMINISTRADOR
// Solo accesibles para usuarios con rol 'administrador'
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:administrador'])->group(function () {
    
    /**
     * Dashboard del Administrador
     * Vista general con estadísticas del sistema
     */
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * Gestión Completa de Pacientes
     * CRUD completo de pacientes
     */
    Route::resource('pacientes', AdminPacienteController::class);
    
    // Descargar historial de paciente en PDF
    Route::get('/pacientes/{paciente}/historial/pdf', [AdminPacienteController::class, 'descargarHistorialPDF'])
        ->name('pacientes.historial.pdf');

    /**
     * Gestión Completa de Médicos
     * CRUD completo de médicos
     */
    Route::resource('medicos', AdminMedicoController::class);

    /**
     * Gestión Completa de Citas
     * CRUD completo de citas
     */
    Route::resource('citas', AdminCitaController::class);

    /**
     * Gestión de Medicamentos
     * CRUD completo de medicamentos (inventario)
     */
    Route::resource('medicamentos', AdminMedicamentoController::class);

    /**
     * Gestión de Recetas
     * Ver y descargar recetas
     */
    Route::get('/recetas', [AdminRecetaController::class, 'index'])
        ->name('recetas.index');
    
    Route::get('/recetas/{receta}', [AdminRecetaController::class, 'show'])
        ->name('recetas.show');
    
    Route::get('/recetas/{receta}/pdf', [AdminRecetaController::class, 'descargarPDF'])
        ->name('recetas.pdf');

    /**
     * Reportes y Estadísticas
     * Búsqueda por fecha, mes, nombre, CI
     */
    // Reportes generales
    Route::get('/reportes', [AdminReporteController::class, 'index'])
        ->name('reportes.index');
    
    // Reporte de citas por fecha
    Route::get('/reportes/citas', [AdminReporteController::class, 'citasPorFecha'])
        ->name('reportes.citas');
    
    // Reporte de pacientes
    Route::get('/reportes/pacientes', [AdminReporteController::class, 'pacientes'])
        ->name('reportes.pacientes');
    
    // Búsqueda avanzada
    Route::get('/busqueda', [AdminReporteController::class, 'busquedaAvanzada'])
        ->name('busqueda');
});


//  EXPLICACIÓN DE LA ESTRUCTURA DE RUTAS: 
// 1. Rutas públicas: Accesibles sin autenticación (home, login, register)
// * 2. Rutas de Paciente: Prefijo /paciente, middleware 'role:paciente'
 //* 3. Rutas de Médico: Prefijo /medico, middleware 'role:medico'
 //* 4. Rutas de Admin: Prefijo /admin, middleware 'role:administrador'
 //* 
 //* Cada grupo tiene su propio dashboard y funcionalidades específicas
 //* El middleware 'auth' verifica autenticación
 //* El middleware 'role' verifica el rol correcto
 //** 