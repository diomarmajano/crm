<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $tenants = Tenant::select('id', 'name', 'domain')->get();

    return view('welcome', compact('tenants'));
});
