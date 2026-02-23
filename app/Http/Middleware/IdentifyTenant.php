<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtener el host (ej: lavanderia-sol.cleanstack.cl)
        $host = $request->getHost();

        // 2. Extraer el subdominio (la primera parte antes del punto)
        // OJO: Esto asume que usas subdominios. Si estás en local (ej: lavanderia.test), ajusta esto.
        $parts = explode('.', $host);
        $subdomain = $parts[0];

        // 3. Buscar el Tenant en la BD
        // Ignoramos dominios del sistema como 'admin', 'www' o 'cleanstack'
        if (in_array($subdomain, ['admin', 'www', 'crmcloud', 'localhost'])) {
            return $next($request);
        }

        $tenant = Tenant::where('slug', $subdomain)->first();

        // 4. Si existe, lo guardamos en la sesión y en el container de Laravel
        if ($tenant) {
            session()->put('tenant_id', $tenant->id);

            // Opcional: También lo puedes guardar globalmente para acceder fácil
            app()->instance('currentTenant', $tenant);
        } else {
            // Si el subdominio no existe, mostramos error 404
            abort(404, 'Lavandería no encontrada');
        }

        return $next($request);
    }
}
