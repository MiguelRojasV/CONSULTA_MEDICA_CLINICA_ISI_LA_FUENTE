<?php 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * LogoutController
 * Gestiona el cierre de sesión de usuarios
 */
class LogoutController extends Controller
{
    /**
     * Cierra la sesión del usuario actual
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Cerrar la sesión del usuario
        Auth::logout();

        // Invalidar la sesión
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        // Redirigir a la página de inicio
        return redirect()->route('home')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}