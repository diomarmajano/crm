<?php

use App\Http\Controllers\AuthDemo;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\SetTenantDatabase;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('newWelcome');
});

Route::get('/login-rapido/{user:name}', [AuthDemo::class, 'AutoLogin'])->name('autologin');

Route::get('/imprimir/pedido/{pedido}', [TicketController::class, 'imprimirTicket'])
    ->name('imprimir.ticket')
    ->middleware('auth', SetTenantDatabase::class);
