<?php
// Redirige usuarios autenticados según su rol
// ============================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware RedirectIfAuthenticated
 * Redirige a usuarios ya autenticados a su dashboard correspondiente
 */
class RedirectIfAuthenticated
{
    /**
     * Maneja la solicitud entrante
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Usuario autenticado, redirigir a su dashboard
                $user = Auth::user();
                
                return match($user->role) {
                    'paciente' => redirect()->route('paciente.dashboard'),
                    'medico' => redirect()->route('medico.dashboard'),
                    'administrador' => redirect()->route('admin.dashboard'),
                    default => redirect()->route('home'),
                };
            }
        }

        return $next($request);
    }
}