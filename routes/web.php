<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\MedicamentoController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Rutas para Pacientes
Route::resource('pacientes', PacienteController::class);

// Rutas para Médicos
Route::resource('medicos', MedicoController::class);

// Rutas para Citas
Route::resource('citas', CitaController::class);

// Rutas para Recetas
Route::resource('recetas', RecetaController::class);

// Rutas para Medicamentos
Route::resource('medicamentos', MedicamentoController::class);

// Rutas adicionales para reportes
Route::get('/reportes/citas-diarias', [CitaController::class, 'reporteDiario'])
    ->name('reportes.citas-diarias');
    
Route::get('/reportes/citas-semanales', [CitaController::class, 'reporteSemanal'])
    ->name('reportes.citas-semanales');