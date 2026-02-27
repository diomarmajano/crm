<?php

namespace App\Utilities;

use App\Models\Pedidos;
use Filament\Notifications\Notification;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class TicketPrinterService
{
    public function imprimir(Pedidos $pedido, float $pagaCon, float $vuelto)
    {
        try {
            if (auth()->check() && auth()->user()->tenant) {
                $nombreTienda = strtoupper(auth()->user()->tenant->name);
            }

            $connector = new WindowsPrintConnector('XP-58');
            $printer = new Printer($connector);

            // --- ENCABEZADO ---
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text($nombreTienda."\n");
            $printer->setTextSize(1, 1);
            $printer->text('Boleta N° '.$pedido->id."\n");
            $printer->text($pedido->created_at->format('d-m-Y H:i:s')."\n");
            $printer->text("-----------------------------\n");

            // --- ITEMS DEL PEDIDO (DISEÑO MODIFICADO) ---
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            // Encabezado de columnas pequeño (Opcional, ayuda a entender)
            // $printer->text("Prod           Cant x Unit    Total\n");
            // $printer->text("-----------------------------\n");

            foreach ($pedido->items as $item) {
                $nombre = $item->nombre_servicio;
                $cantidad = $item->cantidad;
                $precioUnitario = $item->precio_unitario;
                $subtotal = $precioUnitario * $cantidad;

                // LÍNEA 1: Nombre del producto completo (o cortado a 32 chars)
                // Esto asegura que el nombre se lea bien
                $printer->text(substr($nombre, 0, 32)."\n");

                // LÍNEA 2: Cantidad x Precio Unitario ...... Total
                // Formato: "2 x $1.000"
                $detalleMatematico = $cantidad.' x $'.number_format($precioUnitario, 0, ',', '.');

                // Formato: "$2.000"
                $detalleTotal = '$'.number_format($subtotal, 0, ',', '.');

                // Cálculo de espaciado para alinear a la derecha (Ancho aprox 32 chars en 58mm)
                // Ponemos el detalle a la izquierda (20 espacios) y el total a la derecha (12 espacios)
                $linea = str_pad($detalleMatematico, 20).str_pad($detalleTotal, 12, ' ', STR_PAD_LEFT);

                $printer->text($linea."\n");
            }

            // --- TOTALES ---
            $printer->text("-----------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(1, 1); // Aseguramos tamaño normal
            $printer->text('Total: $'.number_format($pedido->total_pedido, 0, ',', '.')."\n");
            $printer->text('Medio: '.ucfirst($pedido->medio_pago)."\n");

            if (strtolower($pedido->medio_pago) === 'efectivo') {
                $printer->text('Entregado: $'.number_format($pagaCon, 0, ',', '.')."\n");
                $printer->text('Vuelto: $'.number_format($vuelto, 0, ',', '.')."\n");
            }

            // --- PIE DE PÁGINA ---
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("¡Gracias por su preferencia!\n");

            $printer->feed(3);
            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al imprimir')
                // ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
