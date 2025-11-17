<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController
 * Gestiona el proceso de inicio de sesión de usuarios
 * Redirige según el rol del usuario autenticado
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de login
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validar los datos de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres'
        ]);

        // Verificar si quiere recordar la sesión
        $remember = $request->filled('remember');

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials, $remember)) {
            // Regenerar la sesión para prevenir ataques de fijación de sesión
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $user = Auth::user();

            // Redirigir según el rol del usuario
            return $this->redirectToDashboard($user->role);
        }

        // Si la autenticación falla, regresar con error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Redirige al dashboard correspondiente según el rol
     * @param string $role
     * @return RedirectResponse
     */
    private function redirectToDashboard(string $role): RedirectResponse
    {
        return match($role) {
            'paciente' => redirect()->route('paciente.dashboard')
                ->with('success', '¡Bienvenido! Has iniciado sesión correctamente.'),
            'medico' => redirect()->route('medico.dashboard')
                ->with('success', '¡Bienvenido Dr/Dra! Has iniciado sesión correctamente.'),
            'administrador' => redirect()->route('admin.dashboard')
                ->with('success', '¡Bienvenido Administrador! Has iniciado sesión correctamente.'),
            default => redirect()->route('home')
                ->with('error', 'Rol de usuario no reconocido.'),
        };
    }
}