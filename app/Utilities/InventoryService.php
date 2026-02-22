<?php

namespace App\Utilities;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Registra un movimiento y actualiza el stock.
     */
    public function registrarMovimiento(Inventory $inventory, string $tipo, int $cantidad, string $motivo, $userId = null)
    {
        // Asegurarnos de que la cantidad sea siempre positiva
        $cantidad = abs($cantidad);

        return DB::transaction(function () use ($inventory, $tipo, $cantidad, $motivo, $userId) {

            // 1. Bloqueamos la fila para evitar "condiciones de carrera" (cuando 2 venden a la vez)
            $inventory = Inventory::where('id', $inventory->id)->lockForUpdate()->first();

            $stockAnterior = $inventory->stock_producto;
            $stockNuevo = $stockAnterior;

            // 2. Calcular el nuevo stock según el tipo
            if ($tipo === 'entrada') {
                $stockNuevo = $stockAnterior + $cantidad;
            } elseif ($tipo === 'salida' || $tipo === 'ajuste') {
                $stockNuevo = $stockAnterior - $cantidad;

                // Opcional: Validar que no quede en negativo
                if ($stockNuevo < 0) {
                    throw new \Exception('Stock insuficiente para realizar esta operación.');
                }
            }

            // 3. Crear el registro en el historial (Kardex)
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'tenant_id' => $inventory->tenant_id,
                'user_id' => $userId ?? auth()->id(),
                'tipo' => $tipo,
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'motivo' => $motivo,
            ]);

            // 4. Actualizar el stock en la tabla principal
            $inventory->update([
                'stock_producto' => $stockNuevo,
            ]);

            return $inventory;
        });
    }
}
