<?php

use App\Http\Controllers\TicketController;
use App\Http\Middleware\SetTenantDatabase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('newWelcome');
});

Route::get('/login-rapido/{user}', function (Request $request, User $user) {
    // 1. Verificar que el enlace no haya sido manipulado y no haya expirado
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
    return redirect()->away('https://demo.crmcloud.cl/admin');

})->name('autologin');

Route::get('/imprimir/pedido/{pedido}', [TicketController::class, 'imprimirTicket'])
    ->name('imprimir.ticket')
    ->middleware('auth', SetTenantDatabase::class);
