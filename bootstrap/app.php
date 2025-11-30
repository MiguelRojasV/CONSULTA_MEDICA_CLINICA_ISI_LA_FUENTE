<?php
#bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar alias de middleware personalizados
        $middleware->alias([
            // Middleware para verificar roles
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Middleware que se ejecuta en todas las solicitudes web
        $middleware->web(append: [
            // Aquí puedes agregar middleware global si es necesario
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuración de manejo de excepciones
    })->create();

/**
 * EXPLICACIÓN:
 * 
 * 1. Este archivo configura la aplicación Laravel
 * 2. Se registra el middleware 'role' que verifica permisos
 * 3. Uso en rutas: ->middleware('role:paciente') o ->middleware('role:medico,administrador')
 */