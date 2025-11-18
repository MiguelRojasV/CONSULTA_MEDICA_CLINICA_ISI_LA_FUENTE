<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * LogoutController
 * Ubicación: app/Http/Controllers/Auth/LogoutController.php
 * 
 * Gestiona el cierre de sesión de usuarios
 * Limpia la sesión y redirige al inicio
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
        // Obtener nombre del usuario antes de cerrar sesión
        $userName = Auth::user()->name ?? 'Usuario';

        // Cerrar la sesión del usuario
        Auth::logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF para seguridad
        $request->session()->regenerateToken();

        // Redirigir a la página de inicio con mensaje
        return redirect()->route('home')
            ->with('success', "Hasta pronto, {$userName}. Has cerrado sesión correctamente.");
    }
}