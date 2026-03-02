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
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            config(['database.default' => 'central']);

            return $next($request);
        }

        config(['database.connections.tenant.database' => $tenant->database_name]);

        DB::purge('tenant');

        DB::reconnect('tenant');

        config(['database.default' => 'tenant']);
        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}
