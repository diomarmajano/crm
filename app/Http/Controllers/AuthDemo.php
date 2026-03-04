<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthDemo extends Controller
{
    public function AutoLogin(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'El enlace de acceso ha expirado.');
        }

        // 2. Autenticar al usuario físicamente
        Auth::login($user, true);

        // 3. Configurar la sesión para que Filament la reconozca
        $request->session()->regenerate();

        // Esto es un "truco" para que Filament no pida verificar password
        $request->session()->put('auth.password_confirmed_at', time());

        // Guardar cambios en el archivo de sesión antes de salir
        $request->session()->save();

        // 4. Redirigir al subdominio
        return redirect()->away(url('/admin'));
    }
}
