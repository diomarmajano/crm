<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function imprimirTicket(Request $request, $pedidoId)
    {
        $pedido = Pedidos::with('items')->findOrFail($pedidoId);

        $user = auth()->user();
        $tenant = $user->tenant;

        $nombreTienda = 'Almacen';
        if ($tenant && $tenant->name) {
            $nombreTienda = strtoupper($tenant->name);
        }
        $pagaCon = $request->query('paga_con', 0);
        $vuelto = $request->query('vuelto', 0);

        $ancho = 32;
        $ticket = '';

        $centrar = fn ($texto) => str_pad(substr($texto, 0, $ancho), $ancho, ' ', STR_PAD_BOTH)."\n";
        $derecha = fn ($texto) => str_pad(substr($texto, 0, $ancho), $ancho, ' ', STR_PAD_LEFT)."\n";

        // --- ENCABEZADO ---
        $ticket .= $centrar($nombreTienda);
        $ticket .= $centrar('Boleta N° '.$pedido->id);
        $ticket .= $centrar($pedido->created_at->format('d-m-Y H:i:s'));
        $ticket .= str_repeat('-', $ancho)."\n";

        // --- ITEMS ---
        foreach ($pedido->items as $item) {
            $nombre = $item->nombre_servicio;
            $cantidad = $item->cantidad;
            $precioUnitario = $item->precio_unitario;
            $subtotal = $precioUnitario * $cantidad;

            // LÍNEA 1: Nombre del producto
            $ticket .= substr($nombre, 0, $ancho)."\n";

            // LÍNEA 2: Cantidad x Precio Unitario ...... Total
            $detalleMatematico = $cantidad.' x $'.number_format($precioUnitario, 0, ',', '.');
            $detalleTotal = '$'.number_format($subtotal, 0, ',', '.');

            // Exactamente como lo tenías: 20 espacios a la izquierda, 12 a la derecha
            $linea = str_pad($detalleMatematico, 20).str_pad($detalleTotal, 12, ' ', STR_PAD_LEFT);
            $ticket .= $linea."\n";
        }

        // --- TOTALES ---
        $ticket .= str_repeat('-', $ancho)."\n";
        $ticket .= $derecha('Total: $'.number_format($pedido->total_pedido, 0, ',', '.'));
        $ticket .= $derecha('Medio: '.ucfirst($pedido->medio_pago));

        if (strtolower($pedido->medio_pago) === 'efectivo') {
            $ticket .= $derecha('Entregado: $'.number_format($pagaCon, 0, ',', '.'));
            $ticket .= $derecha('Vuelto: $'.number_format($vuelto, 0, ',', '.'));
        }

        // --- PIE DE PÁGINA ---
        $ticket .= "\n";
        $ticket .= $centrar('¡Gracias por su preferencia!');
        $ticket .= "\n\n\n"; // Espacio extra para que el papel salga lo suficiente para el corte manual/automático

        // Pasamos ÚNICAMENTE la cadena de texto a la vista
        return view('pdf.pedido', compact('ticket'));
    }
}
