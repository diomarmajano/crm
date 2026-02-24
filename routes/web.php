<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('newWelcome');
});

Route::get('/login-rapido/{user}', function (User $user) {
    if (! request()->hasValidSignature()) {
        abort(401);
    }

    FacadesAuth::login($user);

    return redirect('/admin');
})->name('autologin');
