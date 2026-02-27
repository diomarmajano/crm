<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $tenant = auth()->user()->tenant;

        if (! $tenant || ! $tenant->is_active) {
            abort(403, 'Tu cuenta está inactiva o no tiene una instancia asignada.');
        }

        // 1. Inyectamos la BD
        config(['database.connections.tenant.database' => $tenant->database_name]);

        // 2. Purgamos la conexión vieja
        DB::purge('tenant');

        // 3. Forzamos la reconexión física inmediata con la nueva BD
        DB::reconnect('tenant');

        // 4. Establecemos por defecto
        config(['database.default' => 'tenant']);
        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}
