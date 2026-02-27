<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function imprimirTicket(Request $request, $pedidoId)
    {
        // CAMBIO 2: Como el middleware SetTenantDatabase ya preparó la conexión,
        // ahora sí podemos buscar el pedido de forma segura.
        $pedido = Pedidos::with('items')->findOrFail($pedidoId);

        $user = auth()->user();
        $tenant = $user->tenant;

        $nombreTienda = 'Almacen';
        if ($tenant && $tenant->name) {
            $nombreTienda = strtoupper($tenant->name);
        }

        // CAMBIO 3: Usamos $request->query() para obtener las variables de la URL de forma segura
        $pagaCon = $request->query('paga_con', 0);
        $vuelto = $request->query('vuelto', 0);

        return view('pdf.pedido', compact('pedido', 'nombreTienda', 'pagaCon', 'vuelto'));
    }
}
