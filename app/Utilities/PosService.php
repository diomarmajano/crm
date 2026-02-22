<?php

namespace App\Utilities;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Items;
use App\Models\Pedidos;
use Exception;
use Illuminate\Support\Facades\DB;

class PosService
{
    public function crearPedido(array $cart, array $formData, float $total, $userId, $tenantId)
    {
        // Iniciamos la transacción directamente
        return DB::transaction(function () use ($cart, $formData, $total, $userId, $tenantId) {

            // 1. Crear el pedido principal
            $pedido = Pedidos::create([
                'user_id' => $userId,
                'total_pedido' => $total,
                'medio_pago' => $formData['medio_pago'],
                'tenant_id' => $tenantId,
            ]);

            // 2. Procesar cada item del carrito
            foreach ($cart as $item) {

                // Crear el detalle del pedido
                Items::create([
                    'pedido_id' => $pedido->id,
                    'servicio_id' => $item['id'],
                    'nombre_servicio' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                    'tenant_id' => $tenantId,
                ]);

                // 3. Lógica de Inventario (Kardex / Movimientos)
                if (! empty($item['id'])) {

                    // Bloqueamos la fila para evitar ventas simultáneas que rompan el stock (Race conditions)
                    $inventario = Inventory::where('id_service', $item['id'])
                        ->lockForUpdate()
                        ->first();

                    // Validar que el inventario exista
                    if (! $inventario) {
                        throw new Exception("El producto {$item['nombre']} no tiene un inventario asociado.");
                    }

                    // Validar que haya stock suficiente
                    if ($inventario->stock_producto < $item['cantidad']) {
                        throw new Exception("El producto {$item['nombre']} solo tiene {$inventario->stock_producto} unidades disponibles.");
                    }

                    // Calcular los stocks
                    $stockAnterior = $inventario->stock_producto;
                    $stockNuevo = $stockAnterior - $item['cantidad'];

                    // A) Actualizar el stock en la tabla principal
                    $inventario->update([
                        'stock_producto' => $stockNuevo,
                    ]);

                    // B) Registrar el movimiento en el historial
                    InventoryMovement::create([
                        'inventory_id' => $inventario->id,
                        'tenant_id' => $tenantId,
                        'user_id' => $userId,
                        'tipo' => 'salida',
                        'cantidad' => $item['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        // Lo mejor de esto es que puedes guardar el ID del pedido como referencia
                        'motivo' => "Venta generada - Pedido #{$pedido->id}",
                    ]);
                }
            }

            // Cargamos la relación items para la impresión posterior
            return $pedido->load('items');
        });
    }
}
