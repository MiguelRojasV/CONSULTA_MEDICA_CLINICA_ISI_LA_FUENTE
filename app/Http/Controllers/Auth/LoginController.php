<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $this->verificarIntentosFallidos($request);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {

            RateLimiter::clear($this->throttleKey($request));

            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Validación corregida
            if (!$user->tienePerfilCompleto()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Tu perfil no está completo. Contacta al administrador.',
                ]);
            }

            return $this->redirectToDashboard($user->role);
        }

        RateLimiter::hit($this->throttleKey($request));

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    protected function verificarIntentosFallidos(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta en {$seconds} segundos.",
            ]);
        }
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    private function redirectToDashboard(string $role): RedirectResponse
    {
        $user = Auth::user();

        return match($role) {
            'paciente' => redirect()->route('paciente.dashboard')
                ->with('success', "¡Bienvenido {$user->name}!"),
                
            'medico' => redirect()->route('medico.dashboard')
                ->with('success', "¡Bienvenido Dr(a). {$user->name}!"),
                
            'administrador' => redirect()->route('admin.dashboard')
                ->with('success', "¡Bienvenido Administrador!"),
                
            default => redirect()->route('home')
                ->with('error', 'Rol no reconocido.'),
        };
    }
}