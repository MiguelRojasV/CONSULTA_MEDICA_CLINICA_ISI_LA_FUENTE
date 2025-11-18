<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 * Ubicación: app/Http/Middleware/CheckRole.php
 * 
 * Verifica que el usuario tenga el rol correcto para acceder a una ruta
 * Uso en rutas: middleware('role:paciente')
 */
class CheckRole
{
    /**
     * Maneja una solicitud entrante
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  El rol requerido
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        $user = Auth::user();

        // Verificar que el usuario tenga el rol requerido
        if ($user->role !== $role) {
            // Redirigir al dashboard correcto según su rol
            return $this->redirectToCorrectDashboard($user->role);
        }

        // Si tiene el rol correcto, continuar con la solicitud
        return $next($request);
    }

    /**
     * Redirige al usuario a su dashboard correcto
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToCorrectDashboard(string $role)
    {
        $message = 'No tienes permisos para acceder a esta sección.';

        return match($role) {
            'paciente' => redirect()->route('paciente.dashboard')->with('error', $message),
            'medico' => redirect()->route('medico.dashboard')->with('error', $message),
            'administrador' => redirect()->route('admin.dashboard')->with('error', $message),
            default => redirect()->route('home')->with('error', $message),
        };
    }
}