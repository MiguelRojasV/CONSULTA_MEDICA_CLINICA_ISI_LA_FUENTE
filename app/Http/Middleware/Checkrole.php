<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 * Verifica que el usuario tenga el rol adecuado para acceder a una ruta
 * 
 * Uso en rutas:
 * Route::get('/admin/dashboard', [AdminController::class, 'index'])
 *      ->middleware('auth', 'role:administrador');
 */
class CheckRole
{
    /**
     * Maneja la solicitud entrante
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Roles permitidos
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para acceder');
        }

        // Obtener el rol del usuario autenticado
        $userRole = auth()->user()->role;

        // Verificar si el usuario tiene alguno de los roles permitidos
        if (!in_array($userRole, $roles)) {
            // Redirigir según el rol del usuario
            return $this->redirectBasedOnRole($userRole);
        }

        return $next($request);
    }

    /**
     * Redirige al usuario a su dashboard según su rol
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectBasedOnRole(string $role)
    {
        $message = 'No tiene permisos para acceder a esta sección';

        return match($role) {
            'paciente' => redirect()->route('paciente.dashboard')->with('error', $message),
            'medico' => redirect()->route('medico.dashboard')->with('error', $message),
            'administrador' => redirect()->route('admin.dashboard')->with('error', $message),
            default => redirect()->route('home')->with('error', $message),
        };
    }
}
