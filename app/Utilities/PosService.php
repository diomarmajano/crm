<?php

namespace App\Utilities;

use App\Models\Inventory;
use App\Models\Items;
use App\Models\Pedidos;
use Exception;
use Illuminate\Support\Facades\DB;

class PosService
{
    public function crearPedido(array $cart, array $formData, float $total, $userId, $tenantId)
    {
        // 1. Validar Stock
        foreach ($cart as $item) {
            if (! empty($item['id'])) {
                $inventario = Inventory::where('id_service', $item['id'])->first();
                if (! $inventario || $inventario->stock_producto < $item['cantidad']) {
                    throw new Exception('El producto '.$item['nombre'].' solo tiene '.$inventario->stock_producto.'unidades.');
                }
            }
        }

        // 2. Transacción
        return DB::transaction(function () use ($cart, $formData, $total, $userId, $tenantId) {
            $pedido = Pedidos::create([
                'user_id' => $userId,
                'total_pedido' => $total,
                'medio_pago' => $formData['medio_pago'],
                'tenant_id' => $tenantId,
            ]);

            foreach ($cart as $item) {
                Items::create([
                    'pedido_id' => $pedido->id,
                    'servicio_id' => $item['id'],
                    'nombre_servicio' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                    'tenant_id' => $tenantId,
                ]);

                if (! empty($item['id'])) {
                    Inventory::where('id_service', $item['id'])->decrement('stock_producto', $item['cantidad']);
                }
            }

            // Cargamos la relación items para la impresión posterior
            return $pedido->load('items');
        });
    }
}
