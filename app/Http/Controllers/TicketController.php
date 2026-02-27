<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function imprimirTicket(Pedidos $pedido)
    {
        $pedido->load('items');
        
        $nombreTienda = 'Almacen';
        if ($pedido->tenant_id) {
            $tenantName = DB::table('tenants')->where('id', $pedido->tenant_id)->value('name');
            if ($tenantName) {
                $nombreTienda = strtoupper($tenantName);
            }
        }

        // Datos calculados (que antes pasabas como parámetros, 
        // idealmente deberían estar guardados en el pedido o calcularse aquí)
        // Por simplicidad, asumiremos que se pasan por URL o se calculan:
        $pagaCon = request('paga_con', 0); // O obtener del modelo si lo guardaste
        $vuelto = request('vuelto', 0);

        return view('pdf.pedido', compact('pedido', 'nombreTienda', 'pagaCon', 'vuelto'));
    }
}